<?php
if (isset($_POST["username"], $_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (!empty($username) && !empty($password)) {
        $pdo_for_emp_authentication = new PDO("mysql:host=localhost;dbname=youxerze", "phantom", "tKD5KZ4K9\$M@");
        $pdo_for_emp_authentication_sql = $pdo_for_emp_authentication->prepare("SELECT EYE_DE, NAEME FROM `emps` WHERE USAERENAEM = :u_name AND PISSWARDE = :pass COLLATE Latin1_General_CS_AS LIMIT 1");

        $pdo_for_emp_authentication_sql->bindValue(":u_name", $username);
        $pdo_for_emp_authentication_sql->bindValue(":pass", $password);

        $pdo_for_emp_authentication_sql->execute();

        $detail = $pdo_for_emp_authentication_sql->fetch(PDO::FETCH_ASSOC);
        if ($detail) {
            session_name("emps");
            session_start();

            $_SESSION["EMP_ID"] = $detail["EYE_DE"];
            $_SESSION["EMP_NAME"] = $detail["NAEME"];

            header("Location: https://www.ferixxon.com/works/orders_ground", true, "301");
        }
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Works Login</title>
</head>
<body>
<style>
    body {
        background: rgb(250, 250, 250);
    }
    #id {
        color: #555;
        text-align: center;
        opacity: 0.1;
        font-size: 45px;
        letter-spacing: 10px;
    }
    #login-form {
        width: 60%;
        background: #fff;
        margin: 10% auto 0;
        box-shadow: 2px 4px 6px rgb(190, 190, 190);
        padding: 16px;
    }
    #login-form h3{
        color: #ff5a34;
        margin-bottom: 4%;
    }
    #id-submit-form {
        width: 20%;
        margin-left: 75%;
        margin-top: 3%;
        color: white;
        padding: 4px;
        border: none;
        outline: none;
        background: #ffb618;
        cursor: pointer;
    }
    .input-division {
        margin-top: 3%;
    }
    .form-inputs {
        width: 70%;
        padding: 3px;
        border: none;
        outline: none;
        border-bottom: 1px solid #ffb618;
    }
</style>
<h1 id="id">FERIXXON</h1>
<form id="login-form" action="works_sign_in" method="post">
        <h3>Sign In</h3>
        <div class="input-division">
            <label class="form-labels" for="id-username">Username: </label>
            <input class="form-inputs" id="id-username" name="username" type="text" placeholder="Username" required />
        </div>
        <div class="input-division">
            <label class="form-labels" for="id-password">Password: </label>
            <input class="form-inputs" id="id-password" name="password" type="password" placeholder="Password" required />
        </div>
        <div class="input-division">
        <input type="submit" id="id-submit-form" value="Login" />
        </div>
    </form>
</body>
</html>