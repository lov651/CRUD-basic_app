<?php
session_start();
require_once "pdo.php";
require_once "func.php";

if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';

if (isset($_POST['email']) && isset($_POST['pass'])) {
    unset($_SESSION['name']);
    unset($_SESSION['user_id']);
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
            WHERE email = :em AND password = :pw');
        $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) {
            $_SESSION['name'] = htmlentities($row['name']);
            //error_log("Login success ".$_POST['email']);
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: index.php");
            return;
        } else {
            //error_log("Login fail ".$_POST['email']." $check");
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Lovro Katić - Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
    flash();
?>
<form method="POST">
<label for="em">Email</label>
<input type="text" name="email" id="em"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name='cancel' value="Cancel">
</form>
<script type="text/javascript">
function doValidate() {
    console.log('Validating...');
    try {
        em = document.getElementById('em').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating pw="+pw+" and email="+em);
        if (pw == null || pw == "" || em == null || em == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if (!em.includes("@")) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}   
</script>
</div>
</body>
</html>
