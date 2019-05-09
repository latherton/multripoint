<?php

require_once '../includes/main_inc.php';
require_once 'includes/header.php';

if (($auth->check()) && ($auth->hasRole(\Delight\Auth\Role::ADMIN))){
	
	if (isset($_GET['user'])){
	
require 'useradmin/users.php';
	
}

else {
require 'includes/admin_index.php';
}


}
else {
		echo "<script> location.href='". $domainRoot ."/index.php' </script>";
    exit;
}
	require_once 'includes/footer.php';
?>

