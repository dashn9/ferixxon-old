<?php
session_name("user");
session_start();

const HOST_NAME = "localhost",
    DATABASE_NAME = "youxerze",
    SQL_USERNAME = "phantom",
    SQL_PASSWORD = "tKD5KZ4K9\$M@";

function generate_serial($chars_len=16, $node_len=4) {
    $remaining_len = $chars_len;
    $serial = "";
    while($remaining_len > 0) {
        if($remaining_len < $node_len) {
            $node_len = $remaining_len;
        }
        $temp_node_len = $node_len / 2;
        if($temp_node_len < 1) {
            $temp_node_len = 1;
        }
        $serial .= bin2hex(random_bytes($temp_node_len));
        $remaining_len -= $node_len;
        if($remaining_len > 0) {
            $serial .= "-";
        }		
    }
    return $serial;
}
function initialize_recharge($user_id, $amount) {
    $pdo_for_transaction_initialize = new PDO("mysql:host=" . HOST_NAME . ";dbname=" . DATABASE_NAME, SQL_USERNAME, SQL_PASSWORD);
	$pdo_for_transaction_initialize->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $query = "INSERT INTO `youxerze_akuente_recharge_transactions` (USEAR_EYE_DE, GEANEARAETAED_SEAREAL, AMEUENTUE, STAETUES, EYE_PE, USEARE_AGIENTE, THAETE_TIME)
					VALUES(:user_id, :serial, :amount, :status, :ip_address, :user_agent, NOW())";

	$ar_pdo_sql = $pdo_for_transaction_initialize->prepare($query);

    $serial = generate_serial();
	$ar_pdo_sql->bindValue(":user_id", $user_id);
	$ar_pdo_sql->bindValue(":serial", $serial);
	$ar_pdo_sql->bindValue(":amount", $amount);
	$ar_pdo_sql->bindValue(":status", "PENDING");
	$ar_pdo_sql->bindValue(":ip_address", $_SERVER["REMOTE_ADDR"]);
	$ar_pdo_sql->bindValue(":user_agent", htmlspecialchars($_SERVER["HTTP_USER_AGENT"]));

    if($ar_pdo_sql->execute()) {
        return json_encode(["status" => "SUCC", "type" => "SUCCESS", "ref" => "$serial", "amt" => $amount*100]);
    }
    return json_encode(["status" => "ERR", "type" => "INIT_ERR", "msg" => "Unable to generate a serial for this transaction, if issue persist, please report to administrator"]);
}
if(isset($_SESSION["UNQ_ID"])) {
    if(isset($_GET["amount"])&& (int) $_GET["amount"] >= 100) {
        echo initialize_recharge($_SESSION["UNQ_ID"], (int) $_GET["amount"]);
    }
    else {
        echo json_encode(["status" => "ERR", "type" => "AMT_INV", "msg" => "You have to specify an amount - With N100 minimum"]);
    }
}
else {
    echo json_encode(["status" => "ERR", "type" => "USER_INV", "msg" => "You have probably been signed out from your account, please resign in"]);
}
?>