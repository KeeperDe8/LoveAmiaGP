<?php

class database{

    function opencon(){

        return new PDO(
            'mysql:host=localhost;
             dbname=amaiah',
            username: 'root',
            password: '');
        }

//register function
     function signupadmin($firstname, $lastname , $phonenum,$email,  $username,  $password ) {
        $con = $this->opencon();
 
        try{
    $con->beginTransaction();
    $stmt = $con->prepare("INSERT INTO owner(OwnerFN, OwnerLN, O_PhoneNumber,O_Email,Username,Password) VALUES(?,?,?,?,?,?)");
    $stmt->execute([$firstname, $lastname , $phonenum ,$email , $username,  $password ]);
    $userID = $con-> lastInsertId();
    $con->commit();
 
    return $userID;
 
        }catch (PDOException $e){
            $con->rollBack();
            return false;
        }
    }
function isUsernameExists($username){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) FROM owner WHERE Username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();
    return $count > 0;
 
}

function isEmailExists($email){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) FROM owner WHERE O_Email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();
    return $count > 0;
 
}

// Login function
function loginUser($username, $password){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT * FROM Owner WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user &&password_verify($password, $user['Password'])){
        return $user;
    }else{
        return false;
    }
}


function addEmployee($firstF, $firstN, $role, $date, $timeS, $timeE, $number, $email, $owerID){
    $con = $this->opencon();

    try {
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO employee (EmployeeFN, EmployeeLN, Role, HireDate, ShiftStart, ShiftEnd, E_PhoneNumber, E_Email, OwnerID) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstF, $firstN, $role, $date, $timeS, $timeE, $number, $email, $owerID]);
        $userID = $con->lastInsertId();
        $con->commit();

        return $userID;
    } catch (PDOException $e) {
        $con->rollBack();
        error_log("AddEmployee Error: " . $e->getMessage());
        return false;
    }
}


function getEmployee(){
    $con = $this->opencon();

    return $con->query("SELECT * FROM employee")->fetchAll();

}


function addProduct($productName, $category, $price, $createdAt, $effectiveFrom, $effectiveTo, $ownerID) {
    $con = $this->opencon();

    try {
        $con->beginTransaction();

        // Insert into product (Created_AT is auto, OwnerID does not exist)
        $stmt = $con->prepare("INSERT INTO product (ProductName, ProductCategory) 
                               VALUES (?, ?)");
        $stmt->execute([$productName, $category]);

        $productID = $con->lastInsertId();

        // Insert into productprices
        $stmt2 = $con->prepare("INSERT INTO productprices (ProductID, UnitPrice, Effective_From, Effective_To) 
                                VALUES (?, ?, ?, ?)");
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
                                  productprices.UnitPrice, productprices.Effective_From, productprices.Effective_To 
                           FROM product 
                           JOIN productprices ON product.ProductID = productprices.ProductID");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}











}