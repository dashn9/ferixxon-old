<?php
session_name("user");
session_start();
class validators {
    function __construct()
    {
        
    }
	public static

	function validate_mail( $email ) {
		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) )
			return $email;
		else
			return false;
	}
    function validate_telephone_number( $tel_to_test ) {
		return self::validate_nigeria_telephone_number( $tel_to_test );
	}

	//Function using the power of regex to validate the most important facts of Nigerian phone numbers
	public static

	function validate_nigeria_telephone_number( $tel_to_test ) {
		//Regex patter indicating that the start of the string to test should possess either "70", "80", "81" or "90" and the rest to the end should be eight digits.
		$nigeria_tel_regex = "/^(70|80|81|90)\d{8}$/i";
		//The following IF blocks checks for the known phone number lengths, checks their prefixes which are: "0", none, "+234" and "234" accordingly and use the regex pattern above to validate the rest.
		if ( strlen( $tel_to_test ) == 11 ) {
			if ( substr( $tel_to_test, 0, 1 ) == 0 && preg_match( $nigeria_tel_regex, substr( $tel_to_test, 1 ) ) ) {
				return $tel_to_test;
			}
		} else if ( strlen( $tel_to_test ) == 10 ) {
			if ( preg_match( $nigeria_tel_regex, $tel_to_test ) ) {
				return $tel_to_test;
			}
		} else if ( strlen( $tel_to_test ) == 14 ) {
			if ( substr( $tel_to_test, 0, 4 ) == "+234" && preg_match( $nigeria_tel_regex, substr( $tel_to_test, 4 ) ) ) {
				return $tel_to_test;
			}
		} else if ( strlen( $tel_to_test ) == 13 ) {
			if ( substr( $tel_to_test, 0, 3 ) == "234" && preg_match( $nigeria_tel_regex, substr( $tel_to_test, 3 ) ) ) {
				return $tel_to_test;
			}
		}
		return false;
	}
	function check_if_address_ok($address) {
        if(strlen($address) >= 6 && strlen($address) <= 100) {
            if(!preg_match("/^[A-Za-z]+[A-Za-z0-9 ,]*$/", $address)) {
                return false;
			}
			return $address;
        }
        return false;
    }
}
class transaction_initializer {
	private $api_key = "sk_test_ab17d97347f80ba9742db8a821fb538b29c6db8e";

	//Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "orders",
        SQL_USERNAME = "odrs_handler",
        SQL_PASSWORD = "p%MU7F60A%6h";

    //PHP Data Object (PDO) variable to insert the PDO Object in.
    public $pdo_for_transaction_initialize;
	function __construct() {
		//creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
        $this->pdo_for_transaction_initialize = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);
		//setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
		$this->pdo_for_transaction_initialize->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	function initiate_transaction($amount, $email="fxn_default_payer@ferixxon.com") {
		$url = "https://api.paystack.co/transaction/initialize";

		$fields = [
			'email' => $email,
			'amount' => $amount * 100
		];

		$fields_string = http_build_query($fields);

		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Authorization: Bearer $this->api_key",
			"Cache-Control: no-cache",
		));
		
		//So that curl_exec returns the contents of the cURL; rather than echoing it
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
		
		//execute post
		$result = curl_exec($ch);
		return json_decode($result, true);
	}
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
	function save_transaction($user_id, $address, $email, $tel, $order_title, $order_total, $order_description, $order_serial, $order_access_code, $order_reference, $order_response, $order) {
		$query = "INSERT INTO initiated_transactions (USEAR_EYE_DE, HOARDER_SAEREALE, HOARDER_TEATTLE, HOARDER_THEASKRIPSHUENE, HOARDER, HOARDER_TOETTEALLE, EE_MEOWL, FEONE_NEIMBA, AEDDREASSE, AECESCE_KHOEDE, RHEPHRIEANCSE, FOOLE_RHEISPIONCSE, EYEPE_AEDREASSCE, USEARE_AGIENTE, THAETE_THIEME)
					VALUES(:user_id, :order_serial, :order_title, :order_description, :order, :order_total, :email, :telephone_number, :address, :access_code, :order_reference, :full_response, :ip_address, :user_agent, NOW())";

		$it_pdo_sql = $this->pdo_for_transaction_initialize->prepare($query);

		$it_pdo_sql->bindValue(":user_id", $user_id);
		$it_pdo_sql->bindValue(":order_serial", $order_serial);
		$it_pdo_sql->bindValue(":order_title", $order_title);
		$it_pdo_sql->bindValue(":order_description", $order_description);
		$it_pdo_sql->bindValue(":order", json_encode($order));
		$it_pdo_sql->bindValue(":order_total", $order_total);
		$it_pdo_sql->bindValue(":email", $email);
		$it_pdo_sql->bindValue(":telephone_number", $tel);
		$it_pdo_sql->bindValue(":address", $address);
		$it_pdo_sql->bindValue(":access_code", $order_access_code);
		$it_pdo_sql->bindValue(":order_reference", $order_reference);
		$it_pdo_sql->bindValue(":full_response", json_encode($order_response));
		$it_pdo_sql->bindValue(":ip_address", $_SERVER["REMOTE_ADDR"]);
		$it_pdo_sql->bindValue(":user_agent", htmlspecialchars($_SERVER["HTTP_USER_AGENT"]));

		return $it_pdo_sql->execute();

	}
	function auto_initiate_transaction($user_id, $address, $email, $tel, $cart, $order_total, $order_title, $order_description) {
		$order_info = $this->initiate_transaction($order_total, $email);
		if($order_info["status"]) {
			$this->save_transaction($user_id, $address, $email, $tel, $order_title, $order_total, $order_description, $this->generate_serial(), $order_info["data"]["access_code"], $order_info["data"]["reference"], $order_info, $cart);
			return $order_info["data"]["access_code"];
		}
		else {
			return false;
		}
	}
}
if(isset($_POST["addr"], $_POST["email"], $_POST["tel"], $_POST["order_title"], $_POST["order_desc"])){
	$validator = new validators();
	$email = $_POST["email"];
	$tel = $_POST["tel"];
	$order_title = $_POST["order_title"];
	$order_desc = $_POST["order_desc"];
	$addr = $validator->check_if_address_ok($_POST["addr"]);
	if(!$addr) {
		echo json_encode(["error_type" => "ADDR_INVALID", "msg" => "There seems to be something off about your address. The acceptable characters are [A-Z0-9S,SPACE]"]);
        exit();
	}
	if ( empty($email) ) {
		if ( empty($tel) ) {
			echo json_encode(["type" => "NO_EMAIL_TEL", "msg" => "You need to have at least one medium of contact from the two options"]);
			exit();
		}
	}
	else {
		$email = $validator->validate_mail($email);
		if (!$email) {
			echo json_encode(["type" => "EMAIL_INVALID", "msg" => "Your email seems to be invalid"]);
			exit();
		}
	}
	if (!empty($tel)) {
		$tel = $validator->validate_telephone_number($tel);
		if (!$tel) {
			echo json_encode(["type" => "TEL_INVALID", "msg" => "Your telephone number seems to be invalid"]);
			exit();
		}
	}
	if (!empty($order_title)) {
		if ($order_title > 30) {
			echo json_encode(["type" => "ORD_TIT_LEN", "msg" => "Your order title length can't be greater than 30"]);
			exit();
		}		 
	}
	if (!empty($order_desc)) {
		if ($order_desc > 300) {
			echo json_encode(["type" => "ORD_DESC_LEN", "msg" => "Your order description length can't be greater than 300"]);
			exit();
		}		 
	}
	if(isset($_SESSION["USER_ORDER_PRICE"], $_SESSION["USER_ORDER"])) {
		$cart = $_SESSION["USER_ORDER"];
		$transaction_amount = $_SESSION["USER_ORDER_PRICE"];
		$user_id = 0;
		if(isset($_SESSION["UNQ_ID"])) {
			$user_id = $_SESSION["UNQ_ID"];
		}
		$t_initer = new transaction_initializer();
		$access_code = $t_initer->auto_initiate_transaction($user_id, $addr, $email, $tel, $cart, $transaction_amount, $order_title, $order_desc);
		if ($access_code) {
			echo json_encode(["type" => "SUCCESS", "msg" => $access_code]);
		} 
		else {
			echo json_encode(["type" => "TRANSACTION_ERROR", "msg" => "We are unable to initiate your transaction, please refresh the page. If error persists, contact administrator"]);
		}
	}
	else {
		echo json_encode(["type" => "ADDR_INVALID", "msg" => "Can't Seem To Find The Price Of This Transaction, Please refresh the page"]);
	}
}
else {
	echo json_encode(["type" => "INC_DET", "msg" => "Some necessary information needed to process this transaction can't be found, reload and retry"]);
	exit();
}
?>