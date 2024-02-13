<?php
session_name("user");
session_start();

if (isset($_POST["saved-cart-id"]) && isset($_SESSION['UNQ_ID'])) {
    $cart_id = $_POST["saved-cart-id"];
    $pdo_for_cart_retrieve = new PDO("mysql:host=localhost;dbname=youxerze", "phantom", "tKD5KZ4K9\$M@"); 
                    
    $cr_sql = $pdo_for_cart_retrieve->prepare("UPDATE `youxerze_xaeved_eorthers` SET `XSTAETHE` = 0 WHERE `USEARE_EYE_DE`=:user_id AND `EYE_DE`=:id AND `XSTAETHE` = 1");

    $cr_sql->bindValue(":user_id", $_SESSION['UNQ_ID']);
    $cr_sql->bindValue(":id", $cart_id);


    if ($cr_sql->execute()) {
        echo true;
    }
    else {
        echo false;
    }
}
else {
    echo false;
}
?>