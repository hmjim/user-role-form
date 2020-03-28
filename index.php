<?php

require_once 'config.php';
$error = isset( $_GET['error'] ) && ! empty( $_GET['error'] ) ? $_GET['error'] : null;

// connect to mysql database
try {
	$dns = 'mysql:host=' . HOST . ';dbname=' . DB;
	$pdo = new PDO( $dns, USER, PASS );
	// set the PDO error mode to exception
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch ( PDOException $e ) {
	echo '<p style="color:red">mysql connection failed: ' . $e->getMessage() . '</p>';
	echo '<p>try to refresh</p>';
}

// select data from database
$stmt = $pdo->prepare( 'SELECT user.username, user_role.rolename FROM `user` JOIN `user_role` ON `user`.role_id = `user_role`.id' );
$stmt->execute();

$list = $stmt->fetchAll( PDO::FETCH_ASSOC );

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title>List</title>
		<style>
			h1, h2 {
				text-align: center;
				text-transform: uppercase;
			}

			.DBForm {
				padding: 1rem;
			}

			.DBForm * {
				display: flex;
				text-align: center;
				padding: 5px 10px;
				margin: 5px auto;
			}

			select {
				width: 180px;
				text-align: center;
			}

			.Error {
				color: tomato;
				margin-top: 0.6rem;
			    text-align: center;
			}

			.Fields .field {
				padding: 1rem;
				text-align: center;
			}

			.add_list {
				padding: 5px 77px;
			}
		</style>
	</head>
	<body>
		<h1>Add name and role</h1>
		<form method="post" action="action.php" class="DBForm" >
			<input type="text" name="username" placeholder="your name" />
			<select name="role_id">
				<option value="1">First</option>
				<option value="2">Second</option>
				<option value="3">Ultimate</option>
			</select>
			<input class="add_list" type="submit" name="action" value="add" />
		</form>

		<div class="Error">
			<?php
			if ( ! is_null( $error ) ) {
				echo 'error: ' . $error;
			} 
			?>
		</div>

		<h2>Name and role</h2>
		<div class="Fields">
			<?php
			$reversed = array_reverse($list);
			foreach ( $reversed as $key => $val ) {
				echo '<div class="field">';
				foreach ( $val as $k => $v ) {
					echo '<div><b>' . $k . ': </b>' . $v . '</div>';
				}
				echo '</div>';
			}
			?>
		</div>
	</body>
</html>
