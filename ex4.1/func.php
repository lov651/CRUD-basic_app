<?php

function flash() {

	if (isset($_SESSION['success'])) {
    	echo('<p style="color: green;">'.$_SESSION['success']."</p>\n");
    	unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
    	echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    	unset($_SESSION['error']);
    }

}

?>