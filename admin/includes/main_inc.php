<?php
\error_reporting(\E_ALL);
\ini_set('display_errors', 'stdout');

// enable assertions
\ini_set('assert.active', 1);
@\ini_set('zend.assertions', 1);
\ini_set('assert.exception', 1);

\header('Content-type: text/html; charset=utf-8');

require __DIR__.'/../vendor/autoload.php';

$db = new \PDO('mysql:dbname=login_app;host=127.0.0.1;charset=utf8mb4', 'root', '', 'false');
// or
// $db = new \PDO('pgsql:dbname=php_auth;host=127.0.0.1;port=5432', 'postgres', 'monkey');
// or
// $db = new \PDO('sqlite:../Databases/php_auth.sqlite');

$auth = new \Delight\Auth\Auth($db);

$result = \processRequestData($auth);
$domainRoot = '/rebuild';
$loc='Welcome';



function processRequestData(\Delight\Auth\Auth $auth) {
	if (isset($_POST)) {
		if (isset($_POST['action'])) {
			if ($_POST['action'] === 'login') {
				if (isset($_POST['remember'])) {
					// keep logged in for one year
					$rememberDuration = (int) (60 * 60 * 24 * 365.25);
				}
				else {
					// do not keep logged in after session ends
					$rememberDuration = null;
				}

				try {
					if (isset($_POST['email'])) {
						$auth->login($_POST['email'], $_POST['password'], $rememberDuration);
					}
					elseif (isset($_POST['username'])) {
						$auth->loginWithUsername($_POST['username'], $_POST['password'], $rememberDuration);
					}
					else {
						return 'either email address or username required';
					}

					return 'ok';
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					echo '<div class="alert alert-danger alert-dismissible">
        <strong>Error!</strong> Please enter a valid username or password.
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>';
				}
				catch (\Delight\Auth\UnknownUsernameException $e) {
					echo 'unknown username';
				}
				catch (\Delight\Auth\AmbiguousUsernameException $e) {
					return 'ambiguous username';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					echo '<div class="alert alert-danger alert-dismissible">
        <strong>Error!</strong> Please enter a valid username or password.
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>';
				}
				catch (\Delight\Auth\EmailNotVerifiedException $e) {
					return 'Email Address not verified';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too masny requests';
				}
			}
			else if ($_POST['action'] === 'register') {
				try {
					if ($_POST['require_verification'] == 1) {
						$callback = function ($selector, $token) {
							echo '<pre>';
							echo 'Email confirmation';
							echo "\n";
							echo '  >  Selector';
							echo "\t\t\t\t";
							echo \htmlspecialchars($selector);
							echo "\n";
							echo '  >  Token';
							echo "\t\t\t\t";
							echo \htmlspecialchars($token);
							echo '</pre>';
						};
					}
					else {
						$callback = null;
					}

					if (!isset($_POST['require_unique_username'])) {
						$_POST['require_unique_username'] = '0';
					}

					if ($_POST['require_unique_username'] == 0) {
						return $auth->register($_POST['email'], $_POST['password'], $_POST['username'], $callback);
					}
					else {
						return $auth->registerWithUniqueUsername($_POST['email'], $_POST['password'], $_POST['username'], $callback);
					}
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					return 'invalid email address';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password';
				}
				catch (\Delight\Auth\UserAlreadyExistsException $e) {
					return 'Email Address already exists';
				}
				catch (\Delight\Auth\DuplicateUsernameException $e) {
					return 'username already exists';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'confirmEmail') {
				try {
					if (isset($_POST['login']) && $_POST['login'] > 0) {
						if ($_POST['login'] == 2) {
							// keep logged in for one year
							$rememberDuration = (int) (60 * 60 * 24 * 365.25);
						}
						else {
							// do not keep logged in after session ends
							$rememberDuration = null;
						}

						return $auth->confirmEmailAndSignIn($_POST['selector'], $_POST['token'], $rememberDuration);
					}
					else {
						return $auth->confirmEmail($_POST['selector'], $_POST['token']);
					}
				}
				catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
					return 'invalid token';
				}
				catch (\Delight\Auth\TokenExpiredException $e) {
					return 'token expired';
				}
				catch (\Delight\Auth\UserAlreadyExistsException $e) {
					return 'Email Address already exists';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'resendConfirmationForEmail') {
				try {
					$auth->resendConfirmationForEmail($_POST['email'], function ($selector, $token) {
						echo '<pre>';
						echo 'Email confirmation';
						echo "\n";
						echo '  >  Selector';
						echo "\t\t\t\t";
						echo \htmlspecialchars($selector);
						echo "\n";
						echo '  >  Token';
						echo "\t\t\t\t";
						echo \htmlspecialchars($token);
						echo '</pre>';
					});

					return 'ok';
				}
				catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
					return 'no request found';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'resendConfirmationForUserId') {
				try {
					$auth->resendConfirmationForUserId($_POST['userId'], function ($selector, $token) {
						echo '<pre>';
						echo 'Email confirmation';
						echo "\n";
						echo '  >  Selector';
						echo "\t\t\t\t";
						echo \htmlspecialchars($selector);
						echo "\n";
						echo '  >  Token';
						echo "\t\t\t\t";
						echo \htmlspecialchars($token);
						echo '</pre>';
					});

					return 'ok';
				}
				catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
					return 'no request found';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'forgotPassword') {
				try {
					$auth->forgotPassword($_POST['email'], function ($selector, $token) {
						echo '<pre>';
						echo 'Password reset';
						echo "\n";
						echo '  >  Selector';
						echo "\t\t\t\t";
						echo \htmlspecialchars($selector);
						echo "\n";
						echo '  >  Token';
						echo "\t\t\t\t";
						echo \htmlspecialchars($token);
						echo '</pre>';
					});

					return 'ok';
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					return 'invalid email address';
				}
				catch (\Delight\Auth\EmailNotVerifiedException $e) {
					return 'Email Address not verified';
				}
				catch (\Delight\Auth\ResetDisabledException $e) {
					return 'password reset is disabled';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'resetPassword') {
				try {
					$auth->resetPassword($_POST['selector'], $_POST['token'], $_POST['password']);

					return 'ok';
				}
				catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
					return 'invalid token';
				}
				catch (\Delight\Auth\TokenExpiredException $e) {
					return 'token expired';
				}
				catch (\Delight\Auth\ResetDisabledException $e) {
					return 'password reset is disabled';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'canResetPassword') {
				try {
					$auth->canResetPasswordOrThrow($_POST['selector'], $_POST['token']);

					return 'yes';
				}
				catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
					return 'invalid token';
				}
				catch (\Delight\Auth\TokenExpiredException $e) {
					return 'token expired';
				}
				catch (\Delight\Auth\ResetDisabledException $e) {
					return 'password reset is disabled';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'reconfirmPassword') {
				try {
					return $auth->reconfirmPassword($_POST['password']) ? 'correct' : 'wrong';
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'changePassword') {
				try {
					$auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);

					return 'ok';
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password(s)';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'changePasswordWithoutOldPassword') {
				try {
					$auth->changePasswordWithoutOldPassword($_POST['newPassword']);

					return 'ok';
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password';
				}
			}
			else if ($_POST['action'] === 'changeEmail') {
				try {
					$auth->changeEmail($_POST['newEmail'], function ($selector, $token) {
						echo '<pre>';
						echo 'Email confirmation';
						echo "\n";
						echo '  >  Selector';
						echo "\t\t\t\t";
						echo \htmlspecialchars($selector);
						echo "\n";
						echo '  >  Token';
						echo "\t\t\t\t";
						echo \htmlspecialchars($token);
						echo '</pre>';
					});

					return 'ok';
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					return 'invalid email address';
				}
				catch (\Delight\Auth\UserAlreadyExistsException $e) {
					return 'Email Address already exists';
				}
				catch (\Delight\Auth\EmailNotVerifiedException $e) {
					return 'account not verified';
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'Too many requests';
				}
			}
			else if ($_POST['action'] === 'setPasswordResetEnabled') {
				try {
					$auth->setPasswordResetEnabled($_POST['enabled'] == 1);

					return 'ok';
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
			}
			else if ($_POST['action'] === 'logOut') {
				$auth->logOut();

				return 'ok';
			}
			else if ($_POST['action'] === 'logOutEverywhereElse') {
				try {
					$auth->logOutEverywhereElse();
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'logOutEverywhere') {
				try {
					$auth->logOutEverywhere();
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'destroySession') {
				$auth->destroySession();

				return 'ok';
			}
			else if ($_POST['action'] === 'admin.createUser') {
				try {
					if (!isset($_POST['require_unique_username'])) {
						$_POST['require_unique_username'] = '0';
					}

					if ($_POST['require_unique_username'] == 0) {
						return $auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
					}
					else {
						return $auth->admin()->createUserWithUniqueUsername($_POST['email'], $_POST['password'], $_POST['username']);
					}
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					return 'invalid email address';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password';
				}
				catch (\Delight\Auth\UserAlreadyExistsException $e) {
					return 'Email Address already exists';
				}
				catch (\Delight\Auth\DuplicateUsernameException $e) {
					return 'username already exists';
				}
			}
			else if ($_POST['action'] === 'admin.deleteUser') {
				if (isset($_POST['id'])) {
					try {
						$auth->admin()->deleteUserById($_POST['id']);
					}
					catch (\Delight\Auth\UnknownIdException $e) {
						return 'unknown ID';
					}
				}
				elseif (isset($_POST['email'])) {
					try {
						$auth->admin()->deleteUserByEmail($_POST['email']);
					}
					catch (\Delight\Auth\InvalidEmailException $e) {
						return 'unknown email address';
					}
				}
				elseif (isset($_POST['username'])) {
					try {
						$auth->admin()->deleteUserByUsername($_POST['username']);
					}
					catch (\Delight\Auth\UnknownUsernameException $e) {
						return 'unknown username';
					}
					catch (\Delight\Auth\AmbiguousUsernameException $e) {
						return 'ambiguous username';
					}
				}
				else {
					return 'either ID, email address or username required';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'admin.addRole') {
				if (isset($_POST['role'])) {
					if (isset($_POST['id'])) {
						try {
							$auth->admin()->addRoleForUserById($_POST['id'], $_POST['role']);
						}
						catch (\Delight\Auth\UnknownIdException $e) {
							return 'unknown ID';
						}
					}
					elseif (isset($_POST['email'])) {
						try {
							$auth->admin()->addRoleForUserByEmail($_POST['email'], $_POST['role']);
						}
						catch (\Delight\Auth\InvalidEmailException $e) {
							return 'unknown email address';
						}
					}
					elseif (isset($_POST['username'])) {
						try {
							$auth->admin()->addRoleForUserByUsername($_POST['username'], $_POST['role']);
						}
						catch (\Delight\Auth\UnknownUsernameException $e) {
							return 'unknown username';
						}
						catch (\Delight\Auth\AmbiguousUsernameException $e) {
							return 'ambiguous username';
						}
					}
					else {
						return 'either ID, email address or username required';
					}
				}
				else {
					return 'role required';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'admin.removeRole') {
				if (isset($_POST['role'])) {
					if (isset($_POST['id'])) {
						try {
							$auth->admin()->removeRoleForUserById($_POST['id'], $_POST['role']);
						}
						catch (\Delight\Auth\UnknownIdException $e) {
							return 'unknown ID';
						}
					}
					elseif (isset($_POST['email'])) {
						try {
							$auth->admin()->removeRoleForUserByEmail($_POST['email'], $_POST['role']);
						}
						catch (\Delight\Auth\InvalidEmailException $e) {
							return 'unknown email address';
						}
					}
					elseif (isset($_POST['username'])) {
						try {
							$auth->admin()->removeRoleForUserByUsername($_POST['username'], $_POST['role']);
						}
						catch (\Delight\Auth\UnknownUsernameException $e) {
							return 'unknown username';
						}
						catch (\Delight\Auth\AmbiguousUsernameException $e) {
							return 'ambiguous username';
						}
					}
					else {
						return 'either ID, email address or username required';
					}
				}
				else {
					return 'role required';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'admin.hasRole') {
				if (isset($_POST['id'])) {
					if (isset($_POST['role'])) {
						try {
							return $auth->admin()->doesUserHaveRole($_POST['id'], $_POST['role']) ? 'yes' : 'no';
						}
						catch (\Delight\Auth\UnknownIdException $e) {
							return 'unknown ID';
						}
					}
					else {
						return 'role required';
					}
				}
				else {
					return 'ID required';
				}
			}
			else if ($_POST['action'] === 'admin.getRoles') {
				if (isset($_POST['id'])) {
					try {
						return $auth->admin()->getRolesForUserById($_POST['id']);
					}
					catch (\Delight\Auth\UnknownIdException $e) {
						return 'unknown ID';
					}
				}
				else {
					return 'ID required';
				}
			}
			else if ($_POST['action'] === 'admin.logInAsUserById') {
				if (isset($_POST['id'])) {
					try {
						$auth->admin()->logInAsUserById($_POST['id']);

						return 'ok';
					}
					catch (\Delight\Auth\UnknownIdException $e) {
						return 'unknown ID';
					}
					catch (\Delight\Auth\EmailNotVerifiedException $e) {
						return 'Email Address not verified';
					}
				}
				else {
					return 'ID required';
				}
			}
			else if ($_POST['action'] === 'admin.logInAsUserByEmail') {
				if (isset($_POST['email'])) {
					try {
						$auth->admin()->logInAsUserByEmail($_POST['email']);

						return 'ok';
					}
					catch (\Delight\Auth\InvalidEmailException $e) {
						return 'unknown email address';
					}
					catch (\Delight\Auth\EmailNotVerifiedException $e) {
						return 'Email Address not verified';
					}
				}
				else {
					return 'Email Address required';
				}
			}
			else if ($_POST['action'] === 'admin.logInAsUserByUsername') {
				if (isset($_POST['username'])) {
					try {
						$auth->admin()->logInAsUserByUsername($_POST['username']);

						return 'ok';
					}
					catch (\Delight\Auth\UnknownUsernameException $e) {
						return 'unknown username';
					}
					catch (\Delight\Auth\AmbiguousUsernameException $e) {
						return 'ambiguous username';
					}
					catch (\Delight\Auth\EmailNotVerifiedException $e) {
						return 'Email Address not verified';
					}
				}
				else {
					return 'Username required';
				}
			}
			else if ($_POST['action'] === 'admin.changePasswordForUser') {
				if (isset($_POST['newPassword'])) {
					if (isset($_POST['id'])) {
						try {
							$auth->admin()->changePasswordForUserById($_POST['id'], $_POST['newPassword']);
						}
						catch (\Delight\Auth\UnknownIdException $e) {
							return 'unknown ID';
						}
						catch (\Delight\Auth\InvalidPasswordException $e) {
							return 'invalid password';
						}
					}
					elseif (isset($_POST['username'])) {
						try {
							$auth->admin()->changePasswordForUserByUsername($_POST['username'], $_POST['newPassword']);
						}
						catch (\Delight\Auth\UnknownUsernameException $e) {
							return 'unknown username';
						}
						catch (\Delight\Auth\AmbiguousUsernameException $e) {
							return 'ambiguous username';
						}
						catch (\Delight\Auth\InvalidPasswordException $e) {
							return 'invalid password';
						}
					}
					else {
						return 'either ID or username required';
					}
				}
				else {
					return 'new password required';
				}

				return 'ok';
			}
			else {
				throw new Exception('Unexpected action: ' . $_POST['action']);
			}
		}
	}

	return null;
}

function convertStatusToText(\Delight\Auth\Auth $auth) {
	if ($auth->isLoggedIn() === true) {
		if ($auth->getStatus() === \Delight\Auth\Status::NORMAL && $auth->isNormal()) {
			return 'normal';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::ARCHIVED && $auth->isArchived()) {
			return 'archived';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::BANNED && $auth->isBanned()) {
			return 'banned';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::LOCKED && $auth->isLocked()) {
			return 'locked';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::PENDING_REVIEW && $auth->isPendingReview()) {
			return 'pending review';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::SUSPENDED && $auth->isSuspended()) {
			return 'suspended';
		}
	}
	elseif ($auth->isLoggedIn() === false) {
		if ($auth->getStatus() === null) {
			return 'none';
		}
	}

	throw new Exception('Invalid status `' . $auth->getStatus() . '`');
}

function showAuthenticatedUserForm(\Delight\Auth\Auth $auth) {
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="reconfirmPassword" />';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<button type="submit">Reconfirm password</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changePassword" />';
	echo '<input type="text" name="oldPassword" placeholder="Old password" /> ';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changePasswordWithoutOldPassword" />';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password without old password</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changeEmail" />';
	echo '<input type="text" name="newEmail" placeholder="New email address" /> ';
	echo '<button type="submit">Change email address</button>';
	echo '</form>';


	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="setPasswordResetEnabled" />';
	echo '<select name="enabled" size="1">';
	echo '<option value="0"' . ($auth->isPasswordResetEnabled() ? '' : ' selected="selected"') . '>Disabled</option>';
	echo '<option value="1"' . ($auth->isPasswordResetEnabled() ? ' selected="selected"' : '') . '>Enabled</option>';
	echo '</select> ';
	echo '<button type="submit">Control password resets</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOut" />';
	echo '<button type="submit">Log out</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOutEverywhereElse" />';
	echo '<button type="submit">Log out everywhere else</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOutEverywhere" />';
	echo '<button type="submit">Log out everywhere</button>';
	echo '</form>';
}








?>
