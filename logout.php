<?php
session_name("user");
session_start();
session_destroy();

header("Location: http://mnd/", true, "302");
?>