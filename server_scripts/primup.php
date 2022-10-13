<?php

class image_processor
{
    //Database login credentials
    private
    const HOST_NAME = "localhost",
        DATABASE_NAME = "youxerze",
        SQL_USERNAME = "phantom",
        SQL_PASSWORD = "thix cervixe ez fer de origeeneated cervixe oonly";

    //File/Image basic information stored in variables
    private $image_file_location, $image_name, $image_mime, $image_size;

    //User Id
    private $user_id;

    //File/Image Details PDO Handler;
    private $file_pdo;


    private const MIN_DATE_TO_UPDATE = 4;

    //Image security/validate rules, the image height and width are in pixels, the sizes are in bytes
    const IMAGE_MIN_WIDTH = 100, IMAGE_MIN_HEIGHT = 100, IMAGE_MAX_WIDTH = 400, IMAGE_MAX_HEIGHT = 400, IMAGE_MIN_SIZE = 1000, IMAGE_MAX_SIZE = 400000;

    //The g stands for guaranteed, the following information are guaranteed or information derived securely about the file/image
    private $g_image_name, $g_image_mime, $g_image_size, $g_image_bit_depth, $g_image_channels, $g_image_width, $g_image_height, $image_url;

    function __construct($image_file)
    {
        session_name("user");
        session_start();

        if (isset($_SESSION['UNQ_ID'])) {
            $this->user_id = $_SESSION['UNQ_ID'];
        } else {
            echo json_encode(["error_type" => "NO_LOG", "msg" => "You are not supposed to be interacting with this script in this manner"]);
            exit(0);
        }
        //Set the File basic information to the image file sent
        $this->image_file_location = $image_file["tmp_name"];
        $this->image_name = $image_file["name"];
        $this->image_mime = $image_file["type"];
        $this->image_size = $image_file["size"];
        $this->file_pdo = new PDO("mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD);
        $time_to_wait = $this->check_if_can_update($this->user_id);
        //Check if time to wait before reupload has elapsed
        if ($time_to_wait === true) {
            //If the file/image error state is equal to 0(0:No error, !0: Error occured)
            if ($image_file["error"] === 0 && !empty($this->image_name) && !empty($this->image_file_location)) {
                if ($this->validate_image($this->image_file_location, ["image/jpeg", "image/png", "image/jpg"])) {
                    $url_dir = $this->check_dir_exists_create("../user_images/$this->user_id/");
                    $this->compress_image($this->image_file_location, $this->g_image_mime, $url_dir);
                    $this->upload_user_image_details($this->user_id, $url_dir);
                }
            } else {
                echo json_encode(["error_type" => "UPL_UNK", "msg" => "An unknown error occured while uploading this file, please try again. If error persists, check your internet connection, try another image or re log-in else contact administrator"]);
                exit(0);
            }
        }
        else {
            echo $time_to_wait;
        }
    }
    function check_if_can_update($user_id)
    {
        if ($user_id) {
            $pdo_for_user_details_retriever_sql = $this->file_pdo->prepare("SELECT DAETE_OPELEAODEAHDE FROM `youxerze_preafeale_eamaegezz` WHERE USEARE_EYE_DE = :id LIMIT 1");

            $pdo_for_user_details_retriever_sql->bindValue(":id", $user_id);

            $pdo_for_user_details_retriever_sql->execute();

            $date_last_updated = $pdo_for_user_details_retriever_sql->fetch(PDO::FETCH_ASSOC)["DAETE_OPELEAODEAHDE"];

            $date_last_updated = (new DateTime($date_last_updated))->getTimestamp();

            $date_last_updated = $_SERVER["REQUEST_TIME"] - $date_last_updated;

            $date_last_updated = $date_last_updated / 60;

            $date_last_updated = $date_last_updated / 60;

            $date_last_updated = round($date_last_updated / 24, 2);

            $day_to_next_update = image_processor::MIN_DATE_TO_UPDATE - $date_last_updated;

            if ($date_last_updated >= image_processor::MIN_DATE_TO_UPDATE) {
                return true;
            } else {
                $day_to_next_update_minutes = ($day_to_next_update - floor($day_to_next_update));

                $days_to_next_update = floor($day_to_next_update) . " Days and " . round($day_to_next_update_minutes * 24) . " Hours";
                return json_encode(["error_type" => "TO_SOON", "msg" => "You have to wait $days_to_next_update before you can re-update your profile image"]);
            }
        }
    }
    //Function checks if image is secure and information conforms to established value and rectify security issues if possible and where need be
    public function validate_image($image_loc, array $acceptable_image_mimes = ["image/jpeg", "image/png", "image/jpg"], array $acceptable_image_extentions = ["jpeg", "png", "jpg"])
    {
        //If file/image is not empty and the accaeptable_image_mimes argument is an actual array
        if ($image_loc && is_array($acceptable_image_mimes)) {
            //Measure file/image actual mime by opening file stream, actually check and closing file stream;
            $opened_file_open_stream = finfo_open(FILEINFO_MIME_TYPE);
            $file_mime = finfo_file($opened_file_open_stream, $image_loc);
            finfo_close($opened_file_open_stream);

            //Get image information like width, height, channel and bitsize on the file/image
            $image_basic_infos = getimagesize($image_loc);
            //Check if the file/image mime is in the acceptable file/image mimes
            if ($file_mime == $image_basic_infos["mime"] && in_array($file_mime, $acceptable_image_mimes)) {
                $this->g_image_mime = $file_mime;

                //Check if the file/image dimensions(width, height) constrains to the setted acceptable const dimensions;
                if ($image_basic_infos[0] >= image_processor::IMAGE_MIN_WIDTH && $image_basic_infos[0] <= image_processor::IMAGE_MAX_WIDTH && $image_basic_infos[1] >= image_processor::IMAGE_MIN_HEIGHT && $image_basic_infos[1] <= image_processor::IMAGE_MAX_HEIGHT) {
                    $this->g_image_width = $image_basic_infos[0];
                    $this->g_image_height = $image_basic_infos[1];
                    $this->g_image_bit_depth = $image_basic_infos["bits"];
                    $this->g_image_channels = $image_basic_infos["channels"];
                    $this->g_image_size = filesize($image_loc);

                    //Check if the file/image is not lesser or greater than the setted acceptable constraints
                    if ($this->g_image_size >= image_processor::IMAGE_MIN_SIZE && $this->g_image_size <= image_processor::IMAGE_MAX_SIZE) {
                        $this->g_image_name = substr(preg_replace("/[^A-Za-z0-9(){}\[\]_\-]+/", "", $this->image_name), 0, 150);
                        return true;
                    } else {
                        echo json_encode(["error_type" => "ERR_SIZE_F", "msg" => "The minimum file size is 1 kilobyte; maximum file size is 100 kilobytes"]);
                        exit(0);
                    }
                } else {
                    echo json_encode(["error_type" => "ERR_DIM_F", "msg" => "This image dimensions are off. The image dimensions must maintain a 1/1 aspect ratio, the width and height can't be lesser than 100px and greater than 400px"]);
                    exit(0);
                }
            } else {
                echo json_encode(["error_type" => "ERR_MIME_F", "msg" => "The format of the file you are trying to upload is unsupported. The acceptable files are: JPEG, JPG and PNG;"]);
                exit(0);
            }
        } else {
            echo json_encode(["error_type" => "ERR_SRV", "msg" => "An unknown error occured on the server, please try again, if error persists, please report to administrator."]);
            exit(0);
        }
    }
    function check_dir_exists_create($url_dir)
    {
        if (!file_exists($url_dir)) {
            mkdir($url_dir, 0666, true);
            return $url_dir;
        }
        return $url_dir;
    }
    //Compress image and return final result
    public function compress_image($image, $image_mime, $url_dir)
    {
        function check_file_exists_adjust($url_dir, $file_name, $file_extension)
        {
            //Checks if the file_name does not match any in the directory
            if (!file_exists($url_dir . $file_name . $file_extension)) {
                return $file_name;
            }
            $i = 0;
            //Consequently check and adjust file name index till a match is not found
            while (true) {
                if (!file_exists($url_dir . $file_name . "($i)$file_extension")) {
                    return $file_name . "($i)";
                }
                $i++;
            }
        }
        try {
            if ($image_mime == "image/jpeg" || $image_mime == "image/jpg") {
                if (!$jpeg_image_object = imagecreatefromjpeg($image)) {
                    throw new Error("Can't create image from jpeg");
                }
                $dir_file_to_use = $url_dir . check_file_exists_adjust($url_dir, $this->g_image_name, ".jpg") . ".jpg";
                $this->g_image_name .= ".jpg";
                $this->image_url = $dir_file_to_use;
                imagejpeg($jpeg_image_object, $dir_file_to_use);
            } else if ($image_mime == "image/png") {
                if (!$png_image_object = imagecreatefrompng($image)) {
                    throw new Error("Can't create image from png");
                }
                $dir_file_to_use = $url_dir . check_file_exists_adjust($url_dir, $this->g_image_name, ".png") . ".png";
                $this->g_image_name .= ".png";
                $this->image_url = $dir_file_to_use;
                imagealphablending($png_image_object, false);
                imagesavealpha($png_image_object, true);
                imagepng($png_image_object, $dir_file_to_use);
            } else {
                echo json_encode(["error_type" => "ERR_MIME_F", "msg" => "The format of the file you are trying to upload is unsupported. The acceptable files are: JPEG, JPG and PNG;"]);
                exit(0);
            }
        } catch (Error $error) {
            echo json_encode(["error_type" => "ERR_IMG_PROC", "msg" => "An unknown error has occured while working on this file, please try again."]);
            exit(0);
        }
    }
    public function mod_old_user_images_status($user_id, $image_old_status = 0)
    {
        $file_pdo_query = $this->file_pdo->prepare("UPDATE `youxerze_preafeale_eamaegezz` SET EAMEAGE_STEATIUSE = :image_status WHERE USEARE_EYE_DE = :u_id");

        $file_pdo_query->bindValue(":u_id", $user_id);
        $file_pdo_query->bindValue(":image_status", $image_old_status);

        if ($file_pdo_query->execute()) {
            return true;
        }
    }
    public function upload_user_image_details($user_id, $url_dir, $image_new_status = 1, $image_old_status = 0)
    {
        if ($this->mod_old_user_images_status($user_id)) {

            $file_pdo_query = $this->file_pdo->prepare("INSERT INTO `youxerze_preafeale_eamaegezz`(`USEARE_EYE_DE`, `OHREAGEANAELE_EAMAEGE_NAEME`, `OHREAGAENAELE_EAMEAGE_MEAME`, `OHREAGAENAELE_EAMEAGE_SIEZE`, `EAMAEGE_NAEME`, `EAMEAGE_MAEME`, `EAMEAGE_SIEZE`, `EAMAEGE_BEETHE_THEPTHE`, `EAMEAGE_CHEANEALE`, `EAMEAGE_WEADITH`, `EAMEAGE_HYEHTE`, `EAMEAGE_YOUEREL`, `EAMEAGE_STEATIUSE`, `DAETE_OPELEAODEAHDE`) VALUES (:u_id, :image_name, :image_mime, :image_size, :g_image_name, :g_image_mime, :g_image_size, :image_bit_depth, :image_channel, :image_width, :image_height, :image_url, :image_status, NOW())");

            $file_pdo_query->bindValue(":u_id", $user_id);
            $file_pdo_query->bindValue(":image_name", $this->image_name);
            $file_pdo_query->bindValue(":image_mime", $this->image_mime);
            $file_pdo_query->bindValue(":image_size", $this->image_size);
            $file_pdo_query->bindValue(":g_image_name", $this->g_image_name);
            $file_pdo_query->bindValue(":g_image_mime", $this->g_image_mime);
            $file_pdo_query->bindValue(":g_image_size", $this->g_image_size);
            $file_pdo_query->bindValue(":image_bit_depth", $this->g_image_bit_depth);
            $file_pdo_query->bindValue(":image_channel", $this->g_image_channels);
            $file_pdo_query->bindValue(":image_width", $this->g_image_width);
            $file_pdo_query->bindValue(":image_height", $this->g_image_height);
            $file_pdo_query->bindValue(":image_url", $this->image_url);
            $file_pdo_query->bindValue(":image_status", $image_new_status);

            if ($file_pdo_query->execute()) {
                echo json_encode(["error_type" => "NO_ERR_IMG_UP_SUCC", "msg" => "Your profile image has been updated successfully", "img_url" => "$this->image_url"]);
                exit(0);
            } else {
                echo json_encode(["error_type" => "IMG_UP_ERR", "msg" => "Your profile image can't be updated for some strange reason, if you keep this message please contact the administrator"]);
                exit(0);
            }
        } else {
            echo json_encode(["error_type" => "NO_ERR_IMG_UP_SUCC", "msg" => "Your old profile image(s) cannot be rectified, if you keep getting this error please contact the administrator"]);
            exit(0);
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $img_processor = new image_processor($_FILES["C_IMAGE"]);
}
