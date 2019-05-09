<?php

require_once 'includes/main_inc.php';
require_once 'includes/header.php';
\showGeneralForm();


if ($auth->check()) {
	
	echo "<script> location.href='user/index.php' </script>";
        exit;
	
	include 'user/index.php';
}
else {
	\showGuestUserForm();
}

function showGeneralForm() {

}

function showGuestUserForm() {

	include 'auth/login.html';
}
	include 'includes/footer.php';
?>