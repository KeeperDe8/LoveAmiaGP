<?php
session_start();
if (!isset($_SESSION['OwnerID'])) {
  header('Location: login.php');
  exit();
}
require_once('classes/database.php');
$con = new database();
$sweetAlertConfig = "";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['add_product'])) {
  $ownerID = $_SESSION['OwnerID'];
  $productName = $_POST['productName'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $createdAt = $_POST['createdAt'];
  $effectiveFrom = $_POST['effectiveFrom'];
  $effectiveTo = $_POST['effectiveTo'];

  $productID = $con->addProduct($productName, $category, $price, $createdAt, $effectiveFrom, $effectiveTo, $ownerID);

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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">

<!-- Sidebar -->
<aside class="bg-white w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <button class="text-[#4B2E0E] text-xl" title="Home" onclick="window.location='page.php'"><i class="fas fa-home"></i></button>
  <button class="text-[#4B2E0E] text-xl" title="Products"><i class="fas fa-boxes"></i></button>
  <button id="logout-btn" class="text-[#4B2E0E] text-xl" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
</aside>

<!-- Main Content -->
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

  <section class="bg-white rounded-xl p-4 max-w-6xl shadow-lg flex-1 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left text-[#4B2E0E] border-b">
          <th class="py-2 px-3">#</th>
          <th class="py-2 px-3">Product Name</th>
          <th class="py-2 px-3">Category</th>
          <th class="py-2 px-3">Created At</th>
          <th class="py-2 px-3">Unit Price</th>
          <th class="py-2 px-3">Effective From</th>
          <th class="py-2 px-3">Effective To</th>
          <th class="py-2 px-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $products = $con->getJoinedProductData();
        foreach ($products as $product) {
        ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="py-2 px-3"><?= $product['ProductID'] ?></td>
          <td class="py-2 px-3"><?= $product['ProductName'] ?></td>
          <td class="py-2 px-3"><?= $product['ProductCategory'] ?></td>
          <td class="py-2 px-3"><?= $product['Created_AT'] ?></td>
          <td class="py-2 px-3">₱<?= number_format($product['UnitPrice'], 2) ?></td>
          <td class="py-2 px-3"><?= $product['Effective_From'] ?></td>
          <td class="py-2 px-3"><?= $product['Effective_To'] ?></td>
          <td class="py-2 px-3">
            <a href="#" class="text-blue-600 hover:underline text-xs mr-2"><i class="fas fa-edit"></i></a>
            <a href="#" class="text-red-600 hover:underline text-xs"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>

  <!-- Hidden Form -->
  <form id="add-product-form" method="POST" style="display:none;">
    <input type="hidden" name="productName" id="form-productName">
    <input type="hidden" name="category" id="form-category">
    <input type="hidden" name="price" id="form-price">
    <input type="hidden" name="createdAt" id="form-createdAt">
    <input type="hidden" name="effectiveFrom" id="form-effectiveFrom">
    <input type="hidden" name="effectiveTo" id="form-effectiveTo">
    <input type="hidden" name="add_product" value="1">
  </form>

  <?= $sweetAlertConfig ?>
</main>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('add-product-btn').addEventListener('click', function (e) {
    e.preventDefault();
    Swal.fire({
      title: 'Add Product',
      html: `
        <input id="swal-product-name" class="swal2-input" placeholder="Product Name">
        <select id="swal-category" class="swal2-input">
          <option value="">Select Category</option>
          <option value="Coffee">Coffee</option>
          <option value="Non Coffee">Non Coffee</option>
          <option value="Matcha">Matcha</option>
          <option value="Frappe">Frappe</option>
          <option value="Signature">Signature</option>
        </select>
        <input id="swal-price" class="swal2-input" type="number" placeholder="Unit Price">
        <input id="swal-createdAt" class="swal2-input" type="date" placeholder="Created At">
        <input id="swal-effectiveFrom" class="swal2-input" type="date" placeholder="Effective From">
        <input id="swal-effectiveTo" class="swal2-input" type="date" placeholder="Effective To">
      `,
      showCancelButton: true,
      confirmButtonText: 'Add',
      preConfirm: () => {
        const productName = document.getElementById('swal-product-name').value.trim();
        const category = document.getElementById('swal-category').value;
        const price = document.getElementById('swal-price').value;
        const createdAt = document.getElementById('swal-createdAt').value;
        const effectiveFrom = document.getElementById('swal-effectiveFrom').value;
        const effectiveTo = document.getElementById('swal-effectiveTo').value;

        if (!productName || !category || !price || !createdAt || !effectiveFrom || !effectiveTo) {
          Swal.showValidationMessage('Please fill out all fields');
          return false;
        }

        document.getElementById('form-productName').value = productName;
        document.getElementById('form-category').value = category;
        document.getElementById('form-price').value = price;
        document.getElementById('form-createdAt').value = createdAt;
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

  document.getElementById('logout-btn').addEventListener('click', function(e) {
    e.preventDefault();
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
        window.location.href = 'logout.php';
      }
    });
  });
</script>

</body>
</html>
