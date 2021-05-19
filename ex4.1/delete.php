<?php
require_once "pdo.php";
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

if (isset($_POST['delete'])) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profile deleted';
    header( 'Location: index.php' ) ;
    return;
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
<title>Lovro Katić - Delete page</title>
</head>
<body>
<div class="container">
<h1>Deleting a profile</h1>

<p>First Name: <?=htmlentities($row['first_name'])?></p>
<p>Last Name: <?=htmlentities($row['last_name'])?></p>

<form method="post">
<input type="hidden" name="profile_id" value="<?= $_REQUEST['profile_id'] ?>">
<input type="submit" value="Delete" name="delete">
<input type="submit" value="Cancel" name="cancel">
</form>
</div>
</body>
</html>