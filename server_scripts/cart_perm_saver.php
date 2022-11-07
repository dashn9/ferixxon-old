<?php
const PRODUCT_ORDER_MAX = 20;
const PRODUCT_ORDER_MIN = 1;
class product_dealer
{
    //Database login credentials
    private
	const HOST_NAME = "localhost",
		DATABASE_NAME = "dpl_db_fx",
		SQL_USERNAME = "collector",
		SQL_PASSWORD = "v2JE3!NMo6@i";

    //product nid variable
    private $product_nid;

    //PHP Data Object (PDO) variable to insert the PDO Object in.
    public $pdo_product_dealer;
    public $pdo_product_dealer_sql;

    public $query = "SELECT nid, title, field_product_price_per_unit_value AS ppu FROM
               (SELECT nid, title FROM `node_field_data`) a
               INNER JOIN
               (SELECT entity_id, field_product_price_per_unit_value FROM `node__field_product_price_per_unit`) b
               ON a.nid=b.entity_id
               WHERE a.nid=:product_nid LIMIT 1";

    //In order for this class to be created a product name is needed
    public

    function __construct()
    {
        //creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
        $this->pdo_product_dealer = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);
    }
    public function update_product_nid($product_nid)
    {
        $this->product_nid = $product_nid;
    }
    //Fetch and return
    public
    function fetch_product()
    {
        $this->pdo_product_dealer_sql = $this->pdo_product_dealer->prepare($this->query);
        $this->pdo_product_dealer_sql->bindValue(":product_nid", $this->product_nid);
        $this->pdo_product_dealer_sql->execute();
        return $this->pdo_product_dealer_sql->fetch(PDO::FETCH_ASSOC);
    }
    public function fetch_product_t_nid($product_nid)
    {
        $this->pdo_product_dealer_sql = $this->pdo_product_dealer->prepare($this->query);
        $this->pdo_product_dealer_sql->bindValue(":product_nid", $product_nid);
        $this->pdo_product_dealer_sql->execute();
        return $this->pdo_product_dealer_sql->fetch(PDO::FETCH_ASSOC);
    }
}
class account
{
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "youxerze",
        SQL_USERNAME = "phantom",
        SQL_PASSWORD = "tKD5KZ4K9\$M@";

    public $user_id = null;
    private $pdo_for_user_details_retriever;
    private $pdo_for_user_details_retriever_sql;

    public $name, $surname, $username, $email, $tel, $account_balance, $address, $catchphrase, $date_registered;

    function __construct()
    {
        session_name("user");
        session_start();

        if (isset($_SESSION['UNQ_ID'])) {
            $this->user_id = $_SESSION['UNQ_ID'];



            //creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
            $this->pdo_for_user_details_retriever = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);

            //setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
            $this->pdo_for_user_details_retriever->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->set_all_user_information();
        } else {
        }
    }

    function set_all_user_information()
    {
        if (!empty($this->user_id)) {
            $this->pdo_for_user_details_retriever_sql = $this->pdo_for_user_details_retriever->prepare("SELECT FEASTE_NAEME, LAESTE_NAEME, USEARNAEME,  EE_MEOWL, FEONE_NEIMBA, AKUENTE_BAELENCE, AEDREASSE, KAETCHPHRAEXZE, THAETE_HEAREGEISTEIRED FROM `youxerze_baexxic_enfor` WHERE EYEDE = :id LIMIT 1");

            $this->pdo_for_user_details_retriever_sql->bindValue(":id", $this->user_id);

            $this->pdo_for_user_details_retriever_sql->execute();

            $requested_details = $this->pdo_for_user_details_retriever_sql->fetch(PDO::FETCH_ASSOC);

            $this->name = $requested_details["FEASTE_NAEME"];
            $this->surname = $requested_details["LAESTE_NAEME"];
            $this->username = $requested_details["USEARNAEME"];
            $this->email = $requested_details["EE_MEOWL"];
            $this->tel = $requested_details["FEONE_NEIMBA"];
            $this->account_balance = $requested_details["AKUENTE_BAELENCE"];
            $this->address = json_decode($requested_details["AEDREASSE"]);
            $this->catchphrase = $requested_details["KAETCHPHRAEXZE"];
            $this->date_registered = $requested_details["THAETE_HEAREGEISTEIRED"];
        }
    }
}
class cart_processor_saver
{
    //Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "youxerze",
        SQL_USERNAME = "phantom",
        SQL_PASSWORD = "tKD5KZ4K9\$M@";

    //PHP Data Object (PDO) variable to insert the PDO Object in.
    public $pdo_for_cart_save;

    public $cart, $cart_title, $in_cart_valid = false;

    const MAX_CART_LEN = 6, MAX_CART_HEADER_LEN = 12, MAX_BATCH_LEN = 8;
    function __construct()
    {
        if ((isset($_POST["cart_title"]) && strlen($_POST["cart_title"]) > 0 && strlen($_POST["cart_title"]) <= 20) && !preg_match("/[^A-Za-z0-9 ()_-]/", $_POST["cart_title"]) && !preg_match("/[ ]{2,}/", $_POST["cart_title"]) && isset($_POST["cart"]) && !empty($_POST["cart"]) && $this->cart = json_decode($_POST["cart"])) {
            if (is_array($this->cart)) {
                $this->cart_title = trim($_POST["cart_title"]);
                $this->in_cart_valid = true;
                //creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
                $this->pdo_for_cart_save = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);
            } else {
                echo json_encode(["error_type" => "INV_ARR", "msg" => "Unrecognized data object, This object is not of type ARR"]);
                return false;
            }
        } else {
            echo json_encode(["error_type" => "INV_JSON", "msg" => "Unable to process data object sent"]);
            return false;
        }
    }
    function validate_cart($cart)
    {
        $is_cart_completely_empty = true;
        if (count($cart) >= 1 && count($cart) <= cart_processor_saver::MAX_CART_LEN) {

            foreach ($cart as $cart_el) {

                $cart_el_header = trim($cart_el[0]);

                if (strlen($cart_el_header) >= 1 && strlen($cart_el_header) <= cart_processor_saver::MAX_CART_HEADER_LEN) {
                    if (!preg_match("/[^A-Za-z0-9 ()_-]/", $cart_el_header) && !preg_match("/[ ]{2,}/", $cart_el_header)) {
                        if (count($cart_el[1]) >= 1) {
                            $is_cart_completely_empty = false;
                        }
                        if (count($cart_el[1]) <= cart_processor_saver::MAX_BATCH_LEN) {
                            foreach ($cart_el[1] as $cart_el_el) {
                                if (is_numeric($cart_el_el->quantity) && is_numeric($cart_el_el->productNid)) {
                                    if ($cart_el_el->quantity < PRODUCT_ORDER_MIN) {
                                        $cart_el_el->quantity = PRODUCT_ORDER_MIN;
                                    } else if ($cart_el_el->quantity > PRODUCT_ORDER_MAX) {
                                        $cart_el_el->quantity = PRODUCT_ORDER_MAX;
                                    }
                                } else {
                                    return json_encode(["error_type" => "INV_BCH_ORD", "msg" => "Product ID and Quantiy contain invalid characters i.e it is not a pure number"]);
                                }
                            }
                        } else {
                            return json_encode(["error_type" => "INV_BCH_LEN", "msg" => "1(One) or more of the Batches contain more than 8(eight) orders"]);
                        }
                    } else {
                        return json_encode(["error_type" => "INV_BCH_TLE_REG", "msg" => "One or more of the Batch Titles contains invalid characters, the acceptable characters are: [A-Za-z0-9 ()_-]; or has more than a single space in a row"]);
                    }
                } else {
                    return json_encode(["error_type" => "INV_BCH_TLE", "msg" => "Your Batch Title can't be less than 1(one) character and more than 12(twelve) characters"]);
                }
            }
            if ($is_cart_completely_empty) {
                return json_encode(["error_type" => "ERR_ARR_LEN", "msg" => "No Batch in this cart conatins at least one order"]);
            }
        } else {
            return json_encode(["error_type" => "ERR_CART_LEN", "msg" => "This cart either holds no batch or has more than 6(six) batches"]);
        }
        return true;
        
    }
    function process_and_save_cart($user_id, $order_title, $cart)
    {
        //Preparing the pdo for user registration - The next step is to bind value to the 'value indicators' indicated by words prefixed with ':'.
		$pdo_for_cart_save = $this->pdo_for_cart_save->prepare( "INSERT INTO `youxerze_xaeved_eorthers`(`USEARE_EYE_DE`, `EORTHER_THYTLE`, `EORTHER_KOENTHENTE`, `XSTAETHE`, `EYEPEE_HARDREZSE`, `USEAR_AEGIENTHE`, `THAETE_STAEMPE`) VALUES (:user_id, :order_title, :cart, :state, :user_ip, :user_agent, NOW())" );

        $pdo_for_cart_save->bindValue(":user_id", $user_id);
        $pdo_for_cart_save->bindValue(":order_title", $order_title);
        $pdo_for_cart_save->bindValue(":cart", $cart);
        $pdo_for_cart_save->bindValue(":state", 1);
        $pdo_for_cart_save->bindValue(":user_ip", $_SERVER["REMOTE_ADDR"]);
        $pdo_for_cart_save->bindValue(":user_agent", htmlspecialchars($_SERVER["HTTP_USER_AGENT"]));

        if($pdo_for_cart_save->execute()) {
            echo json_encode(["error_type" => "NO_ERR_SUCC", "msg" => "Your cart has been successfully saved"]);
            return true;
        }
        else {
            echo json_encode(["error_type" => "ERR_UP", "msg" => "Something went wrong while trying to save your cart"]);
            return false;
        }
    }
}
function main()
{
    $account = new account();
    if ($account->user_id) {
        $cart_processor = new cart_processor_saver();
        if ($cart_processor->in_cart_valid) {
            $cart_validation_result = $cart_processor->validate_cart($cart_processor->cart);
            if (!is_string($cart_validation_result)) {
                $cart_processor->process_and_save_cart($account->user_id, $cart_processor->cart_title, json_encode($cart_processor->cart));
            } else {
                echo $cart_validation_result;
            }
        }
    } else {
        echo json_encode(["error_type" => "ERR_NO_LOG", "msg" => "You need to have an account signed in to permanently save a cart"]);
    }
}
main();
