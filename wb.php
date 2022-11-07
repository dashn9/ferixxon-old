<?php
class process_wb_info
{
	//Database login credentials
	private
	const HOST_NAME = "localhost",
		DATABASE_NAME = "userz_submitted_contentz",
		SQL_USERNAME = "userzer",
		SQL_PASSWORD = "pM7Ql576sGp^";

	//User information variables
	private $event_title, $event_desc;

	public $error_text;
	//PHP Data Object (PDO) variable to insert the PDO Object in.
	public $pdo_for_wb;

	//In order for this class to be created, the e_mail/phone number and password is needed
	public

	function __construct($event_title, $event_desc)
	{
		$this->event_title = $event_title;
		$this->event_desc = $event_desc;
		$this->is_credential_validated = false;

		//creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
		$this->pdo_for_wb = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);

		//setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
		$this->pdo_for_wb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
		$this->event_title = $this->validate_text_input($this->event_title, "title", 60);
		$this->event_desc = $this->validate_text_input($this->event_desc, "description", 400);
		if ($this->event_title && $this->event_desc) {
		} else {
			$fail = true;
		}
		return !$fail;
	}
	public function insert_into_db() {
		$query = "INSERT INTO `wistle_bloher` (EVIENTE_TIT, EVIENTE_DESCHE, USEARE_AGIENTE, EYE_PE, DATE)
			VALUES(:event_title, :event_desc, :ua, :ip, NOW())";

		$wb_processor_sql = $this->pdo_for_wb->prepare($query);

		$wb_processor_sql->bindValue(":event_title", htmlspecialchars($this->event_title));
		$wb_processor_sql->bindValue(":event_desc", htmlspecialchars($this->event_desc));
		$wb_processor_sql->bindValue(":ip", $_SERVER["REMOTE_ADDR"]);
		$wb_processor_sql->bindValue(":ua", $_SERVER["HTTP_USER_AGENT"]);

		$wb_processor_sql->execute();
	}
}
if (isset($_POST["event-title"], $_POST["event-desc"])) {
	$wb_processor = new process_wb_info($_POST["event-title"], $_POST["event-desc"]);
	if($wb_processor->validate_all_text_input()){
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Discovered someone using our service in a manner that violates our terms, please report, you will be duly compensated" />
    <link type="text/css" rel="stylesheet" href="./stylers/whistleblow.css" />
    <title>Report: Illegal activities</title>
</head>

<body>
    <div id="body-overlay">
        <a href="https://www.ferixxon.com/" class="mnd-logo"><img src="/images/ferixxon-logo-1.png" alt="ferixxon log" width="20%" /></a>
        <div id="form-body">
            <?php
            if ($wb_processor) {
                if ($wb_processor->validate_all_text_input()) {
                    $wb_processor->insert_into_db();
					echo "<div class=\"success\">You have successfully reported this incident, Thanks. <a href=\"https://www.ferixxon.com/\">GO HOME</a></div><br>";
                } else {
                    if($wb_processor->error_text != null) {
                        echo "<div class=\"error\">" . $wb_processor->error_text . "</div><br>";
                    }
                }
            } else {
                if($wb_processor->error_text != null) {
                    echo "<div class=\"error\">" . $wb_processor->error_text . "</div><br>";
                }
            }
            ?>
            <form method="post" action="wb.php">
                <div id="event-title-bar" class="inputs-bar">
                    <label id="event-title" for="event-title" class="text">What's going on?</label><br>
                    <input name="event-title" id="event-title" type="text" class="text" placeholder="Event Title" value="<?php if (isset($_POST["event-title"])) {
                                                                                                                            echo $_POST["event-title"];
                                                                                                                        } ?>"/>
                </div>
                <div id="event-desc-bar" class="inputs-bar">
                    <label id="event-desc" for="event-desc" class="text">Provide more information, if you can.</label><br>
                    <textarea name="event-desc" id="event-desc" type="event-desc" class="text" placeholder="Event Description" ><?php if (isset($_POST["event-desc"])) {
                                                                                                                            echo $_POST["event-desc"];
                                                                                                                        } ?></textarea>
                </div>
                <button id="submit">Tell Us</button>
            </form>
        </div>
    </div>
</body>

</html>