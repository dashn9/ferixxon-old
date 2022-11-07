<?php
//The following class will be responsible for user session and cookie management
class activity_controller
{

    function __construct($user_id, $username, $acc_bal)
    {
        $this->open_set_session($user_id, $username, $acc_bal, "https://www.ferixxon.com/account.php");
    }
    function open_set_session($user_id, $username, $acc_bal, $redirect)
    {
        //Start a new sesssion named user
        session_name("user");
        session_start();

        //Create a new session variable and assign the recently validated user id and username to it.
        $_SESSION["UNQ_ID"] = $user_id;
        $_SESSION["USERNAME"] = $username;
        $_SESSION["AKUENTE_BAELENCE"] = $acc_bal;

        //If a redirect argument was passed, redirect to the page using the 301 redirect code.
        if ($redirect) {
            header("Location: $redirect", true, "301");
        }
    }
}
//This class will be dealing with the signup operation only.
class signin_details_dealer
{
    //Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "youxerze",
        SQL_USERNAME = "phantom",
        SQL_PASSWORD = "tKD5KZ4K9\$M@";

    //User information variables
    private $e_mail_telephone_number, $password;

    //PHP Data Object (PDO) variable to insert the PDO Object in.
    public $pdo_for_user_authentication_sql;

    //variable holding the state of credentials validate
    public $is_credential_validated;

    //In order for this class to be created, the e_mail/phone number and password is needed
    public

    function __construct($e_mail_telephone_number, $password)
    {
        $this->is_credential_validated = false;

        $this->e_mail_telephone_number = $e_mail_telephone_number;
        $this->password = $password;

        //creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
        $this->pdo_for_user_authentication_sql = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);

        //setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
        $this->pdo_for_user_authentication_sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public

    function prepare_user_details_and_authenticate()
    {
        //Perform security checks on the password first
        if ($this->validate_password()) {
            //Checking if the mail_or_telephone number contents validate as either a mail or telephone number and authenticate based on the results else terminate as an invalid user head;
            if (signin_details_dealer::validate_mail($this->e_mail_telephone_number)) {
                $this->user_authentication(1);
            } else if (signin_details_dealer::validate_telephone_number($this->e_mail_telephone_number)) {
                $this->user_authentication((0));
            } else {
                //To insert an error message regarding invalid user head here;
            }
        }
    }
    //Using the system of the PDO to prepare values for database entry and also query them in
    public

    function user_authentication($mail_or_tel)
    {
        //Check if email or phone number is properly valid else kill further code execution.
        if ($this->validate_mail($this->e_mail_telephone_number) && $this->validate_telephone_number($this->e_mail_telephone_number)) {
            echo "ERR_EMAIL_PHONE_NUMBER_INVALIDATION";
            exit(0);
        }
        //Preparing the pdo for user authentication: Depending on the value passed, wether Email or Telephone Number, 1(true) for mail and 0(false) for tel.
        if ($mail_or_tel) {
            $this->pdo_for_user_authentication_sql = $this->pdo_for_user_authentication_sql->prepare("SELECT EYEDE, USEARNAEME, PAERXEWEIRDE, AKUENTE_BAELENCE FROM `youxerze_baexxic_enfor` WHERE EE_MEOWL = :e_mail_telephone_number LIMIT 1");
        } else if (!$mail_or_tel) {
            $this->pdo_for_user_authentication_sql = $this->pdo_for_user_authentication_sql->prepare("SELECT EYEDE, USEARNAEME, PAERXEWEIRDE, AKUENTE_BAELENCE FROM `youxerze_baexxic_enfor` WHERE FEONE_NEIMBA = :e_mail_telephone_number LIMIT 1");
        }
        //Binding information inputed by a user to 'value indicators' represented with words prefixed with ':'. Next step will be to execute the prepared statement
        $this->pdo_for_user_authentication_sql->bindValue(":e_mail_telephone_number", $this->e_mail_telephone_number);

        //Finally executing the prepared statement
        $this->pdo_for_user_authentication_sql->execute();

        //Fetch the values retrieved from the query execution
        $details_fetched = $this->pdo_for_user_authentication_sql->fetch(PDO::FETCH_ASSOC);
        if ($details_fetched) {
            if (SELF::verify_password($this->password, $details_fetched['PAERXEWEIRDE'])) {
                $this->is_credential_validated = true;
                new activity_controller($details_fetched['EYEDE'], $details_fetched['USEARNAEME'], $details_fetched["AKUENTE_BAELENCE"]);
            }
        }
    }
    //Validate and perform security check on password
    public

    function validate_password()
    {
        if (isset($this->password)) {
            if (empty($this->password)) {
                return false;
            } else if (strlen($this->password) < 6) {
                return false;
            }
            return true;
        }
    }

    public static

    function verify_password($password_to_validate, $password_to_validate_against)
    {
        if (password_verify($password_to_validate, $password_to_validate_against)) {
            return 1;
        } else {
            return 0;
        }
    }
    //Use filer var to validate mail return true(true) if email if valid and 0(false) if its not.
    public static

    function validate_mail($e_mail)
    {
        if (filter_var($e_mail, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }

    //Validate telephone number depending on country
    public static

    function validate_telephone_number($tel_to_test)
    {
        return  self::validate_nigeria_telephone_number($tel_to_test);
    }

    //Function using the power of regex to validate the most important facts of Nigerian phone numbers
    public static

    function validate_nigeria_telephone_number($tel_to_test)
    {
        //Regex patter indicating that the start of the string to test should possess either "70", "80", "81" or "90" and the rest to the end should be eight digits.
        $nigeria_tel_regex = "/^(70|80|81|90)\d{8}$/i";
        //The following IF blocks checks for the known phone number lengths, checks their prefixes which are: "0", none, "+234" and "234" accordingly and use the regex pattern above to validate the rest.
        if (strlen($tel_to_test) == 11) {
            if (substr($tel_to_test, 0, 1) == 0 && preg_match($nigeria_tel_regex, substr($tel_to_test, 1))) {
                return true;
            }
        } else if (strlen($tel_to_test) == 10) {
            if (preg_match($nigeria_tel_regex, $tel_to_test)) {
                return true;
            }
        } else if (strlen($tel_to_test) == 14) {
            if (substr($tel_to_test, 0, 4) == "+234" && preg_match($nigeria_tel_regex, substr($tel_to_test, 4))) {
                return true;
            }
        } else if (strlen($tel_to_test) == 13) {
            if (substr($tel_to_test, 0, 3) == "234" && preg_match($nigeria_tel_regex, substr($tel_to_test, 3))) {
                return true;
            }
        }
        return false;
    }
}

class signin_validator_processor
{
    public $e_mail_telephone_number, $password, $signin_dealer;
    public $error_text;

    function __construct()
    {
        if ($this->check_if_all_expected_values_isset_and_not_null()) {

            $this->e_mail_telephone_number = $_POST["e_mail_telephone_number"];

            $this->password = $_POST["password"];

            //Creating a new signin_details_dealer object and passing it all the expected values it requires.
            $this->signin_dealer = new signin_details_dealer($this->e_mail_telephone_number, $this->password);

            //prepare and query the details in
            $this->signin_dealer->prepare_user_details_and_authenticate();
        }
    }
    //validate e_mail_telephone_number as both an email or phone number
    public

    function validate_as_email_tel()
    {
        if (isset($this->e_mail_telephone_number)) {
            if (empty($this->e_mail_telephone_number)) {
                $this->error_text = "Oops! Sorry, Your registered E-mail or Phone number is needed.";
                return false;
            } else if (!(signin_details_dealer::validate_mail($this->e_mail_telephone_number) || signin_details_dealer::validate_telephone_number($this->e_mail_telephone_number))) {
                $this->error_text = "Oops! Sorry, Your registered E-mail or Phone number seems invalid.";
                return false;
            }
        }
        return true;
    }
    //Validate and perform security check on password
    public

    function validate_password()
    {
        if (isset($this->password)) {
            if (empty($this->password)) {
                $this->error_text = "Oops! Sorry, but a password is necessary";
                return false;
            } else if (strlen($this->password) < 6) {
                $this->error_text = "Oops! Sorry, Your password cannot be less than six(6) characters.";
                return false;
            } else if ((signin_details_dealer::validate_mail($this->e_mail_telephone_number) || signin_details_dealer::validate_telephone_number($this->e_mail_telephone_number)) && !$this->signin_dealer->is_credential_validated) {
                $this->error_text = "Oops! Sorry, The credentials you supplied are invalid, please check and try again.";
                return false;
            }
        }
        return true;
    }

    function check_if_all_expected_values_isset_and_not_null()
    {
        //Actually check that all user values is set and not null, if all values passes this test, then return true
        if (isset($_POST["e_mail_telephone_number"], $_POST["password"])) {
            return true;
        }
        if (empty($_POST["e_mail_telephone_number"])) {
            return false;
        } else if (empty($_POST["password"])) {
            return false;
        }
        //If any of the values tested is not set or is null return false
        else
            return false;
    }
}
session_name("user");
session_start();
if (isset($_SESSION["UNQ_ID"])) {
    header("Location: https://www.ferixxon.com/account.php", true, "301");
}
$signin_validator = new signin_validator_processor();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in</title>
    <link rel="stylesheet" type="text/css" href="stylers/header_footer.css" />
    <link rel="stylesheet" type="text/css" href="stylers/login.css" />
</head>

<body>
<a href="https://www.ferixxon.com/" class="fxn-logo"><img src="/images/ferixxon-logo-1.png" alt="ferixxon log" width="20%" /></a>
    <form id="login-form" action="signin" method="post" onSubmit="return loginElementsValidators.validateAllFormInput()">
        <h3>Sign In</h3>
        <div class="input-division">
            <label class="form-labels" for="id-email">EMAIL / TEL NUMBER: </label>
            <input class="form-inputs" id="id-email" name="e_mail_telephone_number" type="text" placeholder="Email Address" value="<?php echo $signin_validator->e_mail_telephone_number; ?>" />
            <?php if (!$signin_validator->validate_as_email_tel()) echo "<p class=\"signin-errors\" id=\"signin-form-details-error-email\">" . $signin_validator->error_text . "</p>"; ?>
        </div>
        <div class="input-division">
            <label class="form-labels" for="id-password">PASSWORD: </label>
            <input class="form-inputs" id="id-password" name="password" type="password" placeholder="Password" required />
            <?php if (!$signin_validator->validate_password()) echo "<p class=\"signin-errors\" id=\"signin-form-details-error-password\">" . $signin_validator->error_text . "</p>"; ?>
        </div>
        <div class="input-division">
            <input type="submit" id="id-submit-form" value="proceed >>>" />
        </div>
        <span id="no-account">Do not have an account yet? <a href="register">Sign Up</a> .</span>
    </form>
    
    <footer class="footer-t-bg">
	<div class="sub-divs footer-group-1">
            <ul>
                <li><img src="./front-icons/icons8-home-30-grey.png" width="16px" height="16px" alt="Home Icon - Ferixxon" /><a href="https://www.ferixxon.com/">Home</a></li>
                <li><img src="./front-icons/icons8-terms-and-conditions-24-grey.png" width="16px" height="16px" alt="Terms of Use Icon - Ferixxon" /><a>Terms of Use</a></li>
                <li><img src="./front-icons/icons8-about-30-grey.png" width="16px" height="16px" alt="About Us Icon - Ferixxon" /><a>About Us</a></li>
            </ul>
        </div>
        <div class="sub-divs footer-group-2">
            <ul>
                <li><img src="./front-icons/icons8-contact-us-24-grey.png" width="16px" height="16px" alt="Contact Us Icon - Ferixxon" /><a href="https://www.ferixxon.com/contact" >Contact Us</a></li>
                <li><img src="./front-icons/icons8-small-business-30-grey.png" width="16px" height="16px" alt="Business Icon - Ferixxon" /><a href="https://www.ferixxon.com/enlist-your-business" >Enlist your business</a></li>
                <li><img src="./front-icons/icons8-whistle-60-grey.png" width="16px" height="16px" alt="Whistle Icon - Ferixxon" /><a href="https://www.ferixxon.com/whistle-blower" >Whistleblower</a></li>
                <li><img src="./front-icons/icons8-idea-30-grey.png" width="16px" height="16px" alt="Idea(Suggestion) - Ferixxon" /><a>Raise a Suggestion</a></li>
            </ul>
        </div>
        <div class=" sub-divs footer-group-3">
            <ul>
                <li><img src="./front-icons/icons8-subscription-24-grey.png" width="16px" height="16px" alt="Subscription Icon - Ferixxon" /><a>Newsletter Subscription</a></li>
                <li><img src="./front-icons/icons8-discount-30-grey.png" width="16px" height="16px" alt="Promotion Icon - Ferixxon" /><a>Offers and Promotions</a></li>
                <li id="follow-us"><img src="./front-icons/icons8-love-circled-24-grey.png" width="16px" height="16px" alt="Follow(Love) Icon - Ferixxon" />Follow us:
                    <div class="follow-elements-bar">
                        <a><img src="./front-icons/icons8-instagram-50-grey.png" width="16px" height="16px" alt="Home Icon - Ferixxon" /></a>
                        <a><img src="./front-icons/icons8-facebook-50-grey.png" width="16px" height="16px" alt="Home Icon - Ferixxon" /></a>
                        <a><img src="./front-icons/icons8-twitter-50-grey.png" width="16px" height="16px" alt="Home Icon - Ferixxon" /></a>
                    </div>
                </li>
                <li><img src="./front-icons/icons8-icons8-24-grey.png" width="16px" height="16px" alt="Home Icon - Ferixxon" />Icons (<a href="./https://icons8.com" id="icons8-link">Icons 8</a>)</li>
            </ul>
        </div>
        <p id="footer-foot">All rights reserved &copy; ferixxon | 2022</p>
    </footer>
</body>

</html>