<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>
    body {
        background: rgb(250, 250, 250);
    }
    #id {
        color: #555;
        text-align: center;
        opacity: 0.1;
        font-size: 45px;
        letter-spacing: 10px;
    }
    #welcome-head {
        background: #fff;
        font-size: 18px;
        margin-left: 12%;
        color: #555;
        padding: 1.5%;
        border-radius: 12px;
        box-shadow: 2px 4px 6px rgb(190, 190, 190);
        display: inline-block;
    }
    .orders-bar {
        background: #fff;
        color: #333;
        font-size: 17px;
        padding: 1.5%;
        border-radius: 4px;
        box-shadow: 2px 4px 6px rgb(190, 190, 190);
        width: 80%;
        margin: 3% auto 0;
    }
    .po-main-infos {
        display: inline-block;
        margin-left: 40px;
    }
</style>
<h1 id="id">FERIXXON</h1>
<?php
session_name("emps");
session_start();
if(isset($_SESSION["EMP_ID"], $_SESSION["EMP_NAME"])) {
    $emp_id = $_SESSION["EMP_ID"];
    $emp_name = $_SESSION["EMP_NAME"];

    echo "<h2 id=\"welcome-head\">Welcome, $emp_name</h2>";

    $pdo_for_order_details_retriever = new PDO("mysql:host=localhost;dbname=orders", "odrs_handler", "p%MU7F60A%6h");

    $odr_sql = $pdo_for_order_details_retriever->prepare("SELECT HOARDER_SAEREALE, THAETE_THIEME FROM `orders_to_be_fufilled` WHERE EAZE_HOARDER_FEAFEALLED = 0");

    $odr_sql->execute();
    
    $pending_orders = $odr_sql->fetchAll(PDO::FETCH_ASSOC);
    if($pending_orders) {
        foreach ($pending_orders as $po) {
            $pdo_for_order_details_retriever = new PDO("mysql:host=localhost;dbname=orders", "odrs_handler", "p%MU7F60A%6h");
            $odr_sql = $pdo_for_order_details_retriever->prepare("SELECT HOARDER, HOARDER_TOETTEALLE, EE_MEOWL, FEONE_NEIMBA, AEDDREASSE FROM `initiated_transactions` WHERE HOARDER_SAEREALE = :o_serial LIMIT 1");
            $odr_sql->bindValue(":o_serial", $po["HOARDER_SAEREALE"]);
            $odr_sql->execute();
            $po_extra = $odr_sql->fetch(PDO::FETCH_ASSOC);
            echo "<div id=\"po-order-bar\" class=\"orders-bar\">";
                echo "<span class=\"yellow-indicator\"></span>";
                echo "<span id=\"po-serial_no\" class=\"po-main-infos\"><u>order serial:</u> ".$po["HOARDER_SAEREALE"]."</span>";
                echo "<span id=\"po-total\" class=\"po-main-infos\"><u>email:</u> ".$po_extra["EE_MEOWL"]."</span>";
                echo "<span id=\"po-total\" class=\"po-main-infos\"><u>phone number:</u> ".$po_extra["FEONE_NEIMBA"]."</span>";
                echo "<span id=\"po-total\" class=\"po-main-infos\"><u>address:</u> ".$po_extra["AEDDREASSE"]."</span>";
                echo "<span id=\"po-total\" class=\"po-main-infos\"><u>order price:</u> ₦".$po_extra["HOARDER_TOETTEALLE"]."</span>";
                echo "<span id=\"po-date\" class=\"po-main-infos\"><u>date:</u> ".$po["THAETE_THIEME"]."</span>";
            echo "</div>";
        }
    }
    else {
        echo "<div id=\"no-pending\">There are no pending orders to be delivered, please wait";
    }
    
}
?>
</body>
</html>