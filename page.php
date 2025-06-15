
<?php 
session_start();
$sweetAlertConfig = ""; 
if (!isset($_SESSION['OwnerID'])) {
  header('Location: login.php');
  exit();
}
require_once('classes/database.php');
$con = new database();
$products = $con->getAllProductsWithPrice();
$categories = $con->getAllCategories();

// --- ORDER SAVE LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderData'])) {
    $orderData = json_decode($_POST['orderData'], true);
    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : 'cash';
    $ownerID = $_SESSION['OwnerID'];
    $totalAmount = 0;

    foreach ($orderData as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }

    $db = $con->opencon();

    // 1. Insert into ordersection (UserTypeID=1 for owner)
    $stmt = $db->prepare("INSERT INTO ordersection (CustomerID, EmployeeID, OwnerID, UserTypeID) VALUES (?, ?, ?, ?)");
    $stmt->execute([null, null, $ownerID, 1]);
    $orderSID = $db->lastInsertId();

    // 2. Insert into orders with the new OrderSID
    $stmt = $db->prepare("INSERT INTO orders (OrderDate, TotalAmount, OrderSID) VALUES (NOW(), ?, ?)");
    $stmt->execute([$totalAmount, $orderSID]);
    $orderID = $db->lastInsertId();

    // 3. Insert order details
    foreach ($orderData as $item) {
        $productID = intval(str_replace('product-', '', $item['id']));
        $priceID = isset($item['price_id']) ? $item['price_id'] : 1;
        $stmt = $db->prepare("INSERT INTO orderdetails (OrderID, ProductID, PriceID, Quantity, Subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $orderID,
            $productID,
            $priceID,
            $item['quantity'],
            $item['price'] * $item['quantity']
        ]);
    }

    $_SESSION['last_payment_method'] = $paymentMethod;
    header("Location: mainpage.php");
    exit;
}
?>

<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Coffee Menu with Category Tabs and Add Item Functionality</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
   body { font-family: 'Inter', sans-serif; }
   #menu-scroll::-webkit-scrollbar { width: 6px; }
   #menu-scroll::-webkit-scrollbar-thumb { background-color: #c4b09a; border-radius: 10px; }
  </style>
 </head>
 <body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">
  <!-- Sidebar -->
  <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
   <button aria-label="Home" class="text-[#4B2E0E] text-xl" title="Home" type="button" onclick="window.location='mainpage.php'"><i class="fas fa-home"></i></button>
   <button aria-label="Cart" class="text-[#4B2E0E] text-xl" title="Cart" type="button" onclick="window.location='page.php'"><i class="fas fa-shopping-cart"></i></button>
   <button aria-label="Order List" class="text-[#4B2E0E] text-xl" title="Order List" type="button" onclick="window.location='orderlist.php'"><i class="fas fa-list"></i></button>
   <button aria-label="Box" class="text-[#4B2E0E] text-xl" title="Box" type="button" onclick="window.location='product.php'"><i class="fas fa-box"></i></button>
   <button aria-label="Chart" class="text-[#4B2E0E] text-xl" title="Chart" type="button" onclick="window.location='chart.php'"><i class="fas fa-chart-bar"></i></button>
   <button aria-label="Users" class="text-[#4B2E0E] text-xl" title="Users" type="button" onclick="window.location='user.php'"><i class="fas fa-users"></i></button>
   <button aria-label="Settings" class="text-[#4B2E0E] text-xl" title="Settings" type="button" onclick="window.location='setting.php'"><i class="fas fa-cog"></i></button>
   <button id="logout-btn" aria-label="Logout" name="logout" class="text-[#4B2E0E] text-xl" title="Logout" type="button"><i class="fas fa-sign-out-alt"></i></button>
  </aside>

  <!-- Main content -->
  <main class="flex-1 p-6 relative flex flex-col">
   <img alt="Background image of coffee beans" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover opacity-20 -z-10" height="800" src="https://storage.googleapis.com/a1aa/image/22cccae8-cc1a-4fb3-7955-287078a4f8d4.jpg" width="1200"/>
   <header class="mb-4">
    <p class="text-xs text-gray-400 mb-0.5">Welcome to Love Amaiah</p>
    <h1 class="text-[#4B2E0E] font-semibold text-xl mb-3">Name's Homepage</h1>
    <form aria-label="Search menu" class="w-full max-w-xs ml-auto relative" role="search">
     <input aria-label="Search menu" class="w-full rounded-full py-2 px-4 pr-10 text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4B2E0E]" placeholder="Search menu..." type="search"/>
     <button aria-label="Search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500" type="submit"><i class="fas fa-search"></i></button>
    </form>
   </header>

   <!-- Category buttons -->
   <nav aria-label="Coffee categories" class="flex flex-wrap gap-3 mb-3 max-w-xl" id="category-nav"></nav>
   <!-- Coffee Menu Grid -->
   <section aria-label="Coffee menu" class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-4 max-w-5xl max-h-[600px] overflow-y-auto shadow-lg flex-1" id="menu-scroll">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="menu-items"></div>
   </section>
  </main>
  
  <!-- Order summary -->
  <aside aria-label="Order summary" class="w-80 bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg flex flex-col justify-between p-4">
   <div>
    <?php
    $customer = isset($_GET['customer_name']) ? htmlspecialchars($_GET['customer_name']) : 'Guest';
    $orderType = isset($_GET['order_type']) ? strtoupper(htmlspecialchars($_GET['order_type'])) : 'DINE IN/TAKE OUT';
    ?>
    <button class="w-full bg-[#4B2E0E] text-white rounded-full py-2 text-sm font-semibold mb-4" type="button"><?php echo $orderType; ?></button>
    <h2 class="font-semibold text-[#4B2E0E] mb-2"><?php echo "{$customer}'s Order:"; ?></h2>
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
   // Dynamic menuData from PHP
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

   // Dynamic categories from PHP
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
         window.location.href = "logout.php";
       }
     });
   });

   confirmBtn.addEventListener("click", () => {
     Swal.fire({
       title: 'Select Payment Method',
       input: 'radio',
       inputOptions: {
         cash: 'Cash',
         card: 'Card',
         online: 'Online Bank'
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
         const inputPayment = document.createElement('input');
         inputPayment.type = 'hidden';
         inputPayment.name = 'paymentMethod';
         inputPayment.value = paymentMethod;
         form.appendChild(inputOrder);
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