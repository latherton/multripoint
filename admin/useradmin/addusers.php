<?php
if (isset($_POST)) {
  if (isset($_POST['action'])) {
    if ($_POST['action'] === 'register'){
      if (is_numeric($result)) {
        if (isset($_POST['firstname'])) {
          $query = $db->prepare("update users set firstname='" . $_POST['firstname'] ."' where id=".$result);
          $query->execute();
        }
        if (isset($_POST['lastname'])) {
          $query = $db->prepare("update users set lastname='" . $_POST['lastname'] ."' where id=".$result);
          $query->execute();
        }
        if (isset($_POST['companyname'])) {
          $query = $db->prepare("update users set companyname='" . $_POST['companyname'] ."' where id=".$result);
          $query->execute();
        }
        echo '<div class="alert alert-success alert-dismissible">
      <strong>Success!</strong> This user has been created.
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>';

      }
        else{
          echo '<div class="alert alert-danger alert-dismissible">
        <strong>Error!</strong> '. $result .'
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>';


          echo $result;
        }
    }

  }
}


 ?>


                <div class="panel">
                  <div class="panel-heading">
				  <h2> Add New User </h2>
                  </div>
                  <div class="panel-body">
					<form action="" method="post" accept-charset="utf-8">
						<input type="hidden" name="action" value="register" />

						<input type="hidden" name="username" placeholder="Username (optional)" />
						<input type="hidden" name="require_verification" placeholder="" value="0" />
						<input type="Hidden" name="require_unique_username" value="0">
            <div class="col-6">
<div class="form-group">
  <label for="email">Email Address:</label>
            <input class="form-control" type="text" name="email" placeholder="Email address" />
</div>
<div class="form-group">
            <label for="password">Password:</label>
						<input class="form-control" type="password" name="password" placeholder="Password" />
</div>
<div class="form-group">
    <label for="firstname">First Name:</label>
            <input class="form-control" type="text" name="firstname" placeholder="Firstname" />
          </div>
          <div class="form-group">
    <label for="lastname">Last Name:</label>
            <input class="form-control" type="text" name="lastname" placeholder="Lastname" />
          </div>
          <div class="form-group">
                <label for="companyname">Company Name:</label>
                <input class="form-control" type="text" name="companyname" placeholder="Company Name" />
</div>
						<button class="btn btn-primary" type="submit">Add User</button>
</div>
</div>
          </form>


				  </div>
