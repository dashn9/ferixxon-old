<?php
class process_biz_info
{
    //Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "youxerze",
        SQL_USERNAME = "phantom",
        SQL_PASSWORD = "thix cervixe ez fer de origeeneated cervixe oonly";

    //User information variables
    private $biz_owner_name, $biz_owner_email, $biz_name, $biz_region, $biz_campus, $biz_desc, $biz_cat_food, $biz_cat_drink, $biz_cat_groceries;

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
        if (isset($_POST["full-name"], $_POST["email"], $_POST["campus"], $_POST["biz-name"], $_POST["campus-add"], $_POST["biz-desc"])) {
            $this->biz_owner_name = $_POST["full-name"];
            $this->biz_owner_email = $_POST["email"];
            $this->biz_region = $_POST["campus"];
            $this->biz_name = $_POST["biz-name"];
            $this->biz_campus = $_POST["campus-add"];
            $this->biz_desc = $_POST["biz-desc"];
            if (isset($_POST["food"]) || isset($_POST["drink"]) || isset($_POST["groceries"])) {
            if (isset($_POST["food"])) {
                $this->biz_cat_food = $_POST["food"];
            }
            if (isset($_POST["drink"])) {
                $this->biz_cat_drink = $_POST["drink"];
            }
            if (isset($_POST["groceries"])) {
                $this->biz_cat_groceries = $_POST["groceries"];
            }
            return true;
        } else {
            $this->error_text = "You have to select at least one business category";
            return false;
        }
        } else {
            return false;
        }

        return false;
    }
    public function validate_text_input($text, $text_name, $length = 40)
    {
        if (isset($text)) {
            if (empty($text)) {
                $this->error_text = "Oops! You forgot to enter your $text_name";
                return false;
            } else if (preg_match("/[0-9~!@#$%^&*|\"<>?`=+\[\]{};'\/]/", $text)) {
                if ($text_name == "email") {
                    if (!filter_var($text, FILTER_VALIDATE_EMAIL)) {
                        echo "donce $text_name";
                        $this->error_text = "Oops! Sorry, your $text_name seems to be invalid";
                        return false;
                    }
                } else {
                    $this->error_text = "Oops! Sorry, your $text_name seems to be invalid";
                    return false;
                }
            } else if (strlen($text) > $length) {
                $this->error_text = "Oops! Sorry, your $text_name exceeds it's maximum acceptable number of characters";
                return false;
            }
            return htmlspecialchars($text);
        }
        return false;
    }
    public function validate_all_text_input()
    {
        $fail = false;
        $this->biz_owner_name = $this->validate_text_input($this->biz_owner_name, "name", 60);
        $this->biz_owner_email = $this->validate_text_input($this->biz_owner_email, "email", 60);
        $this->biz_name = $this->validate_text_input($this->biz_name, "business name", 30);
        $this->biz_region = $this->validate_text_input($this->biz_region, "campus region", 90);
        $this->biz_campus = $this->validate_text_input($this->biz_campus, "address", 100);
        $this->biz_desc = $this->validate_text_input($this->biz_desc, "business description", 400);

        if ($this->biz_owner_name && $this->biz_owner_email && $this->biz_owner_name && $this->biz_name && $this->biz_region && $this->biz_campus && $this->biz_desc) {
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
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="./stylers/business-enlist.css" />
    <title>Enlist your business</title>
</head>

<body>
    <?php
    $biz_processor = new process_biz_info();
    if ($biz_processor->check_if_info_set()) {
        if ($biz_processor->validate_all_text_input()) {
            echo true;
        } else {
            if($biz_processor->error_text != null) {
                echo "<div class=\"error\">" . $biz_processor->error_text . "</div><br>";
            }
        }
    } else {
        if($biz_processor->error_text != null) {
            echo "<div class=\"error\">" . $biz_processor->error_text . "</div><br>";
        }
    }
    ?>
    <div id="inner-body">
        <a href="http://mnd" class="mnd-logo"><img src="/images/ferixxon-logo-1.png" alt="ferixxon log" width="20%" /></a>
        <form method="POST" action="./business-enlist.php">
            <div id="form-panel">

                <div id="inner-form-division-1">
                    <h1>Help us grow your business by synergizing with us</h1>
                    <p>We believe this little bit of information we require will lead to a tremendously beneficiary relationship.</p>



                    <div id="full-name-bar" class="inputs-bar">
                        <label id="full-name" for="full-name" class="text">Hello there. Can you indulge us in knowing your full name?</label><br>
                        <input name="full-name" id="full-name" type="text" class="text" placeholder="John Smith" value="<?php if (isset($_POST["full-name"])) {
                                                                                                                            echo $_POST["full-name"];
                                                                                                                        } ?>" />
                    </div>
                    <div id="email-bar" class="inputs-bar">
                        <label id="email" for="email" class="text">We would like to know your e-mail for response?</label><br>
                        <input name="email" id="email" type="email" class="text" placeholder="johnsm@ferixxon.com" value="<?php if (isset($_POST["email"])) {
                                                                                                                                echo $_POST["email"];
                                                                                                                            } ?>" />
                    </div>
                    <div id="campus-bar" class="inputs-bar">
                        <label id="campus" for="campus" class="text">In what campus region does your business operate in?</label>
                        <select name="campus" id="campus" class="text">
                            <option>Adekunle Ajasin State University(AAUA), Akungba-Akoko, Ondo State</option>
                        </select>
                    </div>
                    <div id="-name-bar" class="inputs-bar">
                        <label id="biz-name" for="biz-name" class="text">We are sure you have an awesome business name. Tell us!</label><br>
                        <input name="biz-name" id="biz-name" type="text" class="text" placeholder="Ferixxon" value="<?php if (isset($_POST["biz-name"])) {
                                                                                                                        echo $_POST["biz-name"];
                                                                                                                    } ?>" />
                    </div>
                    <div id="campus-add-bar" class="inputs-bar">
                        <label id="campus-add" for="campus-add" class="text">Your business address within the selected campus region? </label><br>
                        <input name="campus-add" id="campus-add" type="address" class="text" value="<?php if (isset($_POST["campus-add"])) {
                                                                                                        echo $_POST["campus-add"];
                                                                                                    } ?>" />
                    </div>

                    <div id="biz-product-bar" class="inputs-bar">
                        <p id="biz-product" class="text">The product categories you specialize in?<span class="lab-extra-note">(All business products to be sold on this platform must definitely fall into any of these categories)</label>
                                <div class="sub-input-bar">
                                    <input id="food" name="food" type="checkbox" class="biz-products" />
                                    <label id="food" for="food" class="radio">Food</label>
                                </div>
                                <div class="sub-input-bar">
                                    <input id="drink" name="drink" type="checkbox" class="biz-products" />
                                    <label id="drink" for="drink" class="radio">Drink</label>
                                </div>
                                <div class="sub-input-bar">
                                    <input id="groceries" name="groceries" type="checkbox" class="bizproducts" />
                                    <label id="groceries" for="groceries" class="radio">Groceries</label>
                                </div>
                    </div>
                </div>
                <div id="inner-form-division-2">
                    <div id="biz-desc-bar" class="inputs-bar">
                        <label id="biz-desc" for="biz-desc" class="text">Any extra information you can tell us about your business? We are going to love it.</label>
                        <textarea name="biz-desc" id="biz-desc" placeholder="A little something about your business" maxlength="400"><?php if (isset($_POST["biz-desc"])) {
                                                                                                                                            echo $_POST["biz-desc"];
                                                                                                                                        } ?></textarea>
                    </div>
                    <button id="submit" type="submit">Submit Form</button>
                </div>
        </form>
    </div>
    <div id="round-ball-1" class="round-ball"></div>
    <div id="round-ball-2" class="round-ball"></div>
    <div id="round-ball-3" class="round-ball"></div>
    <div id="round-ball-4" class="round-ball"></div>
    </div>
</body>

</html>