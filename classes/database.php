<?php


class database {

    function opencon() {
        return new PDO(
            'mysql:host=localhost;
            dbname=amaihatest', // VERIFY THIS DATABASE NAME IS CORRECT!
            username: 'root',
            password: ''
        );
    }

    // Register function
    function signupCustomer($firstname, $lastname, $phonenum, $email, $username, $password) {
    $con = $this->opencon();
    try {
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO customer (CustomerFN, CustomerLN, C_PhoneNumber, C_Email, C_Username, C_Password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstname, $lastname, $phonenum, $email, $username, $password]);
        $userID = $con->lastInsertId();
        $con->commit();
        return $userID;
    } catch (PDOException $e) {
        $con->rollBack();
        return false;
    }
}

function isUsernameExists($username) {
    $con = $this->opencon();

    // Check in customer table
    $stmt1 = $con->prepare("SELECT COUNT(*) FROM customer WHERE C_Username = ?");
    $stmt1->execute([$username]);
    $count1 = $stmt1->fetchColumn();

    // Check in employee table
    $stmt2 = $con->prepare("SELECT COUNT(*) FROM employee WHERE E_Username = ?");
    $stmt2->execute([$username]);
    $count2 = $stmt2->fetchColumn();

    return ($count1 > 0 || $count2 > 0);
}


function isEmailExists($email) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) FROM customer WHERE C_Email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();
    return $count > 0;
}

    // Login function
    function loginCustomer($username, $password) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT * FROM customer WHERE C_Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['C_Password'])) {
        return $user;
    } else {
        return false;
    }
}

    function loginOwner($username, $password) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT * FROM owner WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        return $user;
    } else {
        return false;
    }
}

// Employee login
function loginEmployee($username, $password) {
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT * FROM employee WHERE E_Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['E_Password'])) {
        return $user;
    } else {
        return false;
    }
}

    function addEmployee($firstF, $firstN, $Euser, $password, $role, $emailN, $number, $owerID): bool|string {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare("INSERT INTO employee (EmployeeFN, EmployeeLN, E_Username, E_Password, Role, E_PhoneNumber, E_Email, OwnerID) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstF, $firstN, $Euser, $password, $role, $number,$emailN, $owerID]);
            $userID = $con->lastInsertId();
            $con->commit();
            return $userID;
        } catch (PDOException $e) {
            $con->rollBack();
            error_log("AddEmployee Error: " . $e->getMessage());
            return false;
        }
    }

    function getEmployee() {
        $con = $this->opencon();
        return $con->query("SELECT * FROM employee")->fetchAll();
    }

    // New function to delete an employee (make sure this is in your classes/database.php)
    function deleteEmployee($employeeID): bool {
        $con = $this->opencon();
        try {
            $stmt = $con->prepare("DELETE FROM employee WHERE EmployeeID = ?");
            return $stmt->execute([$employeeID]);
        } catch (PDOException $e) {
            error_log("DeleteEmployee Error: " . $e->getMessage());
            return false;
        }
    }


     function deleteProduct($productID): bool {
        $con = $this->opencon();
        try {
            $con->beginTransaction();

            // First, delete associated product prices
            $stmt_prices = $con->prepare("DELETE FROM productprices WHERE ProductID = ?");
            $stmt_prices->execute([$productID]);

            // Now, delete the product itself
            $stmt_product = $con->prepare("DELETE FROM product WHERE ProductID = ?");
            $result = $stmt_product->execute([$productID]);

            $con->commit();
            return $result;
        } catch (PDOException $e) {
            $con->rollBack();
            error_log("DeleteProduct Error: " . $e->getMessage());
            if (strpos($e->getMessage(), 'Cannot delete or update a parent row: a foreign key constraint fails') !== false) {
            
            }
            return false;
        }
    }

    


     function updateProductPrice($priceID, $unitPrice, $effectiveFrom, $effectiveTo): bool {
        $con = $this->opencon();
        try {
            $stmt = $con->prepare("UPDATE productprices SET UnitPrice = ?, Effective_From = ?, Effective_To = ? WHERE PriceID = ?");
            // If effectiveTo is an empty string, convert it to NULL for the database
            $effectiveTo = empty($effectiveTo) ? NULL : $effectiveTo;
            return $stmt->execute([$unitPrice, $effectiveFrom, $effectiveTo, $priceID]);
        } catch (PDOException $e) {
            error_log("UpdateProductPrice Error: " . $e->getMessage());
            return false;
        }
    }

    function addProduct($productName, $category, $price, $createdAt, $effectiveFrom, $effectiveTo, $ownerID) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            // Insert into product (Created_AT is auto, OwnerID does not exist)
            $stmt = $con->prepare("INSERT INTO product (ProductName, ProductCategory) VALUES (?, ?)");
            $stmt->execute([$productName, $category]);
            $productID = $con->lastInsertId();
            // Insert into productprices
            $stmt2 = $con->prepare("INSERT INTO productprices (ProductID, UnitPrice, Effective_From, Effective_To) VALUES (?, ?, ?, ?)");
            $stmt2->execute([$productID, $price, $effectiveFrom, $effectiveTo]);
            $con->commit();
            return $productID;
        } catch (PDOException $e) {
            $con->rollBack();
            error_log("AddProduct Error: " . $e->getMessage());
            return false;
        }
    }
    function getJoinedProductData() {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT product.ProductID, product.ProductName, product.ProductCategory, product.Created_AT,
                                      productprices.UnitPrice, productprices.Effective_From, productprices.Effective_To,
                                      productprices.PriceID  -- <--- ADDED THIS LINE
                               FROM product
                               JOIN productprices ON product.ProductID = productprices.ProductID");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function isEmployeEmailExists($emailN) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT COUNT(*) FROM employee WHERE E_Email = ?");
        $stmt->execute([$emailN]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    function isEmployeeUserExists($Euser) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT COUNT(*) FROM employee WHERE E_Username = ?");
        $stmt->execute([$Euser]);
        return $stmt->fetchColumn() > 0;
    }

    function getAllProductsWithPrice() {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT 
                p.ProductID, 
                p.ProductName, 
                p.ProductCategory, 
                p.Created_AT,
                pp.UnitPrice,
                pp.PriceID
            FROM product p
            LEFT JOIN productprices pp ON p.ProductID = pp.ProductID
            GROUP BY p.ProductID
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all unique categories
    function getAllCategories() {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT DISTINCT ProductCategory FROM product");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

}