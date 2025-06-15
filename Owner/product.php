<?php
// Start output buffering immediately to capture any stray output
ob_start();

session_start();
if (!isset($_SESSION['OwnerID'])) {
  header('Location: ../all/login.php');
  ob_end_clean(); // Discard any buffered output before redirect
  exit();
}

require_once('../classes/database.php'); // Corrected path to classes folder
$con = new database();
$sweetAlertConfig = "";

// Temporarily suppress all errors and prevent them from being displayed
// This will hide the "Deprecated" warning from showing on the page.
error_reporting(0);
ini_set('display_errors', 0);

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

// End the initial output buffering, allowing normal HTML/PHP output to proceed
ob_end_flush();
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
  <style>
    body { font-family: 'Inter', sans-serif; }
    #menu-scroll::-webkit-scrollbar { width: 6px; }
    #menu-scroll::-webkit-scrollbar-thumb { background-color: #c4b09a; border-radius: 10px; }
    /* Added styles for SweetAlert form validation */
    .swal-feedback { color: #dc3545; font-size: 13px; text-align: left; display: block; margin-bottom: 5px; }
    .swal2-input.is-valid { border: 2px solid #198754 !important; }
    .swal2-input.is-invalid { border: 2px solid #dc3545 !important; }
    /* Style for labels within SweetAlert */
    .swal-input-label {
        font-size: 0.875rem; /* text-sm equivalent */
        color: #4B2E0E; /* A darker color similar to your theme */
        text-align: left;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 5px;
    }
  </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">

<!-- Sidebar -->
<aside class="bg-white w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <button class="text-[#4B2E0E] text-xl" title="Home" onclick="window.location='page.php'"><i class="fas fa-home"></i></button>
  <button class="text-[#4B2E0E] text-xl" title="Products"><i class="fas fa-boxes"></i></button>
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
          <th class="py-2 px-3 text-center">Actions</th> <!-- Centered header for actions -->
        </tr>
      </thead>
      <tbody>
        <?php
        // IMPORTANT: Ensure getJoinedProductData() returns PriceID as well.
        // It should look like:
        // SELECT product.ProductID, product.ProductName, product.ProductCategory, product.Created_AT,
        //        productprices.UnitPrice, productprices.Effective_From, productprices.Effective_To,
        //        productprices.PriceID
        // FROM product JOIN productprices ON product.ProductID = productprices.ProductID
        // (If your current getJoinedProductData doesn't fetch PriceID, you need to update it in classes/database.php)
        $products = $con->getJoinedProductData();
        foreach ($products as $product) {
        ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="py-2 px-3"><?= htmlspecialchars($product['ProductID']) ?></td>
          <td class="py-2 px-3"><?= htmlspecialchars($product['ProductName']) ?></td>
          <td class="py-2 px-3"><?= htmlspecialchars($product['ProductCategory']) ?></td>
          <td class="py-2 px-3"><?= htmlspecialchars($product['Created_AT']) ?></td>
          <td class="py-2 px-3">₱<?= htmlspecialchars(number_format($product['UnitPrice'], 2)) ?></td>
          <td class="py-2 px-3"><?= htmlspecialchars($product['Effective_From']) ?></td>
          <td class="py-2 px-3"><?= htmlspecialchars((string)($product['Effective_To'] ?? 'N/A')) ?></td> <!-- Handle null Effective_To for display -->
          <td class="py-2 px-3 text-center"> <!-- Centered cell for actions -->
            <!-- Edit Button: Added new class and data attributes -->
            <a href="#" class="text-blue-600 hover:underline text-xs mr-2 edit-product-btn"
               data-product-id="<?= htmlspecialchars($product['ProductID']) ?>"
               data-product-name="<?= htmlspecialchars($product['ProductName']) ?>"
               data-price-id="<?= htmlspecialchars($product['PriceID']) ?>"
               data-unit-price="<?= htmlspecialchars($product['UnitPrice']) ?>"
               data-effective-from="<?= htmlspecialchars($product['Effective_From']) ?>"
               data-effective-to="<?= htmlspecialchars((string)($product['Effective_To'] ?? '')) ?>"> <!-- FIX: Explicitly cast to string here -->
              <i class="fas fa-edit"></i>
            </a>
            <!-- Delete Button -->
            <a href="#" class="text-red-600 hover:underline text-xs delete-product-btn"
               data-product-id="<?= htmlspecialchars($product['ProductID']) ?>"
               data-product-name="<?= htmlspecialchars($product['ProductName']) ?>">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>

  <!-- Hidden Form for Add Product -->
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

<script>
// --- Validation and Helper Functions (for Add Product) ---
document.getElementById('add-product-btn').addEventListener('click', function (e) {
  e.preventDefault();

  const categories = <?php
    $allCategories = $con->getAllCategories();
    echo json_encode($allCategories);
  ?>;

  let categoryOptions = categories.map(cat => `<option value="${cat}">${cat}</option>`).join('');

  Swal.fire({
    title: 'Add Product',
    html: `
      <input id="swal-product-name" class="swal2-input" placeholder="Product Name">
      <span class="swal-feedback" id="feedback-productName"></span>

      <select id="swal-category" class="swal2-input">
        <option value="">Select Category</option>
        ${categoryOptions}
      </select>
      <span class="swal-feedback" id="feedback-category"></span>
    
      <input id="swal-price" class="swal2-input" type="number" step="0.01" placeholder="Unit Price">
      <span class="swal-feedback" id="feedback-price"></span>
      
      <p class="swal-input-label">Created At</p> 
      <input id="swal-createdAt" class="swal2-input" type="date">
      <span class="swal-feedback" id="feedback-createdAt"></span>

      <p class="swal-input-label">Effective From</p>
      <input id="swal-effectiveFrom" class="swal2-input" type="date">
      <span class="swal-feedback" id="feedback-effectiveFrom"></span>

      <p class="swal-input-label">Effective To</p>
      <input id="swal-effectiveTo" class="swal2-input" type="date">
      <span class="swal-feedback" id="feedback-effectiveTo"></span>
    `,
    showCancelButton: true,
    confirmButtonText: 'Add',
    focusConfirm: false, // Prevents auto-focus on confirm button when validation message appears
    preConfirm: () => {
      const productName = document.getElementById('swal-product-name').value.trim();
      const category = document.getElementById('swal-category').value;
      const price = document.getElementById('swal-price').value;
      const createdAt = document.getElementById('swal-createdAt').value;
      const effectiveFrom = document.getElementById('swal-effectiveFrom').value;
      const effectiveTo = document.getElementById('swal-effectiveTo').value; // Can be empty

      let isValid = true;
      
      // Reset feedback
      document.querySelectorAll('#add-product-btn + .swal2-popup .swal-feedback').forEach(span => span.textContent = '');

      // Simple validation for required fields
      if (!productName) {
        document.getElementById('feedback-productName').textContent = 'Product Name is required.';
        isValid = false;
      }
      if (!category) {
        document.getElementById('feedback-category').textContent = 'Category is required.';
        isValid = false;
      }
      if (!price || isNaN(parseFloat(price)) || parseFloat(price) <= 0) {
        document.getElementById('feedback-price').textContent = 'Valid Unit Price is required.';
        isValid = false;
      }
      if (!createdAt) {
        document.getElementById('feedback-createdAt').textContent = 'Created At date is required.';
        isValid = false;
      }
      if (!effectiveFrom) {
        document.getElementById('feedback-effectiveFrom').textContent = 'Effective From date is required.';
        isValid = false;
      }

      // Date logic validation (Effective From <= Effective To)
      if (effectiveFrom && effectiveTo && new Date(effectiveFrom) > new Date(effectiveTo)) {
        document.getElementById('feedback-effectiveTo').textContent = 'Effective To must be after or same as Effective From.';
        isValid = false;
      }


      if (!isValid) {
        Swal.showValidationMessage('Please correct the errors.');
        return false;
      }

      document.getElementById('form-productName').value = productName;
      document.getElementById('form-category').value = category;
      document.getElementById('form-price').value = price;
      document.getElementById('form-createdAt').value = createdAt;
      document.getElementById('form-effectiveFrom').value = effectiveFrom;
      document.getElementById('form-effectiveTo').value = effectiveTo;

      return true;
    },
    didOpen: () => {
      // Add real-time validation feedback to inputs if needed
      const priceInput = document.getElementById('swal-price');
      priceInput.addEventListener('input', () => {
        const value = priceInput.value;
        const feedbackSpan = document.getElementById('feedback-price');
        if (!value || isNaN(parseFloat(value)) || parseFloat(value) <= 0) {
          feedbackSpan.textContent = 'Valid Unit Price is required.';
          priceInput.classList.add('is-invalid');
          priceInput.classList.remove('is-valid');
        } else {
          feedbackSpan.textContent = '';
          priceInput.classList.remove('is-invalid');
          priceInput.classList.add('is-valid');
        }
      });

      const effectiveFromInput = document.getElementById('swal-effectiveFrom');
      const effectiveToInput = document.getElementById('swal-effectiveTo');

      const validateDates = () => {
        const fromDate = effectiveFromInput.value;
        const toDate = effectiveToInput.value;
        const feedbackFrom = document.getElementById('feedback-effectiveFrom');
        const feedbackTo = document.getElementById('feedback-effectiveTo');

        // Reset
        feedbackFrom.textContent = '';
        feedbackTo.textContent = '';
        effectiveFromInput.classList.remove('is-valid', 'is-invalid');
        effectiveToInput.classList.remove('is-valid', 'is-invalid');

        if (!fromDate) {
          feedbackFrom.textContent = 'Effective From date is required.';
          effectiveFromInput.classList.add('is-invalid');
        } else {
          effectiveFromInput.classList.add('is-valid');
        }

        if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
          feedbackTo.textContent = 'Effective To must be after or same as Effective From.';
          effectiveToInput.classList.add('is-invalid');
        } else if (toDate) { // Only mark valid if there's a toDate
            effectiveToInput.classList.add('is-valid');
        }
      };

      effectiveFromInput.addEventListener('change', validateDates);
      effectiveToInput.addEventListener('change', validateDates);
    }
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('add-product-form').submit();
    }
  });
});

// --- JavaScript for Delete Product Button ---
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.delete-product-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault(); // Prevent default link behavior
 
      const productId = this.dataset.productId;
      const productName = this.dataset.productName;
 
      Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete ${productName}. This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          // Send AJAX request to delete the product
          const formData = new FormData();
          formData.append('product_id', productId);

          fetch('delete_product.php', { // Path to the delete product script in the same directory
            method: 'POST',
            body: formData
          })
          .then(response => {
            // Check if the response is OK before trying to parse as JSON
            if (!response.ok) {
                return response.text().then(text => { // Get raw text to see PHP errors/warnings
                    console.error('Server response was not OK:', text);
                    throw new Error(`Server returned status ${response.status}. Check console for details. Raw response: ${text.substring(0, 100)}...`);
                });
            }
            return response.json(); // Attempt to parse as JSON only if response is OK
          })
          .then(data => {
            if (data.success) {
              Swal.fire(
                'Deleted!',
                `${productName} has been deleted.`,
                'success'
              ).then(() => {
                this.closest('tr').remove();
              });
            } else {
              Swal.fire(
                'Error!',
                data.message || `Failed to delete ${productName}.`,
                'error'
              );
            }
          })
          .catch(error => {
            console.error('Fetch Error:', error);
            Swal.fire(
              'Error!',
              `An error occurred: ${error.message}`,
              'error'
            );
          });
        }
      });
    });
  });
});


// --- JavaScript for Edit Product Button ---
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.edit-product-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();

      const productId = this.dataset.productId;
      const productName = this.dataset.productName;
      const priceId = this.dataset.priceId;
      const currentUnitPrice = this.dataset.unitPrice;
      const currentEffectiveFrom = this.dataset.effectiveFrom;
      const currentEffectiveTo = this.dataset.effectiveTo; // This might be empty string for null

      Swal.fire({
        title: `Edit Price for ${productName}`,
        html: `
          <input id="swal-edit-priceId" type="hidden" value="${priceId}">
          <p class="swal-input-label">Unit Price</p>
          <input id="swal-edit-unitPrice" class="swal2-input" type="number" step="0.01" value="${currentUnitPrice}" placeholder="Unit Price">
          <span class="swal-feedback" id="feedback-edit-unitPrice"></span>
          
          <p class="swal-input-label">Effective From</p>
          <input id="swal-edit-effectiveFrom" class="swal2-input" type="date" value="${currentEffectiveFrom}">
          <span class="swal-feedback" id="feedback-edit-effectiveFrom"></span>

          <p class="swal-input-label">Effective To</p>
          <input id="swal-edit-effectiveTo" class="swal2-input" type="date" value="${currentEffectiveTo}">
          <span class="swal-feedback" id="feedback-edit-effectiveTo"></span>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save Changes',
        focusConfirm: false,
        preConfirm: () => {
          const unitPrice = document.getElementById('swal-edit-unitPrice').value;
          const effectiveFrom = document.getElementById('swal-edit-effectiveFrom').value;
          const effectiveTo = document.getElementById('swal-edit-effectiveTo').value;

          let isValid = true;

          // Reset feedback messages specific to this form
          document.querySelectorAll('.swal2-popup .swal-feedback').forEach(span => span.textContent = '');

          // Validate Unit Price
          if (!unitPrice || isNaN(parseFloat(unitPrice)) || parseFloat(unitPrice) <= 0) {
            document.getElementById('feedback-edit-unitPrice').textContent = 'Valid Unit Price is required.';
            isValid = false;
          }

          // Validate Effective From
          if (!effectiveFrom) {
            document.getElementById('feedback-edit-effectiveFrom').textContent = 'Effective From date is required.';
            isValid = false;
          }

          // Validate Effective To against Effective From
          if (effectiveFrom && effectiveTo && new Date(effectiveFrom) > new Date(effectiveTo)) {
            document.getElementById('feedback-edit-effectiveTo').textContent = 'Effective To must be after or same as Effective From.';
            isValid = false;
          }

          if (!isValid) {
            Swal.showValidationMessage('Please correct the errors.');
            return false;
          }

          return { priceID: priceId, unitPrice: unitPrice, effectiveFrom: effectiveFrom, effectiveTo: effectiveTo };
        },
        didOpen: () => {
          // Real-time validation for edit form
          const editUnitPriceInput = document.getElementById('swal-edit-unitPrice');
          const editEffectiveFromInput = document.getElementById('swal-edit-effectiveFrom');
          const editEffectiveToInput = document.getElementById('swal-edit-effectiveTo');

          const validateEditForm = () => {
            // Price validation
            const priceValue = editUnitPriceInput.value;
            const priceFeedback = document.getElementById('feedback-edit-unitPrice');
            if (!priceValue || isNaN(parseFloat(priceValue)) || parseFloat(priceValue) <= 0) {
                priceFeedback.textContent = 'Valid Unit Price is required.';
                editUnitPriceInput.classList.add('is-invalid');
                editUnitPriceInput.classList.remove('is-valid');
            } else {
                priceFeedback.textContent = '';
                editUnitPriceInput.classList.remove('is-invalid');
                editUnitPriceInput.classList.add('is-valid');
            }

            // Date validation
            const fromDate = editEffectiveFromInput.value;
            const toDate = editEffectiveToInput.value;
            const fromFeedback = document.getElementById('feedback-edit-effectiveFrom');
            const toFeedback = document.getElementById('feedback-edit-effectiveTo');

            fromFeedback.textContent = '';
            toFeedback.textContent = '';
            editEffectiveFromInput.classList.remove('is-valid', 'is-invalid');
            editEffectiveToInput.classList.remove('is-valid', 'is-invalid');

            if (!fromDate) {
                fromFeedback.textContent = 'Effective From date is required.';
                editEffectiveFromInput.classList.add('is-invalid');
            } else {
                editEffectiveFromInput.classList.add('is-valid');
            }

            if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
                toFeedback.textContent = 'Effective To must be after or same as Effective From.';
                editEffectiveToInput.classList.add('is-invalid');
            } else if (toDate) { // Only mark valid if there's a toDate
                editEffectiveToInput.classList.add('is-valid');
            }
          };

          editUnitPriceInput.addEventListener('input', validateEditForm);
          editEffectiveFromInput.addEventListener('change', validateEditForm);
          editEffectiveToInput.addEventListener('change', validateEditForm);
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const { priceID, unitPrice, effectiveFrom, effectiveTo } = result.value;

          const formData = new FormData();
          formData.append('priceID', priceID);
          formData.append('unitPrice', unitPrice);
          formData.append('effectiveFrom', effectiveFrom);
          formData.append('effectiveTo', effectiveTo); // Send empty string if null

          fetch('update_product.php', { // Path to the update product script
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              return response.text().then(text => {
                console.error('Server response for update was not OK:', text);
                throw new Error(`Server returned status ${response.status}. Raw response: ${text.substring(0, 100)}...`);
              });
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              Swal.fire(
                'Updated!',
                `${productName} price has been updated.`,
                'success'
              ).then(() => {
                // Reload the page to reflect changes
                window.location.reload();
              });
            } else {
              Swal.fire(
                'Error!',
                data.message || `Failed to update ${productName}.`,
                'error'
              );
            }
          })
          .catch(error => {
            console.error('Update Fetch Error:', error);
            Swal.fire(
              'Error!',
              `An error occurred: ${error.message}`,
              'error'
            );
          });
        }
      });
    });
  });
});
</script>

</body>
</html>