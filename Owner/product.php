<?php

session_start();
if (!isset($_SESSION['OwnerID'])) {
  header('Location: ../all/login.php');
  exit();
}

require_once('../classes/database.php'); 
$con = new database();
$sweetAlertConfig = "";

if (isset($_POST['add_product'])) {
  $ownerID = $_SESSION['OwnerID'];
  $productName = $_POST['productName'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $effectiveFrom = $_POST['effectiveFrom'];
  $effectiveTo = !empty($_POST['effectiveTo']) ? $_POST['effectiveTo'] : null;

  $productID = $con->addProduct($productName, $category, $price, date('Y-m-d'), $effectiveFrom, $effectiveTo, $ownerID);

  if ($productID) {
    $sweetAlertConfig = "
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Product added.',
        confirmButtonText: 'OK'
      }).then(() => {
        window.location.href = 'product.php';
      });
    });
    </script>";
  } else {
    $sweetAlertConfig = "
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to add product.',
        confirmButtonText: 'OK'
      });
    });
    </script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Product List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
  <style>
    body { font-family: 'Inter', sans-serif; }
    .swal-input-label { font-size: 0.875rem; color: #4B2E0E; text-align: left; width: 100%; margin-top: 10px; margin-bottom: 5px; }
    .pagination-bar {
      position: absolute;
      bottom: 1rem;
      left: 0;
      right: 0;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 0.5rem;
    }
  </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">

<aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
    <img src="../images/logo.png" alt="Logo" class="w-10 h-10 rounded-full mb-4" />
    <?php $current = basename($_SERVER['PHP_SELF']); ?>   
    <button title="Dashboard" onclick="window.location.href='../Owner/dashboard.php'">
        <i class="fas fa-chart-line text-xl <?= $current == 'dashboard.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Home" onclick="window.location.href='../Owner/mainpage.php'">
        <i class="fas fa-home text-xl <?= $current == 'mainpage.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Cart" onclick="window.location.href='../Owner/page.php'">
        <i class="fas fa-shopping-cart text-xl <?= $current == 'page.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Order List" onclick="window.location.href='../all/tranlist.php'">
        <i class="fas fa-list text-xl <?= $current == 'tranlist.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Product List" onclick="window.location.href='../Owner/product.php'">
        <i class="fas fa-box text-xl <?= $current == 'product.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Employees" onclick="window.location.href='../Owner/user.php'">
        <i class="fas fa-users text-xl <?= $current == 'user.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Settings" onclick="window.location.href='../all/setting.php'">
        <i class="fas fa-cog text-xl <?= $current == 'setting.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button id="logout-btn" title="Logout">
        <i class="fas fa-sign-out-alt text-xl text-[#4B2E0E]"></i>
    </button>
</aside>

<main class="flex-1 p-6 relative flex flex-col">
  <header class="mb-4 flex items-center justify-between">
    <div>
      <h1 class="text-[#4B2E0E] font-semibold text-xl mb-1">Product List</h1>
      <p class="text-xs text-gray-400">Manage your products here</p>
    </div>
    <a href="#" id="add-product-btn" class="bg-[#4B2E0E] text-white rounded-full px-5 py-2 text-sm font-semibold shadow-md hover:bg-[#6b3e14] transition flex items-center">
      <i class="fas fa-plus mr-2"></i>Add Product
    </a>
  </header>

  <section class="bg-white rounded-xl p-4 w-full shadow-lg flex-1 overflow-x-auto relative">
    <table id="product-table" class="w-full text-sm">
      <thead>
        <tr class="text-left text-[#4B2E0E] border-b">
          <th class="py-2 px-4 w-[5%]">#</th>
          <th class="py-2 px-4 w-[20%]">Product Name</th>
          <th class="py-2 px-4 w-[15%]">Category</th>
          <th class="py-2 px-4 w-[10%]">Status</th>
          <th class="py-2 px-4 w-[10%]">Unit Price</th>
          <th class="py-2 px-4 w-[15%]">Effective From</th>
          <th class="py-2 px-4 w-[15%]">Effective To</th>
          <th class="py-2 px-4 w-[10%] text-center">Actions</th>
        </tr>
      </thead>
      <tbody id="product-body">
          <?php
      $products = $con->getJoinedProductData();
      usort($products, function($a, $b) {
          return $a['ProductID'] <=> $b['ProductID']; 
      });
      foreach ($products as $product) {
              ?>
        <tr class="border-b hover:bg-gray-50 <?= $product['is_available'] == 0 ? 'bg-red-50 text-gray-500' : '' ?>">
          <td class="py-2 px-4"><?= htmlspecialchars($product['ProductID']) ?></td>
          <td class="py-2 px-4 font-semibold <?= $product['is_available'] == 0 ? 'line-through' : '' ?>"><?= htmlspecialchars($product['ProductName']) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars($product['ProductCategory']) ?></td>
          <td class="py-2 px-4">
            <?php if ($product['is_available'] == 1): ?>
              <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">Available</span>
            <?php else: ?>
              <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-200">Archived</span>
            <?php endif; ?>
          </td>
          <td class="py-2 px-4">₱<?= htmlspecialchars(number_format($product['UnitPrice'], 2)) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars($product['Effective_From']) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars((string)($product['Effective_To'] ?? 'N/A')) ?></td>
          <td class="py-2 px-4 text-center">
            <?php if ($product['is_available'] == 1): ?>
              <button class="text-blue-600 hover:underline text-lg mr-2 edit-product-btn" title="Edit Price" data-product-name="<?= htmlspecialchars($product['ProductName']) ?>" data-price-id="<?= htmlspecialchars($product['PriceID']) ?>" data-unit-price="<?= htmlspecialchars($product['UnitPrice']) ?>" data-effective-from="<?= htmlspecialchars($product['Effective_From']) ?>" data-effective-to="<?= htmlspecialchars((string)($product['Effective_To'] ?? '')) ?>"><i class="fas fa-edit"></i></button>
              <button class="text-red-600 hover:underline text-lg archive-product-btn" title="Archive" data-product-id="<?= htmlspecialchars($product['ProductID']) ?>" data-product-name="<?= htmlspecialchars($product['ProductName']) ?>"><i class="fas fa-archive"></i></button>
            <?php else: ?>
              <button class="text-green-600 hover:underline text-lg restore-product-btn" title="Restore" data-product-id="<?= htmlspecialchars($product['ProductID']) ?>" data-product-name="<?= htmlspecialchars($product['ProductName']) ?>"><i class="fas fa-undo-alt"></i></button>
            <?php endif; ?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <div id="pagination" class="pagination-bar"></div>
  </section>

  <!-- Hidden form for adding a product -->
  <form id="add-product-form" method="POST" style="display:none;">
    <input type="hidden" name="productName" id="form-productName">
    <input type="hidden" name="category" id="form-category">
    <input type="hidden" name="price" id="form-price">
    <input type="hidden" name="effectiveFrom" id="form-effectiveFrom">
    <input type="hidden" name="effectiveTo" id="form-effectiveTo">
    <input type="hidden" name="add_product" value="1">
  </form>

  <?= $sweetAlertConfig ?>
</main>


<script>
document.getElementById('add-product-btn').addEventListener('click', function (e) {
  e.preventDefault();
  const categories = <?php echo json_encode($con->getAllCategories()); ?>;
  let categoryOptions = categories.map(cat => `<option value="${cat}">${cat}</option>`).join('');
  Swal.fire({
    title: 'Add Product',
    html: `
      <input id="swal-product-name" class="swal2-input" placeholder="Product Name">
      <select id="swal-category" class="swal2-input"><option value="">Select Category</option>${categoryOptions}</select>
      <input id="swal-price" class="swal2-input" type="number" step="0.01" placeholder="Unit Price">
      <p class="swal-input-label">Effective From</p>
      <input id="swal-effectiveFrom" class="swal2-input" type="date">
      <p class="swal-input-label">Effective To (Optional)</p>
      <input id="swal-effectiveTo" class="swal2-input" type="date">
    `,
    showCancelButton: true,
    confirmButtonText: 'Add',
    focusConfirm: false, 
    preConfirm: () => {
      const productName = document.getElementById('swal-product-name').value.trim();
      const category = document.getElementById('swal-category').value;
      const price = document.getElementById('swal-price').value;
      const effectiveFrom = document.getElementById('swal-effectiveFrom').value;
      const effectiveTo = document.getElementById('swal-effectiveTo').value;
      if (!productName || !category || !price || !effectiveFrom) {
        Swal.showValidationMessage('Product Name, Category, Price, and Effective From date are required.');
        return false;
      }
      document.getElementById('form-productName').value = productName;
      document.getElementById('form-category').value = category;
      document.getElementById('form-price').value = price;
      document.getElementById('form-effectiveFrom').value = effectiveFrom;
      document.getElementById('form-effectiveTo').value = effectiveTo;
      return true;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('add-product-form').submit();
    }
  });
});

function paginateTable(containerId, paginationId, rowsPerPage = 15) {
  const tbody = document.getElementById(containerId);
  const pagination = document.getElementById(paginationId);
  if (!tbody || !pagination) return;
  const rows = Array.from(tbody.children);
  const pageCount = Math.ceil(rows.length / rowsPerPage);
  let currentPage = 1;

  function showPage(page) {
    rows.forEach((row, i) => {
      row.style.display = (i >= (page - 1) * rowsPerPage && i < page * rowsPerPage) ? '' : 'none';
    });
    renderPagination();
  }

  function renderPagination() {
    pagination.innerHTML = '';
    const createButton = (text, onClick, isDisabled = false) => {
        const btn = document.createElement('button');
        btn.textContent = text;
        btn.disabled = isDisabled;
        btn.onclick = onClick;
        btn.className = "px-3 py-1 border rounded disabled:opacity-50";
        return btn;
    };
    
    pagination.appendChild(createButton('Prev', () => { if (currentPage > 1) { currentPage--; showPage(currentPage); } }, currentPage === 1));
    for (let i = 1; i <= pageCount; i++) {
        const btn = createButton(i, () => { currentPage = i; showPage(currentPage); });
        if (i === currentPage) btn.className += ' bg-[#4B2E0E] text-white';
        pagination.appendChild(btn);
    }
    pagination.appendChild(createButton('Next', () => { if (currentPage < pageCount) { currentPage++; showPage(currentPage); } }, currentPage === pageCount));
  }
  if (pageCount > 1) { showPage(currentPage); }
}

function initializeActionButtons() {
    document.querySelectorAll('.archive-product-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to archive "${productName}".`, icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, archive it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    fetch('archive_product.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Archived!', `${productName} has been archived.`, 'success').then(() => window.location.reload());
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to archive.', 'error');
                        }
                    });
                }
            });
        });
    });

    document.querySelectorAll('.restore-product-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to restore "${productName}".`, icon: 'info', showCancelButton: true,
                confirmButtonColor: '#28a745', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    fetch('restore_product.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Restored!', `${productName} has been restored.`, 'success').then(() => window.location.reload());
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to restore.', 'error');
                        }
                    });
                }
            });
        });
    });

    document.querySelectorAll('.edit-product-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productName = this.dataset.productName, priceId = this.dataset.priceId,
                  currentUnitPrice = this.dataset.unitPrice, currentEffectiveFrom = this.dataset.effectiveFrom,
                  currentEffectiveTo = this.dataset.effectiveTo; 
            Swal.fire({
                title: `Edit Price for ${productName}`,
                html: `
                <p class="swal-input-label">Unit Price</p>
                <input id="swal-edit-unitPrice" class="swal2-input" type="number" step="0.01" value="${currentUnitPrice}">
                <p class="swal-input-label">Effective From</p>
                <input id="swal-edit-effectiveFrom" class="swal2-input" type="date" value="${currentEffectiveFrom}">
                <p class="swal-input-label">Effective To (Optional)</p>
                <input id="swal-edit-effectiveTo" class="swal2-input" type="date" value="${currentEffectiveTo}">
                `,
                showCancelButton: true, confirmButtonText: 'Save Changes', focusConfirm: false,
                preConfirm: () => {
                    const unitPrice = document.getElementById('swal-edit-unitPrice').value;
                    const effectiveFrom = document.getElementById('swal-edit-effectiveFrom').value;
                    const effectiveTo = document.getElementById('swal-edit-effectiveTo').value;
                    if (!unitPrice || !effectiveFrom) {
                        Swal.showValidationMessage('Price and Effective From date are required.');
                        return false;
                    }
                    return { priceID: priceId, unitPrice: unitPrice, effectiveFrom: effectiveFrom, effectiveTo: effectiveTo };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { priceID, unitPrice, effectiveFrom, effectiveTo } = result.value;
                    const formData = new FormData();
                    formData.append('priceID', priceID); formData.append('unitPrice', unitPrice);
                    formData.append('effectiveFrom', effectiveFrom); formData.append('effectiveTo', effectiveTo); 
                    fetch('update_product.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Updated!', `${productName} price has been updated.`, 'success').then(() => window.location.reload());
                        } else {
                            Swal.fire('Error!', data.message || `Failed to update.`, 'error');
                        }
                    });
                }
            });
        });
    });
}

window.addEventListener('DOMContentLoaded', () => {
  paginateTable('product-body', 'pagination');
  initializeActionButtons();
});

document.getElementById('logout-btn').addEventListener('click', () => {
    Swal.fire({
        title: 'Are you sure you want to log out?', icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#4B2E0E', cancelButtonColor: '#d33', confirmButtonText: 'Yes, log out'
    }).then((result) => {
        if (result.isConfirmed) { window.location.href = "../all/logout.php"; }
    });
});
</script>

</body>
</html>