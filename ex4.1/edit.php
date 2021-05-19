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

if (!isset($_REQUEST['profile_id']) || strlen($_REQUEST['profile_id']) < 1) {
    die("No profile entry chosen");
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
        return;
    } else if (strpos($_POST['email'], "@") === false) {
        $_SESSION['error'] = "Email address must contain @";
        header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
        return;
    } else {
        $sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su
                WHERE profile_id=:pid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'],
            ':pid' => $_POST['profile_id']));
        $_SESSION['success'] = 'Profile updated';
        header( 'Location: index.php' );
        return;
    }
}

$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id=:pid");
$stmt->execute(array(":pid" => $_REQUEST['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Entry doesnt exist';
    header('Location: index.php') ;
    return;
}

if ($row['user_id'] !== $_SESSION['user_id']) {
    $_SESSION['error'] = "This entry doesn't belong to the currently logged user.";
    header('Location: index.php') ;
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Lovro Katić - Edit page</title>
</head>
<body>
<div class="container">
<h1>Editing profile for user: <?=htmlentities($_SESSION['name']) ?></h1>
<?php
    flash();
?>
<form method="POST">
<p>First Name:
<input type="text" name="first_name"  value="<?=htmlentities($row['first_name'])?>" size="50"></p>
<p>Last Name:
<input type="text" name="last_name" value="<?=htmlentities($row['last_name'])?>" size="50"></p>
<p>Email:
<input type="text" name="email" value="<?=htmlentities($row['email'])?>" size="50"></p>
<p>Headline:
<input type="text" name="headline" value="<?=htmlentities($row['headline'])?>" size="80"></p>
<p>Summary:<br>
<textarea name="summary" rows="10" cols="100"><?=htmlentities($row['summary'])?></textarea>
</p>
<p>
<input type="hidden" name="profile_id" value="<?=$_REQUEST['profile_id']?>">
<input type="submit" value="Edit"/>
<input type="submit" name="cancel" value="Cancel"/>
</p>
</form>
</div>
</body>
</html>