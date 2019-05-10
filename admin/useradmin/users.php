<?php

if ($auth->check()) {

	echo '

	<div id="wrapper">
  <!-- Navigation -->
  <div id="page-wrapper">
    <div class="container-flex">
      <!-- Page Heading -->

        <div class="row">
          <div class="col-12 col-xs-12">
            <div class="col-2 col-xs-2">
                <div class="card">
                  <div class="card-body">';
				require 'usernav.php';
				echo '
				  </div>
              </div>
            </div>
            <div class="col-10 col-xs-10">';

			if (isset($_GET['action'])){
				if ($_GET['action'] =='adduser'){
					include 'useradmin/addusers.php';
				}
			}




            echo '</div>
            </div>

      </div>

    </div>

  </div>
</div>
</div>';











}
else {
		echo "<script> location.href='". $domainRoot ."/index.php' </script>";
    exit;
}



if ($auth->hasRole(\Delight\Auth\Role::ADMIN)) {

}

else {
		echo "<script> location.href='". $domainRoot ."/index.php' </script>";
    exit;
}

?>
