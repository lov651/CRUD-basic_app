<?php
require_once "pdo.php";
require_once "func.php";
session_start();

if (!isset($_SESSION['name']) || strlen($_SESSION['name']) < 1 || !isset($_SESSION['user_id']) || strlen($_SESSION['user_id']) < 1) {
    die("Not logged in");
}

if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    } else if (strpos($_POST['email'], "@") === false) {
        $_SESSION['error'] = "Email address must contain @";
        header("Location: add.php");
        return;
    } else {
        $sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary)
                VALUES (:uid, :fn, :ln, :em, :he, :su)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary']));
        $_SESSION['success'] = 'Profile added';
        header( 'Location: index.php' );
        return;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Lovro Katić - Add page</title>
</head>
<body>
<div class="container">
<h1>Adding profile for user: <?=htmlentities($_SESSION['name']) ?></h1>
<?php
    flash();
?>
<form method="POST">
<p>First Name:
<input type="text" name="first_name" size="50"></p>
<p>Last Name:
<input type="text" name="last_name" size="50"></p>
<p>Email:
<input type="text" name="email" size="50"></p>
<p>Headline:
<input type="text" name="headline" size="80"></p>
<p>Summary:<br>
<textarea name="summary" rows="10" cols="100"></textarea>
</p>
<p>
<input type="submit" value="Add"/>
<input type="submit" name="cancel" value="Cancel"/></p>
</form>
</div>
</body>
</html>