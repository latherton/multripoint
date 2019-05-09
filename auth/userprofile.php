<?php

require_once '../includes/main_inc.php';
require_once '../includes/header.php';
\showGeneralForm();


if ($auth->check()) {
	\showAuthenticatedUserForm($auth);
}
else {
	\showGuestUserForm();
}

function showGeneralForm() {

}

function showGuestUserForm() {

	include 'login.html';
}
	include '../includes/footer.php';
?>