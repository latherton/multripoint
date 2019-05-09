<?php

require_once '../includes/main_inc.php';
require_once '../includes/header.php';

if ($auth->check()) {


}
else {
		echo "<script> location.href='". $domainRoot ."/index.php' </script>";
    exit;
}

function showGeneralForm() {

}

	require_once '../includes/footer.php';
?>

<div id="wrapper">
  <!-- Navigation -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <!-- Page Heading -->
      <div class="row">
          <div class="col-lg-12">
              <h1>Multrio</h1>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-lg-12">
            <div class="col-4 col-xs-4">
              <div class="row">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">no ass Panel</h3>
                  </div>
                  <div class="panel-body"> Dis be it </div>
                  <div class="panel-footer">Ty BOy</div>
                </div>
      				</div>
      			</div>
            <div class="col-4 col-xs-4">

                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Bad Ass Panel</h3>
                  </div>
                  <div class="panel-body"> Dis be it </div>
                  <div class="panel-footer">Ty BOy</div>

              </div>
            </div>
            <div class="col-4 col-xs-4">

                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Bad Ass Panel</h3>
                  </div>
                  <div class="panel-body"> Dis be it </div>
                  <div class="panel-footer">Ty BOy</div>

              </div>
            </div>

      </div>

    </div>

  </div>
</div>
</div>
