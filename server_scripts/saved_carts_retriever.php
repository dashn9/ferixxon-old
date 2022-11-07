<?php
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
class saved_carts_retriever
{
    //Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "youxerze",
        SQL_USERNAME = "phantom",
        SQL_PASSWORD = "tKD5KZ4K9\$M@";

    //PHP Data Object (PDO) variable to insert the PDO Object in.
    public $pdo_for_cart_retrieve;

    function __construct()
    {
        //creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
        $this->pdo_for_cart_retrieve = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD); 
    }
    function retrieve_and_parse_user_saved_carts($user_id) {
        echo $user_id;
    }
}
$account = new account();
if($account->user_id) {
    $cart_retriever = new saved_carts_retriever();
    $cart_retriever->retrieve_and_parse_user_saved_carts($account->user_id);
}   else {
        echo json_encode(["error_type" => "ERR_NO_LOG", "msg" => "You need to have an account signed in to retrieve saved carts"]);
    }
?>