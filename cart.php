<?php
const PRODUCT_ORDER_MAX = 20;
const PRODUCT_ORDER_MIN = 1;

session_name("user");
session_start();
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
    public $pdo_for_cart;
    public $pdo_for_cart_sql;

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
        $this->pdo_for_cart = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);
    }
    public function update_product_nid($product_nid)
    {
        $this->product_nid = $product_nid;
    }
    //Fetch and return
    public
    function fetch_product()
    {
        $this->pdo_for_cart_sql = $this->pdo_for_cart->prepare($this->query);
        $this->pdo_for_cart_sql->bindValue(":product_nid", $this->product_nid);
        $this->pdo_for_cart_sql->execute();
        return $this->pdo_for_cart_sql->fetch(PDO::FETCH_ASSOC);
    }
    public function fetch_product_t_nid($product_nid)
    {
        $this->pdo_for_cart_sql = $this->pdo_for_cart->prepare($this->query);
        $this->pdo_for_cart_sql->bindValue(":product_nid", $product_nid);
        $this->pdo_for_cart_sql->execute();
        return $this->pdo_for_cart_sql->fetch(PDO::FETCH_ASSOC);
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
class process_cart
{
    public $product_dealer, $cart;

    const MAX_CART_LEN = 6, MAX_CART_HEADER_LEN = 6;
    function __construct()
    {
        $this->product_dealer = new product_dealer();
        try {
            $this->cart = json_decode($_POST["cart"]);
            if (!is_array($this->cart)) {
                throw new Error();
            }
        } catch (Error $error) {
            header("Location: https://www.ferixxon.com/", true, 302);
            echo $error;
        }
    }
    function generate_cart_elements($cart, $set_info_to_session=true)
    {
        $total_price = 0;
        foreach ($cart as $cart_el) {
            if ($cart_el[1]) {
                echo "<h2 class=\"batch-head\">" . $cart_el[0] . "</h2>";
            }
            foreach ($cart_el[1] as $cart_el_el) {
                if ($cart_el_el->quantity < PRODUCT_ORDER_MIN) {

                    $cart_el_el->quantity = PRODUCT_ORDER_MIN;
                } else if ($cart_el_el->quantity > PRODUCT_ORDER_MAX) {
                    $cart_el_el->quantity = PRODUCT_ORDER_MAX;
                }
                $details = $this->product_dealer->fetch_product_t_nid($cart_el_el->productNid);
                $total_price += ($cart_el_el->quantity * $details["ppu"]);
                echo "<div class=\"orders-elements\">" . $details["title"] . " x" . $cart_el_el->quantity . "<span class=\"order-el-price\">" . $cart_el_el->quantity * $details["ppu"] . "</span>" . "</div>";
            }
        }
        if ($set_info_to_session) {
            $_SESSION["USER_ORDER"] = $cart;
            $_SESSION["USER_ORDER_PRICE"] = $total_price;
        }
        echo "<div id=\"total\">TOTAL AMOUNT: <span id=\"total-amount\">" . number_format($total_price) . "</div>";
    }
}
$cart = new process_cart;
$account = new account;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Complete Order</title>
    <link type="text/css" rel="stylesheet" href="stylers/cart.css" />
    
</head>

<body>
    <div id="message-overlay">
        <div id="message-overlay-sub">
            <img class="exit-image" src="./front-icons/icons8-delete-24.png" onclick="orderProcessor.hideMessager()" />
            <div id="message-body"></div>
        </div>
    </div>
    <div id="interaction-blocker"></div>
    <div class="message-overlay">
        <img class="exit-image" src="./front-icons/icons8-delete-24.png" onclick="customPrompts.hideMessager()" />
        <div id="message-body">Are you sure?</div>
        <div id="input"><input type="text" required /><button id="submit-message-input">Submit</button></div>
        <div id="message-response"><button id="button-one">Yes</button><button id="button-two">No</button></div>
    </div>
    <div class="status-banner" style="display: none;"><span id="status-banner-text"></span><img onclick="document.getElementsByClassName('status-banner')[0].style.display = 'none';"id="status-banner-cancel" alt="a white cancel button for status banner" src="./front-icons/icons8-cancel-white-50.png" width="13px" height="13px" /> </div>
    <h2 id="header-completion">Delivery Information</h2>
        <div class="status-bar">
            <div class="status-fill"></div>
            <span id="status-1" class="status">1</span>
            <span id="status-2" class="status">2</span>
            <span id="status-3" class="status">3</span>
            <span id="status-4" class="status">4</span>
        </div>
    <div class="cart-pages" id="address-page">
        <div class="address">
            <h2>Delivery Details:</h2>
            <input name="addr" type="text" id="temp-address" class="temps" placeholder="Temporary address to deliver to" onfocus="cartPageHandler.uncheckPermAddresses()"/>
            <input name="email" type="email" id="email"  class="temps" placeholder="Your Email Address" <?php if($account->user_id) {echo "value=".$account->email." disabled";} ?>/>
            <input name="tel" type="tel" id="tel"  class="temps" placeholder="Your Telephone Number" onfocus="cartPageHandler.uncheckPermAddresses()" <?php if($account->user_id) {echo "value=".$account->tel." disabled";} ?>/>
            <div class="or-with-line"><span>OR</span></div>
            <?php
            if(!$account->user_id) {
                echo "<div id=\"address-message\"><a href=\"./signin\">Log In</a> to retrieve delivery details or <a href=\"./signup.php/\">Sign Up</a> to save contacts and addresses permanently</div>";
            }
            else if(!$account->address) {
                echo "<div>You don't have any saved addresses, if you wish to store an address for permanent use go to you <a>account dashboard</a>";
            }
            else {
                try {
                    echo "<div class=\"saved-addresses\">";
                    $addr_indx = 1;
                    foreach($account->address as $addr) {
                        echo "<div class=\"saved-address\"><input name=\"addr\" id=\"addr-".$addr_indx."\" type=\"radio\" value=\"".$addr."\" /><label for=\"addr-".$addr_indx."\">".$addr."</label></div>";
                        $addr_indx = $addr_indx + 1;
                    }
                    echo "</div>";
                }
                catch (Error $error) {

                }
                
            }
            ?>
        </div>
    </div>
    <div class="cart-pages" id="cart-view-page" style="display: none;">
        <div id="cart-view">
            <input type="text" class="order-heads" id="order-title" placeholder="Order Title (Not Necessary)" maxlength="30"/>
            <br />
            <textarea class="order-heads" id="order-description" placeholder="Order Description (Not Necessary)" maxlength="300"></textarea>
            <div class="cart-view-main">
                <?php
                $cart->generate_cart_elements($cart->cart);
                ?>
            </div>
        </div>
    </div>
    <div class="cart-pages" id="checkout-page" style="display: none;">
        <script src="https://js.paystack.co/v2/inline.js"></script>
    </div>
    <div class="cart-pages" id="receipt-page" style="display: none;">
        <div id="receipt-view">
            <div class="receipt-infos" id="payment-channel-info"><span class="receipt-info-label">Payment Channel</span> via:<span class="receipt-info-values">Paystack</span></div>
            <div class="receipt-infos" id="transaction-reference-info"><span class="receipt-info-label">Transaction Reference:</span><span class="receipt-info-values"></span></div>
            <div class="receipt-infos" id="transaction-info"><span class="receipt-info-label">Transaction N<u>o</u>:</span><span class="receipt-info-values"></span></div>
            <div class="receipt-infos" id="transaction-message-info"><span class="receipt-info-label">Transaction Message:</span><span class="receipt-info-values"></span></div>
            <div class="receipt-infos" id="payment-status-info"><span class="receipt-info-label">Payment Status:</span><span class="receipt-info-values"></span></div>
            <div class="receipt-main">
                <?php
                $cart->generate_cart_elements($cart->cart);
                ?>
            </div>
        </div>
        <div id="receipts-button-triggers-container"><button id="save-receipt" onclick="cartPageHandler.saveReceiptToPDF()">Save Receipt(PDF)</button></div>
    </div>
    <div id="cart-nav-parent">
        <button id="back" class="cart-nav" onclick="cartProcessor.reversePage()">Previous!</button>
        <button id="proceed" class="cart-nav" onclick="cartProcessor.fowardPage()">Next!</button>
    </div>
    <a href="https://www.ferixxon.com/">
        <div class="esc-home">f</div>
    </a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="/scripts/cart.js"></script>
</body>

</html>