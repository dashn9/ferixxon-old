<?php
class process_wb_details
{
    //Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "youxerze",
        SQL_USERNAME = "phantom",
        SQL_PASSWORD = "thix cervixe ez fer de origeeneated cervixe oonly";

    //User information variables
    private $event_title, $event_desc;

    public $error_text;
    //PHP Data Object (PDO) variable to insert the PDO Object in.
    public $pdo_for_user_authentication_sql;

    //In order for this class to be created, the e_mail/phone number and password is needed
    public

    function __construct()
    {
        $this->is_credential_validated = false;

        //creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
        $this->pdo_for_user_authentication_sql = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);

        //setting the pdo 'Error Mode' attribue to 'ERRMODE_EXCEPTION' to allow display of errors in the event something goes wrong.
        $this->pdo_for_user_authentication_sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function check_if_info_set()
    {
        if (isset($_POST["event-title"], $_POST["event-desc"])) {
            $this->event_title = $_POST["event-title"];
            $this->event_desc = $_POST["event-desc"];
        } else {
            return false;
        }
    }
    public function validate_text_input($text, $text_name, $length = 40)
    {
        if (isset($text)) {
            if (empty($text)) {
                $this->error_text = "Oops! You forgot to enter the $text_name";
                return false;
            } else if (preg_match("/[0-9~!@#$%^&*|\"<>?`=+\[\]{};'\/]/", $text)) {
                if ($text_name == "email") {
                    if (!filter_var($text, FILTER_VALIDATE_EMAIL)) {
                        echo "donce $text_name";
                        $this->error_text = "Oops! Sorry, the $text_name seems to be invalid";
                        return false;
                    }
                } else {
                    $this->error_text = "Oops! Sorry, the $text_name seems to be invalid";
                    return false;
                }
            } else if (strlen($text) > $length) {
                $this->error_text = "Oops! Sorry, the $text_name exceeds it's maximum acceptable number of characters";
                return false;
            }
            return htmlspecialchars($text);
        }
        return false;
    }
    public function validate_all_text_input()
    {
        $fail = false;
        $this->event_title = $this->validate_text_input($this->event_title, "event title", 60);
        $this->event_desc = $this->validate_text_input($this->event_desc, "event description", 400);

        if ($this->event_title && $this->event_desc && $this->event_title) {
        } else {
            $fail = true;
        }
        return !$fail;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta title="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="./stylers/whistleblow.css" />
    <title>Report: Illegal activities</title>
</head>

<body>
    <div id="body-overlay">
        <a href="http://mnd" class="mnd-logo"><img src="/images/ferixxon-logo-1.png" alt="ferixxon log" width="20%" /></a>
        <div id="form-body">
            <?php
            $wb_processor = new process_wb_details();
            if ($wb_processor->check_if_info_set()) {
                if ($wb_processor->validate_all_text_input()) {
                    echo true;
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
            <form method="get" action="wb.php">
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