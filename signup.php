<?php
//This class will be dealing with the signup operation only.
class signup_details_dealer {
	//Database login credentials
	private
	const HOST_NAME = "localhost",
		DATABASE_NAME = "youxerze",
		SQL_USERNAME = "phantom",
		SQL_PASSWORD = "tKD5KZ4K9\$M@";

	//User information variables
	private $name, $last_name, $e_mail, $telephone_number, $password;

	//PHP Data Object (PDO) variable to insert the PDO Object in.
	public $pdo_for_user_registration_sql;

	//In order for this class to be created, the name, last name, email, a bool to tell: true: it's an email, false: it's not an email(which by nature of this script will be dealt with like a phone number).
	public

	function __construct( $name, $last_name, $e_mail, $telephone_number, $password ) {
		//Sanitizing the name, last name, email, phone number parameters to prevent a stored and reflected xss in the future.
		$this->name = htmlspecialchars( $name );

		$this->last_name = htmlspecialchars( $last_name );

		$this->e_mail = htmlspecialchars( $e_mail );

		$this->telephone_number = htmlspecialchars( $telephone_number );
        
        $this->password = $password;

		//creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
		$this->pdo_for_user_registration_sql = new PDO( "mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD );

		//setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
		$this->pdo_for_user_registration_sql->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	//Using the system of the PDO to prepare values for database entry and also query them in
	public

	function prepare_user_for_database_entry_and_execute() {
		//Check if email or phone number is properly valid else kill further code execution.
		if ( !($this->validate_mail( $this->e_mail ) || $this->validate_telephone_number( $this->telephone_number )) ) {
			echo "ERR_EMAIL_PHONE_NUMBER_INVALIDATION";
			exit( 0 );
		}
		//Preparing the pdo for user registration - The next step is to bind value to the 'value indicators' indicated by words prefixed with ':'.
		$this->pdo_for_user_registration_sql = $this->pdo_for_user_registration_sql->prepare( "INSERT INTO `youxerze_baexxic_enfor`(`FEASTE_NAEME`, `LAESTE_NAEME`, `EE_MEOWL`, `FEONE_NEIMBA`, `PAERXEWEIRDE`, `THAETE_HEAREGEISTEIRED`) VALUES (:first_name, :last_name, :e_mail, :phone_number, :password, NOW())" );

		//Hashing Users password to help secure Users password in the event of a breach.(Good Security Practice)
		$this->password = password_hash( $this->password, PASSWORD_DEFAULT );

		//Binding information inputed by a user to 'value indicators' represented with words prefixed with ':'. Next step will be to execute the prepared statement
		$this->pdo_for_user_registration_sql->bindValue( ":first_name", $this->name );

		$this->pdo_for_user_registration_sql->bindValue( ":last_name", $this->last_name );

		$this->pdo_for_user_registration_sql->bindValue( ":e_mail", $this->e_mail );

		$this->pdo_for_user_registration_sql->bindValue( ":phone_number", $this->telephone_number );

		$this->pdo_for_user_registration_sql->bindValue( ":password", $this->password );

		//Finally executing the prepared statement
		$this->pdo_for_user_registration_sql->execute();
        
        header("Location: http://mnd.localhost");
	}

	//Use filer var to validate mail return true(true) if email if valid and 0(false) if its not.
	public static

	function validate_mail( $e_mail ) {
		if ( filter_var( $e_mail, FILTER_VALIDATE_EMAIL ) )
			return true;
		else
			return false;
	}

	//Validate telephone number depending on country
	public static

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
				return true;
			}
		} else if ( strlen( $tel_to_test ) == 10 ) {
			if ( preg_match( $nigeria_tel_regex, $tel_to_test ) ) {
				return true;
			}
		} else if ( strlen( $tel_to_test ) == 14 ) {
			if ( substr( $tel_to_test, 0, 4 ) == "+234" && preg_match( $nigeria_tel_regex, substr( $tel_to_test, 4 ) ) ) {
				return true;
			}
		} else if ( strlen( $tel_to_test ) == 13 ) {
			if ( substr( $tel_to_test, 0, 3 ) == "234" && preg_match( $nigeria_tel_regex, substr( $tel_to_test, 3 ) ) ) {
				return true;
			}
		}
		return false;
	}
}

class signup_validator_processor {
	public $name, $last_name, $e_mail, $telephone_number, $password, $signup_dealer;
	public $error_text;
	public

	function __construct() {
		//Retrieving all expected known input from the client side of the web application if any of the expected values from the user on the client side of the web application is not set or null.
		if ( $this->check_if_all_expected_values_isset_and_not_null() ) {
			$this->name = $_POST[ "name" ];

			$this->last_name = $_POST[ "last_name" ];

			$this->e_mail = $_POST[ "e_mail" ];

			$this->telephone_number = $_POST[ "telephone_number" ];

			$this->password = $_POST[ "password" ];

			if ( $this->validate_name() && $this->validate_last_name() && $this->validate_email() && $this->validate_password() && ( ($this->validate_email() || $this->validate_telephone_number()) && (empty($this->e_mail) xor empty($this->telephone_number)) || ($this->validate_email() && $this->validate_telephone_number()))) {
				//Creating a new signup_details_dealer object and passing it all the expected values it requires.
				$this->signup_dealer = new signup_details_dealer( $this->name, $this->last_name, $this->e_mail, $this->telephone_number, $this->password );

				//prepare and query the details in
				$this->signup_dealer->prepare_user_for_database_entry_and_execute();
			}

		}
	}
	//Checks if the name is inputed is valid
	public

	function validate_name() {
		if ( isset( $this->name ) ) {
			if ( empty( $this->name ) ) {
				$this->error_text = "Oops! Sorry, you forgot to enter your name";
				return false;
			} else if ( preg_match( "/[0-9~!@#$%^&*())_{}|:\"<>?`=+\-[\];',.\/\s]/", $this->name ) ) {
				$this->error_text = "Oops! Sorry, but the name seems to be invalid";
				return false;
			}
		}

		return true;
	}
	//Checks if the last name entered is valid
	public

	function validate_last_name() {
		if ( isset( $this->last_name ) ) {
			if ( empty( $this->last_name ) ) {
				$this->error_text = "Oops! Sorry, you forgot to enter your last name";
				return false;
			} else if ( preg_match( "/[0-9~!@#$%^&*())_{}|:\"<>?`=+\-[\];',.\/\s]/", $this->last_name ) ) {
				$this->error_text = "Oops! Sorry, but the last name seems to be invalid";
				return false;
			}
		}

		return true;
	}
	//Validates email inputed.
	public

	function validate_email() {
			if ( empty( $this->e_mail ) ) {
				if ( empty( $this->telephone_number ) ) {
					$this->error_text = "Oops! Sorry, you forgot to enter your email(E-mail or phone number is needed)";	
				}
                return false;

			} else if ( !( signup_details_dealer::validate_mail( $this->e_mail ) ) ) {
				$this->error_text = "Oops! Sorry, your email seems to be invalid";
				return false;
			}
		return true;
	}
	//Validates inputed mobile phone number
	public

	function validate_telephone_number() {
			if ( empty( $this->telephone_number ) ) {
				if ( empty( $this->e_mail ) ) {
					$this->error_text = "Oops! Sorry, you forgot to enter your phone number(E-mail or phone number is needed)";
				}
                return false;
			} else if ( !( signup_details_dealer::validate_telephone_number( $this->telephone_number ) ) ) {
				$this->error_text = "Oops! Sorry, your telephone number seems to be invalid";
				return false;
			}
		return true;
	}
	//Validate and perform security check on password
	public

	function validate_password() {
		if ( isset( $this->password ) ) {
			if ( empty( $this->password ) ) {
				$this->error_text = "Oops! Sorry, but a password is necessary";
				return false;
			} else if ( strlen( $this->password ) < 6 ) {
				$this->error_text = "Oops! Sorry, your password cannot be less than six(6) characters";
				return false;
			}
		}

		return true;
	}

	//A function to observe if all expected info on the user from the client side of the web application "is set" and not null
	function check_if_all_expected_values_isset_and_not_null() {
		//Actually check that all user values is set and not null, if all values passes this test, then return true
		if ( isset( $_POST[ "name" ], $_POST[ "last_name" ], $_POST[ "password" ] ) && ( isset( $_POST[ "e_mail" ] ) || isset( $_POST[ "telephone_number" ] ) ) ) {

			return true;

		}
		//If any of the values tested is not set or is null return false
		else

			return false;

	}


}

$signup_validator = new signup_validator_processor();
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Sign Up an Account</title>
	<link rel="stylesheet" type="text/css" hreflang="EN" href="stylers/signup.css"/>
	<link rel="stylesheet" type="text/css" hreflang="EN" href="stylers/header_footer.css"/>
</head>

<body>
<a href="https://www.ferixxon.com/" class="mnd-logo"><img src="/images/ferixxon-logo-1.png" alt="ferixxon log" width="20%" /></a>
	<div id="signup-form-parent">
		<h1>Sign Up</h1>
		<form id="signup-form" action="register" method="post" onSubmit="return signupElementsValidators.validateAllFormInput()">
			<?php if(false) { echo "<div class=\"signup-errors\" id=\"signup-head-error\">
				<p>SOMETHING WENT WRONG WHILE REGISTERING YOU, CONTACT THE ADMIN ON THIS <a href=\"#\">PAGE</a>
				</p>
			</div>"; }
			?>
			<div class="input-division"><label for="id-name">NAME: </label><input id="id-name" name="name" type="text" placeholder="Your first name" value="<?php echo $signup_validator->name; ?>"/>
				<?php if(!$signup_validator->validate_name()) echo "<p class=\"signup-errors signup-form-details-errors\" id=\"signup-form-details-error-name\">" . $signup_validator->error_text . "</p>"; ?>
			</div>
			<div class="input-division"><label for="id-last-name">LAST NAME: </label><input id="id-last-name" name="last_name" type="text" placeholder="Your last name" value="<?php echo $signup_validator->last_name; ?>" required/>
				<?php if(!$signup_validator->validate_last_name()) echo "<p class=\"signup-errors signup-form-details-errors\" id=\"signup-form-details-error-last-name\">" . $signup_validator->error_text . "</p>"; ?>
			</div>
			<div class="input-division"><label for="id-email">EMAIL: </label><input id="id-email" name="e_mail" type="email" placeholder="Email Address" value="<?php echo $signup_validator->e_mail; ?>"/>
				<?php if(!empty($signup_validator->e_mail) && !$signup_validator->validate_email()) echo "<p class=\"signup-errors signup-form-details-errors\" id=\"signup-form-details-error-email\">" . $signup_validator->error_text . "</p>"; ?>
			</div>
			<div class="input-division"><label for="id-telephone">Telephone Number: </label><input id="id-telephone" name="telephone_number" type="tel" placeholder="Telephone number" value="<?php echo $signup_validator->telephone_number; ?>"/>
				<?php if(!empty($signup_validator->telephone_number) && !$signup_validator->validate_telephone_number()) echo "<p class=\"signup-errors signup-form-details-errors\" id=\"signup-form-details-error-telephone-number\">" . $signup_validator->error_text . "</p>"; ?>
			</div>
			<div class="input-division"><label for="id-password">PASSWORD: </label><input id="id-password" name="password" type="password" placeholder="Your password" required/>
				<?php if(!$signup_validator->validate_password()) echo "<p class=\"signup-errors signup-form-details-errors\" id=\"signup-form-details-error-password\">" . $signup_validator->error_text . "</p>"; ?>
			</div>
			<div class="input-division"><label for="id-confirm-password">CONFIRM PASSWORD: </label><input id="id-confirm-password" name="confirm-password" type="password" placeholder="Confirm Your password" required/>
			</div>
			<div class="input-division">
				<input type="submit" id="id-submit-form" value="proceed >>>"/>
			</div>
		</form>
	</div>
	
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
	<script type="text/javascript" src="scripts/signup.js">
	</script>
</body>
</html>