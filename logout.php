<?php
session_name("user");
session_start();
session_destroy();

header("Location: https://www.ferixxon.com/", true, "302");
?>