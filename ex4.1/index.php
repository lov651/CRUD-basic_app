<?php
	require_once "pdo.php";
    require_once "func.php";
	session_start();
	$stmt = $pdo->query("SELECT * FROM profile");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<title>Lovro Katić - Resume Database</title>
<?php require_once "bootstrap.php"; ?>	
</head>
<body>
<div class="container">
<h1>Welcome to Resumes Database</h1>
<?php
    flash();
	if (!isset($_SESSION['name'])) {
		echo '<p><a href="'.'login.php'.'">Log in</a></p>'."\n";
	}
	if (count($rows)) {
		echo('<table border="1">'."\n");
		echo "<tr><td>Name</td><td>Headline</td><td>Action</td></tr>\n";
		foreach ($rows as $row) {
            echo "<tr><td>";
            echo(htmlentities($row['first_name'])." ".htmlentities($row['last_name']));
            echo("</td><td>");
            echo(htmlentities($row['headline']));
            echo "</td><td>";
            echo('<a href="view.php?profile_id='.$row['profile_id'].'">View</a>');
            if (!isset($_SESSION['name']) || ($_SESSION['user_id'] !== $row['user_id'])) {
                echo "</td></tr>\n";
            } else {
                echo(' / <a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
                echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
                echo("</td></tr>\n");
            }
    	}
    	echo("</table>\n");
    } else {
    	echo "<p>No rows found</p>\n";
    }
    if (isset($_SESSION['name'])) {
        echo '<p><a href="'.'add.php'.'">Add New Entry</a> | <a href="'.'logout.php'.'">Logout</a></p>'."\n";
    }
?>
</div>
</body>
</html>