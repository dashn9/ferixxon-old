<?php
class process_contact_message
{
	//Database login credentials
	private
	const HOST_NAME = "localhost",
		DATABASE_NAME = "userz_submitted_contentz",
		SQL_USERNAME = "userzer",
		SQL_PASSWORD = "pM7Ql576sGp^";

	//User information variables
	private $name, $email, $subject, $message;

	public $error_text;
	//PHP Data Object (PDO) variable to insert the PDO Object in.
	public $pdo_for_contact;

	//In order for this class to be created, the e_mail/phone number and password is needed
	public

	function __construct($name, $email, $subject, $message)
	{
		$this->name = $name;
		$this->email = $email;
		$this->subject = $subject;
		$this->message = $message;
		$this->is_credential_validated = false;

		//creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
		$this->pdo_for_contact = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);

		//setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
		$this->pdo_for_contact->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	public function validate_text_input($text, $text_name, $length = 40)
	{
		if (empty($text)) {
			$this->error_text = "Oops! You forgot to enter the $text_name";
			return false;
		} else if ($text_name == "email") {
			if (!filter_var($text, FILTER_VALIDATE_EMAIL)) {
				$this->error_text = "Oops! Sorry, the $text_name seems to be invalid";
				return false;
			}
		} else if ($text_name == "name" && preg_match("/[0-9~!@#$%^&*|\"<>?`=+\[\]{};'\/]/", $text)) {
			$this->error_text = "Oops! Sorry, the $text_name seems to be invalid";
			return false;
		} else if (strlen($text) > $length) {
			$this->error_text = "Oops! Sorry, the $text_name exceeds it's maximum acceptable number of characters";
			return false;
		}
		return htmlspecialchars($text);
	}
	public function validate_all_text_input()
	{
		$fail = false;
		$this->name = $this->validate_text_input($this->name, "name", 60);
		$this->email = $this->validate_text_input($this->email, "email", 400);
		$this->subject = $this->validate_text_input($this->subject, "subject", 400);
		$this->message = $this->validate_text_input($this->message, "message", 400);
		if ($this->name && $this->email && $this->subject && $this->message) {
		} else {
			$fail = true;
		}
		return !$fail;
	}
	public function insert_into_db() {
		$query = "INSERT INTO `koentactz_horse` (NAEME, EMEALE, KOENTACTZ_SUBE, KOENTACTZ_MEZZ, USEAR_AGIENTE, EYE_PE, DATE)
			VALUES(:name, :email, :subject, :message, :ua, :ip, NOW())";

		$pc_sql = $this->pdo_for_contact->prepare($query);

		$pc_sql->bindValue(":name", $this->name);
		$pc_sql->bindValue(":email", $this->email);
		$pc_sql->bindValue(":subject", $this->subject);
		$pc_sql->bindValue(":message", $this->message);
		$pc_sql->bindValue(":ip", $_SERVER["REMOTE_ADDR"]);
		$pc_sql->bindValue(":ua", $_SERVER["HTTP_USER_AGENT"]);

		$pc_sql->execute();
	}
}
if (isset($_POST["name"], $_POST["email"], $_POST["subject"], $_POST["message"])) {
	$pc = new process_contact_message($_POST["name"], $_POST["email"], $_POST["subject"], $_POST["message"]);
	if($pc->validate_all_text_input()){
		$pc->insert_into_db();
		echo "OK";
	}
	echo $pc->error_text;
	exit();
}
?>
<!doctype html>
<html lang="en">

<head>
	<title>Contact | Ferixxon</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Any queries or complaint, we are here for you 24/7" />

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="stylers/contact.css">
	<link rel="stylesheet" href="stylers/header_footer.css">

</head>

<body>
	<a href="https://www.ferixxon.com/" class="fxn-logo"><img src="/images/ferixxon-logo-1.png" alt="ferixxon log" width="30%" /></a>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-10">
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-lg-10 col-md-12">
					<div class="wrapper">
						<div class="row no-gutters">
							<div class="col-md-7 d-flex align-items-stretch">
								<div class="contact-wrap w-100 p-md-5 p-4">
									<h3 class="mb-4">Get in touch</h3>
									<div id="form-message-warning" class="mb-4"></div>
									<div id="form-message-success" class="mb-4">
										Your message was sent, thank you!
									</div>
									<form method="POST" id="contactForm" name="contactForm">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" class="form-control" name="name" id="name" placeholder="Name">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<input type="email" class="form-control" name="email" id="email" placeholder="Email">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<textarea name="message" class="form-control" id="message" cols="30" rows="7" placeholder="Message"></textarea>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<input type="submit" value="Send Message" class="btn btn-primary">
													<div class="submitting"></div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="col-md-5 d-flex align-items-stretch">
								<div class="info-wrap bg-primary w-100 p-lg-5 p-4">
									<h3 class="mb-4 mt-md-4">Contact us</h3>
									<div class="dbox w-100 d-flex align-items-start">
										<div class="icon d-flex align-items-center justify-content-center">
											<span class="fa fa-map-marker"></span>
										</div>
										<div class="text pl-3">
											<p><span>Address:</span> Adekunle Ajasin, Akungba, Akoko</p>
										</div>
									</div>
									<div class="dbox w-100 d-flex align-items-center">
										<div class="icon d-flex align-items-center justify-content-center">
											<span class="fa fa-phone"></span>
										</div>
										<div class="text pl-3">
											<p><span>Phone:</span> <a href="tel://1234567920">+234 81 4543 6927</a></p>
										</div>
									</div>
									<div class="dbox w-100 d-flex align-items-center">
										<div class="icon d-flex align-items-center justify-content-center">
											<span class="fa fa-paper-plane"></span>
										</div>
										<div class="text pl-3">
											<p><span>Email:</span> <a href="mailto:care@ferixxon.com">care@ferixxon.com</a></p>
										</div>
									</div>
									<div class="dbox w-100 d-flex align-items-center">
										<div class="icon d-flex align-items-center justify-content-center">
											<span class="fa fa-globe"></span>
										</div>
										<div class="text pl-3">
											<p><span>Website</span> <a href="https://www.ferixxon.com">ferixxon</a></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
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
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.5/dist/popper.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
	<script src="scripts/contact.js"></script>

</body>

</html>