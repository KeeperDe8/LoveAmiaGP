<?php

session_start();

if (!isset($_SESSION['CustomerID'])) {

  if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
      header('Location: ../all/coffee.php');
      exit();
  }
}

require_once('../classes/database.php');
$con = new database();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderData'])) {
    if (!isset($_SESSION['CustomerID'])) {
        header('Location: ../all/login.php?error=session_expired');
        exit();
    }

    $orderData = json_decode($_POST['orderData'], true);
    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : 'gcash';
    $customerID = $_SESSION['CustomerID'];

    $result = $con->processOrder($orderData, $paymentMethod, $customerID, 'customer');

    if ($result['success']) {
        header("Location: ../Customer/transactionrecords.php");
        exit;
    } else {
        error_log("Customer Order Save Failed: " . $result['message']);
        header("Location: customerpage.php?error=order_failed");
        exit;
    }
}

$customer = isset($_SESSION['CustomerFN']) ? $_SESSION['CustomerFN'] : 'Guest';
$products = $con->getAllProductsWithPrice();
$categories = $con->getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Customer Order Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    #menu-scroll::-webkit-scrollbar { width: 6px; }
    #menu-scroll::-webkit-scrollbar-thumb { background-color: #c4b09a; border-radius: 10px; }
    @media (min-width: 1024px) {
      #menu-items { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
  </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">
  <!-- Sidebar -->
   <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
     <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <img src="../images/logo.png" alt="Logo" style="width: 56px; height: 56px; border-radius: 9999px; margin-bottom: 25px;" />
  <button aria-label="Home" class="text-xl" title="Home" type="button" onclick="window.location='../Customer/advertisement.php'">
    <i class="fas fa-home <?= $currentPage === 'advertisement.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button aria-label="Cart" class="text-xl" title="Cart" type="button" onclick="window.location='../Customer/customerpage.php'">
    <i class="fas fa-shopping-cart <?= $currentPage === 'customerpage.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button aria-label="Order List" class="text-xl" title="Order List" type="button" onclick="window.location='../Customer/transactionrecords.php'">
    <i class="fas fa-list <?= $currentPage === 'transactionrecords.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button aria-label="Settings" class="text-xl" title="Settings" type="button" onclick="window.location='../all/setting.php'">
    <i class="fas fa-cog <?= $currentPage === 'setting.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button id="logout-btn" aria-label="Logout" name="logout" class="text-xl" title="Logout" type="button">
    <i class="fas fa-sign-out-alt text-[#4B2E0E]"></i>
  </button>
</aside>

  <!-- Main content -->
  <main class="flex-1 p-6 relative flex flex-col">
    <img alt="Background image of coffee beans" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover opacity-20 -z-10" height="800" src="https://storage.googleapis.com/a1aa/image/22cccae8-cc1a-4fb3-7955-287078a4f8d4.jpg" width="1200"/>
    <header class="mb-4">
      <p class="text-xs text-gray-400 mb-0.5">Welcome, <?php echo htmlspecialchars($customer); ?></p>
      <h1 class="text-[#4B2E0E] font-semibold text-xl mb-3"><?php echo htmlspecialchars($customer); ?>'s Order</h1>
    </header>
 
    <!-- Category buttons -->
   <nav aria-label="Coffee categories" id="category-nav"
  class="flex gap-3 mb-3 overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-[#c4b09a] scrollbar-track-transparent px-1">
</nav>
   <!-- Coffee Menu Grid -->
  <section aria-label="Coffee menu" class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-4 max-h-[600px] overflow-y-auto shadow-lg flex-1" id="menu-scroll">
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="menu-items"></div>
</section>
  </main>
 
  <!-- Order summary -->
  <aside aria-label="Order summary" class="w-80 bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg flex flex-col p-4">
   <div class="flex-1 overflow-y-auto pr-2">
    <h2 class="font-semibold text-[#4B2E0E] mb-2"><?php echo htmlspecialchars($customer); ?>'s Order:</h2>  
    <div class="text-xs text-gray-700" id="order-list">
     <p class="font-semibold mb-1">CATEGORY</p>
    </div>
   </div>
   <div class="mt-6 text-center">
    <p class="font-semibold mb-1">Total:</p>
    <p class="text-4xl font-extrabold text-[#4B2E0E] flex justify-center items-center gap-1" id="order-total"><span>₱</span> 0.00</p>
   </div>
   <div class="mt-6 flex gap-4">
    <button class="flex-1 bg-green-500 text-white rounded-lg py-2 font-semibold hover:bg-green-600 transition" type="submit" id="confirm-btn" disabled>Confirm</button>
    <button class="flex-1 bg-red-500 text-white rounded-lg py-2 font-semibold hover:bg-red-600 transition" type="button" id="cancel-btn" disabled>Cancel</button>
   </div>
  </aside>
 
  <script>
   const menuData = <?php
echo json_encode(array_map(function($p) {
    return [
        'id' => 'product-' . $p['ProductID'],
        'name' => $p['ProductName'],
        'price' => floatval($p['UnitPrice']),
        'img' => 'https://placehold.co/80x80/png?text=' . urlencode($p['ProductName']),
        'alt' => $p['ProductName'],
        'category' => strtolower($p['ProductCategory']),
        'price_id' => $p['PriceID']
    ];
}, $products));
?>;
 
   const categories = <?php echo json_encode($categories); ?>;
   const categoryNav = document.getElementById('category-nav');
   function renderCategories() {
     categoryNav.innerHTML = categories.map((cat, idx) => `
       <button aria-pressed="${idx === 0 ? 'true' : 'false'}"
         class="flex items-center gap-2
           ${idx === 0 ? 'bg-[#4B2E0E] text-white shadow-md' : 'bg-white border border-gray-300 text-gray-700'}
           rounded-full py-2 px-5 text-sm font-semibold category-btn
           ${cat.trim().toLowerCase() === 'signatures' || cat.trim().toLowerCase() === 'signature' ? 'ring-2 ring-[#c19a6b] bg-yellow-100 text-[#4B2E0E] border-yellow-400' : ''}"
         data-category="${cat.toLowerCase()}" type="button">
         <i class="fas fa-coffee"></i> ${cat}
       </button>
     `).join('');
   }
   renderCategories();
 
   const menuContainer = document.getElementById("menu-items");
   const orderList = document.getElementById("order-list");
   const orderTotalEl = document.getElementById("order-total");
   const confirmBtn = document.getElementById("confirm-btn");
   const cancelBtn = document.getElementById("cancel-btn");
 
   let order = {};
   let currentCategory = categories.length > 0 ? categories[0].toLowerCase() : "";
 
   function renderMenu() {
     menuContainer.innerHTML = "";
     const filteredItems = menuData.filter(item => item.category === currentCategory);
     filteredItems.forEach(item => {
       const isInOrder = order[item.id] !== undefined;
       const quantity = isInOrder ? order[item.id].quantity : 0;
 
       const article = document.createElement("article");
       article.setAttribute("aria-label", `${item.name} coffee item`);
       article.className = "bg-white rounded-lg shadow-md p-3 flex flex-col items-center";
 
       const img = document.createElement("img");
       img.src = item.img;
       img.alt = item.alt;
       img.className = "mb-2";
       img.width = 80;
       img.height = 80;
 
       const h3 = document.createElement("h3");
       h3.className = "font-semibold text-sm text-[#4B2E0E] mb-1 text-center";
       h3.textContent = item.name;
 
       const pPrice = document.createElement("p");
       pPrice.className = "font-semibold text-xs text-[#4B2E0E] mb-2";
       pPrice.textContent = `₱ ${item.price.toFixed(2)}`;
 
       article.appendChild(img);
       article.appendChild(h3);
       article.appendChild(pPrice);
 
       if (isInOrder) {
         const controls = document.createElement("div");
         controls.className = "flex items-center gap-2";
 
         const btnMinus = document.createElement("button");
         btnMinus.type = "button";
         btnMinus.className = "bg-gray-300 rounded-full w-7 h-7 text-gray-600";
         btnMinus.textContent = "-";
         btnMinus.setAttribute("aria-label", `Decrease quantity of ${item.name}`);
         btnMinus.disabled = false;
         btnMinus.addEventListener("click", () => {
           if (quantity <= 1) {
             delete order[item.id];
             renderMenu();
             renderOrder();
           } else {
             updateQuantity(item.id, quantity - 1);
           }
         });
 
         const spanQty = document.createElement("span");
         spanQty.className = "text-sm font-semibold text-[#4B2E0E]";
         spanQty.textContent = quantity;
 
         const btnPlus = document.createElement("button");
         btnPlus.type = "button";
         btnPlus.className = "bg-[#C4A07A] rounded-full w-7 h-7 text-white font-bold";
         btnPlus.textContent = "+";
         btnPlus.setAttribute("aria-label", `Increase quantity of ${item.name}`);
         btnPlus.addEventListener("click", () => {
           updateQuantity(item.id, quantity + 1);
         });
 
         controls.appendChild(btnMinus);
         controls.appendChild(spanQty);
         controls.appendChild(btnPlus);
 
         article.appendChild(controls);
       } else {
         const addBtn = document.createElement("button");
         addBtn.type = "button";
         addBtn.className = "bg-[#C4A07A] rounded-full w-full py-1 text-xs font-semibold text-white";
         addBtn.textContent = "Add Item";
         addBtn.addEventListener("click", () => {
           addToOrder(item.id);
         });
         article.appendChild(addBtn);
       }
 
       menuContainer.appendChild(article);
     });
   }
 
   function addToOrder(id) {
     if (!order[id]) {
       const item = menuData.find(i => i.id === id);
       order[id] = {...item, quantity: 1};
       renderMenu();
       renderOrder();
     }
   }
 
   function updateQuantity(id, newQty) {
     if (newQty < 1) {
       delete order[id];
     } else {
       order[id].quantity = newQty;
     }
     renderMenu();
     renderOrder();
   }
 
   function renderOrder() {
     orderList.innerHTML = '<p class="font-semibold mb-1">CATEGORY</p>';
     const entries = Object.values(order);
     if (entries.length === 0) {
       orderTotalEl.textContent = "₱ 0.00";
       confirmBtn.disabled = true;
       cancelBtn.disabled = true;
       return;
     }
     let total = 0;
     entries.forEach(item => {
       total += item.price * item.quantity;
       const div = document.createElement("div");
       div.className = "flex justify-between mb-1";
       const spanName = document.createElement("span");
       spanName.className = "font-semibold";
       spanName.textContent = item.name;
       const spanPriceQty = document.createElement("span");
       spanPriceQty.innerHTML = `<span class="font-semibold">₱ ${item.price.toFixed(2)}</span><span class="ml-1">x${item.quantity}</span>`;
       div.appendChild(spanName);
       div.appendChild(spanPriceQty);
       orderList.appendChild(div);
     });
     orderTotalEl.innerHTML = `<span>₱</span> ${total.toFixed(2)}`;
     confirmBtn.disabled = false;
     cancelBtn.disabled = false;
   }
 
   cancelBtn.addEventListener("click", () => {
     order = {};
     renderMenu();
     renderOrder();
   });
 
   function attachCategoryEvents() {
     document.querySelectorAll(".category-btn").forEach(btn => {
       btn.addEventListener("click", () => {
         const selectedCategory = btn.getAttribute("data-category");
         if (selectedCategory === currentCategory) return;
         currentCategory = selectedCategory;
         document.querySelectorAll(".category-btn").forEach(b => {
           if (b === btn) {
             b.setAttribute("aria-pressed", "true");
             b.classList.add("bg-[#4B2E0E]", "text-white", "shadow-md");
             b.classList.remove("bg-white", "border", "border-gray-300", "text-gray-700");
           } else {
             b.setAttribute("aria-pressed", "false");
             b.classList.remove("bg-[#4B2E0E]", "text-white", "shadow-md");
             b.classList.add("bg-white", "border", "border-gray-300", "text-gray-700");
           }
         });
         renderMenu();
       });
     });
   }
 
   document.getElementById("logout-btn").addEventListener("click", () => {
     Swal.fire({
       title: 'Are you sure you want to log out?',
       icon: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#4B2E0E',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Yes, log out',
       cancelButtonText: 'Cancel'
     }).then((result) => {
       if (result.isConfirmed) {
         window.location.href = "../all/logoutcos.php";
       }
     });
   });
 
   confirmBtn.addEventListener("click", () => {
     Swal.fire({
       title: 'Select Payment Method',
       input: 'radio',
       inputOptions: {
         gcash: 'GCash'
       },
       inputValidator: (value) => {
         if (!value) {
           return 'You need to choose a payment method!';
         }
       },
       confirmButtonText: 'Proceed',
       showCancelButton: true
     }).then((result) => {
       if (result.isConfirmed) {
         const paymentMethod = result.value;
         const orderArray = Object.values(order).map(item => ({
           id: item.id,
           price: item.price,
           quantity: item.quantity,
           price_id: item.price_id
         }));
         
         const form = document.createElement('form');
         form.method = 'POST';
         form.style.display = 'none';

         const inputOrder = document.createElement('input');
         inputOrder.type = 'hidden';
         inputOrder.name = 'orderData';
         inputOrder.value = JSON.stringify(orderArray);
         form.appendChild(inputOrder);

         const inputPayment = document.createElement('input');
         inputPayment.type = 'hidden';
         inputPayment.name = 'paymentMethod';
         inputPayment.value = paymentMethod;
         form.appendChild(inputPayment);

         document.body.appendChild(form);
         form.submit();
       }
     });
   });
 
   renderMenu();
   renderOrder();
   attachCategoryEvents();
  </script>
 </body>
</html>