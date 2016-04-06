<?php namespace Main;?>
<?php

date_default_timezone_set ( 'Europe/Berlin' );
// ###############################################################################################################
function create_new_user($username, $password, $email, $permissions) {
	if (! is_logdin () || (! is_admin () || ! is_root ()))
		die ( return_new_warning ( "Sie haben keine Berechtigung, dies zu tun!" ) );
	
	$hash = password_hash ( $password, PASSWORD_BCRYPT );
	unset ( $password );
	
	// is_logdin()
	// ####################################
	// is_admin()
	// allow to create $permissions > 500 && $permissions <= 1500;
	// ####################################
	// is_root()
	// allow to create $permissions > 500 && $permissions <= 4500;
	// ####################################
	// if not admin or root return error
	// ####################################
	// else
	// ####################################
	// write user to db
}
function delete_user($username) {
	if (! is_logdin () || (! is_admin () || ! is_root ()))
		die ( "Sie haben keine Berechtigung, dies zu tun!" );
	if ($username = "root")
		die ( "How DARE you, trie to delete the root?!" );
	// is_logdin()
	// ####################################
	// is_admin()
	// allow to create $permissions > 500 && $permissions <= 1500;
	// ####################################
	// is_root()
	// allow to create $permissions > 500 && $permissions <= 4500;
	// ####################################
	// if not admin or root return error
	// ####################################
	// else
	// ####################################
	// delete user
}
function change_permissions($username) {
	
	// is_logdin()
	// ####################################
	// is_admin()
	// allow to change persons with 1500 >= right >=500
	// to 1500 >= right >= 500
	// ####################################
	// is_root()
	// allow to change persons with 4500 >= right >=500
	// to 4500 >= right >= 500
	// ####################################
	// else return noooooooooooooooooooo5
}
function login_locked() {
	// try to find a MLockLogin in db "Manual"
	return false;
}
function lock_login() {
}
function unlock_login() {
}


?>