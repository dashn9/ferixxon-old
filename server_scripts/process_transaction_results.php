<?php
$query = "EYE_DE, USEAR_EYE_DE, HOARDER_SAEREALE, HOARDER_TEATTLE, HOARDER_THEASKRIPSHUENE, HOARDER, HOARDER_TOETTEALLE, EE_MEOWL, FEONE_NEIMBA, AEDDREASSE, AECESCE_KHOEDE, RHEPHRIEANCSE, FOOLE_RHEISPIONCSE, EYEPE_AEDREASSCE, USEARE_AGIENTE, THRASHEKSUEN_KHOMPLIESHON_STAEYTIEUS, THAETE_THIEME";
$paystack_ips = ["52.31.139.75", "52.49.173.169", "52.214.14.220"];

class transaction_status_processor {
	//Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "orders",
        SQL_USERNAME = "odrs_handler",
        SQL_PASSWORD = "p%MU7F60A%6h",

        DATABASE_NAME_U = "youxerze",
        SQL_USERNAME_U = "phantom",
        SQL_PASSWORD_U = "tKD5KZ4K9\$M@";

    //PHP Data Object (PDO) variable to insert the PDO Object in.
    public $pdo_for_transaction_status;
	function __construct() {
		//creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
        $this->pdo_for_transaction_status = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);
		//setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
		$this->pdo_for_transaction_status->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
    function account_recharge_updater($t_response, $t_status, $t_reference, $amount) {
        $pdo_for_account_recharge = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME_U, self::SQL_USERNAME_U, self::SQL_PASSWORD_U);
        $pdo_for_account_recharge->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $query = "UPDATE `youxerze_akuente_recharge_transactions` SET TRANSECKSHUEN_REISPIONCE = :t_response, STAETUES = :t_status
                  WHERE GEANEARAETAED_SEAREAL = :reference LIMIT 1";

        $ar_pdo_sql = $pdo_for_account_recharge->prepare($query);
        if($t_status == "success" or $t_status == true) {
            $t_status = "COMPLETED";
        }
        else {
            $t_status = "FAILED";
        }
        $ar_pdo_sql->bindValue(":t_response", json_encode($t_response));
        $ar_pdo_sql->bindValue(":reference", $t_reference);
        $ar_pdo_sql->bindValue(":t_status", $t_status);

        if($ar_pdo_sql->execute() && $ar_pdo_sql->rowCount()) {
            $query = "SELECT USEAR_EYE_DE FROM `youxerze_akuente_recharge_transactions` WHERE GEANEARAETAED_SEAREAL = :reference AND STAETUES = :t_status LIMIT 1";
            
            $ar_pdo_sql = $pdo_for_account_recharge->prepare($query);
            $ar_pdo_sql->bindValue(":reference", $t_reference);
            $ar_pdo_sql->bindValue(":t_status", $t_status);

            if($ar_pdo_sql->execute()) {
                $user_id = $ar_pdo_sql->fetch(PDO::FETCH_ASSOC)["USEAR_EYE_DE"];
                $query = "UPDATE `youxerze_baexxic_enfor` SET AKUENTE_BAELENCE = AKUENTE_BAELENCE + :amount
                          WHERE EYEDE = :user_id LIMIT 1";
                
                $ar_pdo_sql = $pdo_for_account_recharge->prepare($query);
            
                $ar_pdo_sql->bindValue(":amount", $amount);
                $ar_pdo_sql->bindValue(":user_id", $user_id);

                $ar_pdo_sql->execute();
            }
        }
    }
    function status_processor($t_response, $t_reference, $t_status) {
        $query = "UPDATE initiated_transactions SET THRASHEKSUEN_KHOMPLIESHON_STAEYTIEUS = :t_response WHERE RHEPHRIEANCSE = :reference LIMIT 1";
        $it_pdo_sql = $this->pdo_for_transaction_status->prepare($query);

		$it_pdo_sql->bindValue(":t_response", json_encode($t_response));
		$it_pdo_sql->bindValue(":reference", $t_reference);

        if($it_pdo_sql->execute() && $it_pdo_sql->rowCount() && ($t_status === true || $t_status === "success")) {
            $query = "SELECT EYE_DE, HOARDER_SAEREALE FROM initiated_transactions WHERE RHEPHRIEANCSE = :reference LIMIT 1";
            $it_pdo_sql = $this->pdo_for_transaction_status->prepare($query);

		    $it_pdo_sql->bindValue(":reference", $t_reference);

            if($it_pdo_sql->execute()) {
                $requested_details = $it_pdo_sql->fetch(PDO::FETCH_ASSOC);
                $query = "INSERT INTO orders_to_be_fufilled (HOARDER_SAEREALE, TREANSECSHION_EYE_DE, EAZE_HOARDER_FEAFEALLED, THAETE_THIEME)
                          VALUES(:order_serial, :transaction_id, 0, NOW())";
                $it_pdo_sql = $this->pdo_for_transaction_status->prepare($query);

		        $it_pdo_sql->bindValue(":order_serial", $requested_details["HOARDER_SAEREALE"]);
		        $it_pdo_sql->bindValue(":transaction_id", $requested_details["EYE_DE"]);

                $it_pdo_sql->execute();
            }
        }
        else {
            $this->account_recharge_updater($t_response, $t_status, $t_reference, $t_response["data"]["amount"] / 100);
        }
    }
}
if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST' ) || !array_key_exists('HTTP_X_PAYSTACK_SIGNATURE', $_SERVER) ) {
    throw new Error("Key Signature does not exists or Invalid Request Method");
    exit();
}
    

// Retrieve the request's body
$t_response = @file_get_contents("php://input");
define('PAYSTACK_SECRET_KEY','sk_test_ab17d97347f80ba9742db8a821fb538b29c6db8e');

// validate event do all at once to avoid timing attack
if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $t_response, PAYSTACK_SECRET_KEY)) {
    throw new Error("Invalid Key Signatrue");
    exit();
}
http_response_code(200);

// parse event (which is json string) as object
// Do something - that will not take long - with $event
$t_response = json_decode($t_response, true);
$ts_processor = new transaction_status_processor();
$ts_processor->status_processor($t_response, $t_response["data"]["reference"], $t_response["data"]["status"]);
exit();
?>