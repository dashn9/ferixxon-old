<?php
$query = "EYE_DE, USEAR_EYE_DE, HOARDER_SAEREALE, HOARDER_TEATTLE, HOARDER_THEASKRIPSHUENE, HOARDER, HOARDER_TOETTEALLE, EE_MEOWL, FEONE_NEIMBA, AEDDREASSE, AECESCE_KHOEDE, RHEPHRIEANCSE, FOOLE_RHEISPIONCSE, EYEPE_AEDREASSCE, USEARE_AGIENTE, THRASHEKSUEN_KHOMPLIESHON_STAEYTIEUS, THAETE_THIEME";
$paystack_ips = ["52.31.139.75", "52.49.173.169", "52.214.14.220"];

class transaction_status_processor {
	//Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "orders",
        SQL_USERNAME = "odrs_handler",
        SQL_PASSWORD = "p%MU7F60A%6h";

    //PHP Data Object (PDO) variable to insert the PDO Object in.
    public $pdo_for_transaction_status;
	function __construct() {
		//creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
        $this->pdo_for_transaction_status = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);
		//setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
		$this->pdo_for_transaction_status->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
    function status_processor($status, $t_reference, $t_status) {
        $query = "UPDATE initiated_transactions SET THRASHEKSUEN_KHOMPLIESHON_STAEYTIEUS = :status WHERE RHEPHRIEANCSE = :reference";
        $it_pdo_sql = $this->pdo_for_transaction_status->prepare($query);

		$it_pdo_sql->bindValue(":status", $status);
		$it_pdo_sql->bindValue(":reference", $t_reference);

        if($it_pdo_sql->execute() && $t_status == true) {
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
    }
}
if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST' ) || !array_key_exists('HTTP_X_PAYSTACK_SIGNATURE', $_SERVER) ) {
    throw new Error("Key Signature does not exists or Invalid Request Method");
    exit();
}
    

// Retrieve the request's body
$status = @file_get_contents("php://input");
define('PAYSTACK_SECRET_KEY','sk_test_ab17d97347f80ba9742db8a821fb538b29c6db8e');

// validate event do all at once to avoid timing attack
if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $status, PAYSTACK_SECRET_KEY)) {
    throw new Error("Invalid Key Signatrue");
    exit();
}
http_response_code(200);

// parse event (which is json string) as object
// Do something - that will not take long - with $event
$status = json_decode($status, true);
//$ts_processor = new transaction_status_processor();
//$ts_processor->status_processor(json_encode($status), $status["data"]["reference"], $status["data"]["status"]);
exit();
?>