<?php
class account_details_updater
{
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "youxerze",
        SQL_USERNAME = "phantom",
        SQL_PASSWORD = "thix cervixe ez fer de origeeneated cervixe oonly";

    private const MIN_DATE_TO_UPDATE = 10;

    public $user_id = null;
    private $pdo_for_user_details_retriever;
    private $pdo_for_user_details_retriever_sql;

    public $name, $surname, $username, $email, $tel, $address, $catch_phrase;

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

            $can_user_update = $this->check_if_can_update($this->user_id);
            if ($can_user_update === true) {
                echo $this->update_all_user_main_information($this->user_id, null, null, $_POST["username"], $_POST["email"], $_POST["tel"], $_POST["address"], $_POST["catch_phrase"]);
            } else if ($can_user_update !== true) {
                echo $can_user_update;
            }
        } else {
            echo json_encode(["error_type" => "NO_LOG", "msg" => "You are not logged in, Please Log in or Sign Up to use this service. Or the manner in which this script is interacted with is unacceptable"]);
        }
    }
    //Checks if the minimum number of date before user details can be updated has elapsed, if false return days to elapse before an update can be made;
    function check_if_can_update($user_id)
    {
        if ($user_id) {
            $this->pdo_for_user_details_retriever_sql = $this->pdo_for_user_details_retriever->prepare("SELECT THAETE_LAESTE_OPTHAETED FROM `youxerze_baexxic_enfor` WHERE EYEDE = :id LIMIT 1");

            $this->pdo_for_user_details_retriever_sql->bindValue(":id", $user_id);

            $this->pdo_for_user_details_retriever_sql->execute();

            $date_last_updated = $this->pdo_for_user_details_retriever_sql->fetch(PDO::FETCH_ASSOC)["THAETE_LAESTE_OPTHAETED"];

            $date_last_updated = (new DateTime($date_last_updated))->getTimestamp();

            $date_last_updated = $_SERVER["REQUEST_TIME"] - $date_last_updated;

            $date_last_updated = $date_last_updated / 60;

            $date_last_updated = $date_last_updated / 60;

            $date_last_updated = round($date_last_updated / 24, 2);

            $day_to_next_update = account_details_updater::MIN_DATE_TO_UPDATE - $date_last_updated;

            $this->set_all_user_information($this->user_id);

            if ($date_last_updated >= account_details_updater::MIN_DATE_TO_UPDATE) {
                return true;
            } else {
                $day_to_next_update_minutes = ($day_to_next_update - floor($day_to_next_update));

                $days_to_next_update = floor($day_to_next_update) . " Days and " . round($day_to_next_update_minutes * 24) . " Hours";
                return json_encode(["error_type" => "TO_SOON", "time_to_wait" => "$days_to_next_update"]);
                
            }
        }
    }
    function validate_mail($e_mail)
    {
        if (filter_var($e_mail, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }
    function validate_telephone_number($tel_to_test)
    {
        return self::validate_nigeria_telephone_number($tel_to_test);
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
                return "234".substr($tel_to_test, 1);
            }
        } else if (strlen($tel_to_test) == 10) {
            if (preg_match($nigeria_tel_regex, $tel_to_test)) {
                return "234".$tel_to_test;
            }
        } else if (strlen($tel_to_test) == 14) {
            if (substr($tel_to_test, 0, 4) == "+234" && preg_match($nigeria_tel_regex, substr($tel_to_test, 4))) {
                return substr($tel_to_test, 1);
                
            }
        } else if (strlen($tel_to_test) == 13) {
            if (substr($tel_to_test, 0, 3) == "234" && preg_match($nigeria_tel_regex, substr($tel_to_test, 3))) {
                return $tel_to_test;
            }
        }
        return false;
    }
    //Updates users information accordingly 
    function update_all_user_main_information($user_id, $name, $surname, $username, $email, $tel, $address, $catch_phrase)
    {
        $has_something_changed = false;
        try {
            $this->address = json_decode($this->address);
            $address = json_decode($address);
            $name && $name !== $this->name ? $has_something_changed = true : $name = $this->name;
            $surname && $surname !== $this->surname ? $has_something_changed = true : $surname = $this->surname;
            if ($username && $username !== $this->username) {
                if(!$this->check_if_username_is_invalid_or_used($username, $user_id)) {
                    $has_something_changed = true;
                }
                else {
                    return json_encode(["error_type" => "UNAME_INVALID", "msg" => "You can't use this username because it is either invalid(Make sure the first character is an English Letter, subsequent can either be made of English letters or numbers) or used already"]);
                }
            } 
            if ($email && $email !== $this->email) {
                if (!$this->check_if_mail_is_invalid_or_used($email, $user_id)) {
                    $has_something_changed = true;
                } else {
                    return json_encode(["error_type" => "MAIL_INVALID", "msg" => "You can't use this mail because it is either invalid or used already"]);
                }
            }
            if ($tel && $tel != $this->tel) {
                if (!$this->check_if_tel_is_invalid_or_used($tel, $user_id)) {
                    $has_something_changed = true;
                } else {
                    return json_encode(["error_type" => "TEL_INVALID", "msg" => "You can't use this telephone number because it is either invalid or used already"]);
                }
            }
            if ($address && $address !== $this->address) {
                if($this->check_if_address_ok($address)) {
                    $has_something_changed = true;
                }
                else{
                    return json_encode(["error_type" => "ADDR_INVALID", "msg" => "There seems to be something off about your address. The acceptable characters are [A-Z0-9S,SPACE], please rectify or refresh webpage, then resend"]);
                }
            }
            $catch_phrase && strcmp($catch_phrase, $this->catchphrase) ? $has_something_changed = true : $catch_phrase = $this->catchphrase;
            if ($has_something_changed) {
                $sql_query = "UPDATE `youxerze_baexxic_enfor` SET FEASTE_NAEME = :name, LAESTE_NAEME = :last_name, USEARNAEME = :username, EE_MEOWL = :email, FEONE_NEIMBA = :tel, AEDREASSE = :address, KAETCHPHRAEXZE = :catchphrase, THAETE_LAESTE_OPTHAETED = NOW() WHERE eyede = :id";

                $this->pdo_for_user_details_retriever_sql = $this->pdo_for_user_details_retriever->prepare($sql_query);
                $this->pdo_for_user_details_retriever_sql->bindValue(":name", $name);
                $this->pdo_for_user_details_retriever_sql->bindValue(":last_name", $surname);
                $this->pdo_for_user_details_retriever_sql->bindValue(":username", $username);
                $this->pdo_for_user_details_retriever_sql->bindValue(":email", $email);
                $this->pdo_for_user_details_retriever_sql->bindValue(":tel", $tel);
                $this->pdo_for_user_details_retriever_sql->bindValue(":address", json_encode($address));
                $this->pdo_for_user_details_retriever_sql->bindValue(":catchphrase", $catch_phrase);
                $this->pdo_for_user_details_retriever_sql->bindValue(":id", $user_id);

                if($this->pdo_for_user_details_retriever_sql->execute()) {
                    return json_encode(["error_type" => "NO_ERR", "msg" => "Your details have been successfully updated"]);
                }
            } else {
                return json_encode(["error_type" => "NO_CHGS", "msg" => "You did not make any changes on your account to save"]);
            }
        } catch (Error) {
            return json_encode(["error_type" => "UNKNOWN", "msg" => "Some unknown error occured, try again some time. If it still persists contact the administrator"]);
        }
    }
    function check_if_mail_is_invalid_or_used($email, $user_id)
    {
        if ($this->validate_mail($email)) {
            $this->pdo_for_user_details_retriever_sql = $this->pdo_for_user_details_retriever->prepare("SELECT EE_MEOWL FROM `youxerze_baexxic_enfor` WHERE  EE_MEOWL = :email AND NOT EYEDE = :id LIMIT 1");

            $this->pdo_for_user_details_retriever_sql->bindValue(":email", $email);

            $this->pdo_for_user_details_retriever_sql->bindValue(":id", $user_id);

            $this->pdo_for_user_details_retriever_sql->execute();

            $requested_details = $this->pdo_for_user_details_retriever_sql->fetch(PDO::FETCH_ASSOC);

            if ($requested_details["EE_MEOWL"]) {
                return true;
            }
            return false;
        }
        return true;
    }
    function check_if_tel_is_invalid_or_used($tel, $user_id)
    {
        $tel = $this->validate_telephone_number($tel);
        if ($tel !== false) {
            $this->pdo_for_user_details_retriever_sql = $this->pdo_for_user_details_retriever->prepare("SELECT FEONE_NEIMBA FROM `youxerze_baexxic_enfor` WHERE  FEONE_NEIMBA LIKE :tel AND NOT EYEDE = :id LIMIT 1");

            $this->pdo_for_user_details_retriever_sql->bindValue(":tel", "%".$tel);

            $this->pdo_for_user_details_retriever_sql->bindValue(":id", $user_id);

            $this->pdo_for_user_details_retriever_sql->execute();

            $requested_details = $this->pdo_for_user_details_retriever_sql->fetch(PDO::FETCH_ASSOC);

            if ($requested_details) {
                return true;
            }
            return false;
        }
        return true;
    }
    function check_if_username_is_invalid_or_used($uname, $user_id)
    {
        $uname_valid = preg_match("/^[A-Za-z]+[A-Za-z0-9*]*$/", $uname);
        if ($uname_valid) {
            $this->pdo_for_user_details_retriever_sql = $this->pdo_for_user_details_retriever->prepare("SELECT USEARNAEME FROM `youxerze_baexxic_enfor` WHERE USEARNAEME = :uname AND NOT EYEDE = :id LIMIT 1");

            $this->pdo_for_user_details_retriever_sql->bindValue(":uname", $uname);

            $this->pdo_for_user_details_retriever_sql->bindValue(":id", $user_id);

            $this->pdo_for_user_details_retriever_sql->execute();

            $requested_details = $this->pdo_for_user_details_retriever_sql->fetch(PDO::FETCH_ASSOC);

            if ($requested_details) {
                return true;
            }
            return false;
        }
        return true;
    }
    function check_if_address_ok($address) {
        if(is_array($address)) {
            if(count($address) <= 3) {
                foreach($address as $el) {
                    $el = trim($el);
                    if(strlen($el) >= 6) {
                        if(!preg_match("/^[A-Za-z]+[A-Za-z0-9 ,]*$/", $el)) {
                            return false;
                        } 
                    }
                    else {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }
    function set_all_user_information($user_id)
    {
        if (!empty($user_id)) {
            $this->pdo_for_user_details_retriever_sql = $this->pdo_for_user_details_retriever->prepare("SELECT FEASTE_NAEME, LAESTE_NAEME, USEARNAEME,  EE_MEOWL, FEONE_NEIMBA, AEDREASSE, KAETCHPHRAEXZE FROM `youxerze_baexxic_enfor` WHERE EYEDE = :id LIMIT 1");

            $this->pdo_for_user_details_retriever_sql->bindValue(":id", $user_id);

            $this->pdo_for_user_details_retriever_sql->execute();

            $requested_details = $this->pdo_for_user_details_retriever_sql->fetch(PDO::FETCH_ASSOC);

            $this->name = $requested_details["FEASTE_NAEME"];
            $this->surname = $requested_details["LAESTE_NAEME"];
            $this->username = $requested_details["USEARNAEME"];
            $this->email = $requested_details["EE_MEOWL"];
            $this->tel = $requested_details["FEONE_NEIMBA"];
            $this->address = $requested_details["AEDREASSE"];
            $this->catchphrase = $requested_details["KAETCHPHRAEXZE"];
        }
    }
}
$user_account = new account_details_updater();
