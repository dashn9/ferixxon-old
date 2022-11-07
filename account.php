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

    public $name, $surname, $username, $email, $tel, $account_balance, $address, $catchphrase, $date_registered, $img_url;

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
            header("Location: /login.php", true, "302");
            die();
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
            $this->address = $requested_details["AEDREASSE"];
            $this->catchphrase = $requested_details["KAETCHPHRAEXZE"];
            $this->date_registered = $requested_details["THAETE_HEAREGEISTEIRED"];

            $this->pdo_for_user_details_retriever_sql = $this->pdo_for_user_details_retriever->prepare("SELECT EAMEAGE_YOUEREL FROM `youxerze_preafeale_eamaegezz` WHERE USEARE_EYE_DE = :id AND EAMEAGE_STEATIUSE = 1 LIMIT 1");

            $this->pdo_for_user_details_retriever_sql->bindValue(":id", $this->user_id);

            $this->pdo_for_user_details_retriever_sql->execute();

            $requested_details = $this->pdo_for_user_details_retriever_sql->fetch(PDO::FETCH_ASSOC);

            $this->img_url = $requested_details["EAMEAGE_YOUEREL"];
        }
    }
}
$user_account = new account();
?>
<!doctype html>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo ucfirst($user_account->name) . " " . ucfirst($user_account->surname); ?> | FERIXXON</title>
    <link href="stylers/account.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="interaction-blocker"></div>
    <div class="message-overlay">
        <img class="exit-image" src="front-icons/icons8-delete-24.png" onclick="customPrompts.hideMessager()" />
        <div id="message-body">Are you sure?</div>
        <div id="input"><input type="text" minlength="6" maxlength="70" required /><button id="submit-message-input">Submit</button></div>
        <div id="message-response"><button id="button-one">Yes</button><button id="button-two">No</button></div>
    </div>
    <div id="sub-body">
        <div id="user-image-handler-bar">
            <h1>Upload Profile Image</h1><img onclick="imageHandler.hideImageHandlerBar()" id="hide-image-handler-bar" src="/front-icons/icons8-cancel-48.png" width="30px" height="30px" alt="Cancel Icon - Ferixxon" />
            <div id="image-bar">
                <div id="image-sub-bar">
                    <div>
                        <canvas id="image-sub-bar-canvas" height="400" width="800"></canvas>
                        <div id="crop-handle"></div>
                    </div>
                </div>
                
                <button id="image-submit-button"><img src="/front-icons/icons8-server-48.png" width="20px" height="20px" alt="Server Save Icon - Ferixxon"/>Update Profile Image</button><button id="save-to-device-button"><img src="/front-icons/icons8-save-48.png" width="20px" height="20px" alt="Device Save Icon - Ferixxon"/>Save Image To Device</button>
            </div>
        </div>
        <aside id="account-links-aside-nav">
            <img src="./front-icons/icons8-go-back-24.png" alt="Back Icon - Ferixxon" id="hide-sidebar" onclick="accountInformationProcessor.hideSideBar()"/>
            <div id="user-light-details">
                <div id="user-image"><?php
                if($user_account->img_url) {
                    echo "<img src=\"".$user_account->img_url."\" alt=\"Your_Profile_Image\" width=\"120px\" height=\"120px\" />";
                }
                else {
                    echo "<span class=\"user-image-text\">".ucfirst(substr($user_account->name, 0, 1)) . ucfirst(substr($user_account->surname, 0, 1))."</span>";
                }
                ?> </div><label for="image-upload" class="file-upload">Change Avatar</label><input onchange="imageHandler.processImageToIhc()" id="image-upload" type="file" filetype="image/jpeg, image/png" hidden />

                <p id="username"><?php if($user_account->username || $user_account->username != "Edit Username") {
                    echo $user_account->username;
                }
                else {
                    echo "Your Username";
                }
                ?></p>
            </div>
            <nav id="account-links-nav">
                <ul id="account-links">
                    <li><img src="front-icons/icons8-summary-list-24.png" alt="account information icon" /><a href="#">Account Information</a>
                    </li>
                    <li><img src="front-icons/icons8-money-24.png" alt="money icon, recharge account" /><a href="#">Recharge Account</a>
                    </li>
                    <li><img src="front-icons/icons8-activity-history-24.png" alt="orders history icon" /><a href="#">Orders History</a>
                    </li>
                    <li><img src="front-icons/icons8-fast-cart-saved-24.png" alt="saved carts/orders icon" /><a href="#">Saved Orders</a>
                    </li>
                    <li><img src="front-icons/icons8-complaint-24.png" alt="complaint icon" /><a href="#">Make A Complaint</a>
                    </li>
                    <li id="logout"><img src="front-icons/icons8-exit-24.png" alt="logout icon" /><a href="./logout.php">Logout</a>
                    </li>
                </ul>
            </nav>
        </aside>
        <div id="content-body">
            <div class="horizontal-loader">
                <div></div>
            </div>

            <div id="account-information-content">
                <div class="header-bar">
                    <a href="https://www.ferixxon.com/" class="mnd-logo"><img src="/images/ferixxon-logo-1.png" alt="ferixxon logo" width="20%" /></a>
                    <div class="title-bar">
                        <img src="./front-icons/icons8-hamburger-menu-bar-with-parallel-navigation-button-24.png" alt="Menu Bar - Ferixxon" id="display-sidebar" onclick="accountInformationProcessor.displaySideBar();">
                        <h1>ACCOUNT INFORMATION</h1>
                    </div>
                    <div id="head-account-information">
                        <p class="balance"><span class="naira-currency">&#x20A6</span><span class="balance-value"><?php echo number_format($user_account->account_balance) ?></span>
                            <br /><span class="balance-label">Account Balance</span>
                        </p>
                        <p class="balance"><span class="balance-value"><?php echo 0 ?></span>
                            <br /><span class="balance-label">Total Orders</span>
                        </p>
                        <p class="balance"><span class="balance-value"><?php echo "3%" ?></span>
                            <br /><span class="balance-label">Trust Index</span>
                        </p>
                    </div>
                </div>
                <div class="body-bar">
                    <div class="account-information-sub basic-account-information">
                        <img class="edit-icon" onClick="accountInformationProcessor.activateBsiEditable(event, true, true, true)" src="front-icons/icons8-edit-property-24.png" alt="edit icon" />
                        <h2>Basic Account Information</h2>
                        <div class="account-info-body">
                            <p class="bsi-details bsi-name">Name: <span class="bsi-details-value" id="bsi-name-value"><?php echo $user_account->name ?></span>
                            </p>
                            <p class="bsi-details bsi-surname">Surame: <span class="bsi-details-value" id="bsi-surname-value"><?php echo $user_account->surname ?></span>
                            </p>
                            <p class="bsi-details bsi-username">Username: <span class="bsi-details-value" id="bsi-username-value"><?php echo $user_account->username ?></span>
                            </p>
                            <p class="bsi-details bsi-tel">Tel: <span class="bsi-details-value" id="bsi-tel-value"><?php echo $user_account->tel ?></span>
                            </p>
                            <p class="bsi-details bsi-email">Email: <span class="bsi-details-value" id="bsi-email-value"><?php echo $user_account->email ?></span>
                            </p>
                            <p class="bsi-details bsi-created-date">Date Created: <span class="bsi-details-value" id="bsi-date-created-value"><?php echo $user_account->date_registered ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="account-information-sub delivery-information">
                        <img class="edit-icon" onClick="accountInformationProcessor.activateDiEditable(event, true, true, true)" src="front-icons/icons8-edit-property-24.png" alt="edit icon" />
                        <h2>Delivery Information</h2>
                        <div class="account-info-body">
                            <p class="di-details di-catchprase">CatchPhrase: <span class="di-details-value" id="di-catchphrase-value"><?php echo $user_account->catchphrase; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="account-information-sub">
                        <h2>Payment Details</h2>
                        <div class="account-info-body">
                        </div>
                    </div>
                </div>
                <div class="body-bar-rudimentry"><button id="account-details-save" onclick="accountInformationProcessor.sendData()">SAVE DETAILS</button>
                </div>
            </div>
        </div>
        <script src="scripts/account.js"></script>
        <script>
            var address = <?php echo $user_account->address; ?>;
            if (Array.isArray(address)) {
                accountInformationProcessor.addresses = address;
                accountInformationProcessor.addressRenderer();
            }
        </script>
</body>

</html>