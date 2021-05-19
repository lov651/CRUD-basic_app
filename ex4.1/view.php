<?php
require_once "pdo.php";
session_start();

if (!isset($_REQUEST['profile_id']) || strlen($_REQUEST['profile_id']) < 1) {
    die("No profile entry chosen");
}

$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id=:pid");
$stmt->execute(array(":pid" => $_REQUEST['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Entry doesnt exist';
    header('Location: index.php') ;
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Lovro Katić - View page</title>
</head>
<body>
<div class="container">
<h1>Profile information</h1>
<p>First Name: <?=htmlentities($row['first_name'])?></p>
<p>Last Name: <?=htmlentities($row['last_name'])?></p>
<p>Email: <?=htmlentities($row['email'])?></p>
<p>Headline: <?=htmlentities($row['headline'])?></p>
<p>Summary:<br> <?=htmlentities($row['summary'])?></p>
<form method="POST">
<input type="hidden" name="profile_id" value="<?=$_REQUEST['profile_id']?>">
</form>
<p><a href="index.php">Done</a></p>
</div>
</body>
</html>