<?php

$post_action = isset( $_POST['action'] ) && ! empty( $_POST['action'] ) ? $_POST['action'] : null;

if ( ! is_null( $post_action ) ) {
	switch ( $post_action ) {
		case 'add':
			$username = isset( $_POST['username'] ) ? trim( $_POST['username'] ) : '';
			$username = ! empty( $username ) ? $username : null;
			$role_id  = isset( $_POST['role_id'] ) ? trim( $_POST['role_id'] ) : '';
			$role_id  = ! empty( $role_id ) ? $role_id : null;
			if ( ! is_null( $username ) ) {
				add_name( $username, $role_id );
			} else {
				show_index( 'invalid username !' );
			}
			break;
		default:
			show_index( 'unknown action' );
			break;
	}
} else {
	show_index( 'no action triggered !' );
}


/// functions

function add_name( $username, $role_id ) {
	require_once 'config.php';

	// connect to mysql database
	try {
		$dns = 'mysql:host=' . HOST . ';dbname=' . DB;
		$pdo = new PDO( $dns, USER, PASS );
		// set the PDO error mode to exception
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	} catch ( PDOException $e ) {
		show_index( 'mysql connection failed: ' . $e->getMessage() );
	}

	// insert into database
	$stmt = $pdo->prepare( 'INSERT INTO user (username, role_id) VALUES (:username, :role_id)' );
	$stmt->bindParam( ':username', $username, PDO::PARAM_STR );
	$stmt->bindParam( ':role_id', $role_id, PDO::PARAM_INT );
	$inserted = $stmt->execute();

	if ( $inserted ) {
		show_index();
	} else {
		show_index( 'something went wrong, please try again!' );
	}
}


function show_index( $msg = null ) {
	if ( is_null( $msg ) ) {
		header( 'Location: index.php' );
	} else {
		header( 'Location: index.php?error=' . $msg );
	}
}