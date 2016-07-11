<?php
// After the first implementation of the script, make it false for security reasons
define('INSTALL_ENABLE', true);

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET NAMES utf8");

if (INSTALL_ENABLE) {
	
	// Create tables
	$conn->query(
		"CREATE TABLE IF NOT EXISTS sgh_admins (
			id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(40) NOT NULL UNIQUE KEY,
			password VARCHAR(255) NOT NULL,	
			firstname VARCHAR(40),
			lastname VARCHAR(40),
			email VARCHAR(50),
			pic VARCHAR(255)
		)DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;"
	);
	$conn->query(
		"CREATE TABLE IF NOT EXISTS sgh_members (
			id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(40) NOT NULL UNIQUE KEY,
			password VARCHAR(255) NOT NULL,	
			firstname VARCHAR(40) NOT NULL,
			lastname VARCHAR(40) NOT NULL,
			father_name VARCHAR(40),
			meli_num VARCHAR(10),
			birth_date DATE,
			mobile VARCHAR(11),
			email VARCHAR(50),
			bank_name VARCHAR(40),
			card_num VARCHAR(16),
			hesab_num VARCHAR(13),
			pic VARCHAR(255),
			join_date DATE
		)DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;"
	);
	$conn->query(
		"CREATE TABLE IF NOT EXISTS sgh_loans (
			id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			member_id INT(11) UNSIGNED NOT NULL,
			amount BIGINT(10) NOT NULL,
			installment_num INT(3) NOT NULL,
			create_date DATE,
			description VARCHAR(500),
			status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			FOREIGN KEY (member_id) REFERENCES sgh_members(id) ON UPDATE CASCADE ON DELETE CASCADE
		)DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;"
	);
	$conn->query(
		"CREATE TABLE IF NOT EXISTS sgh_transactions (
			id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			member_id INT(11) UNSIGNED NOT NULL,
			type VARCHAR(40) NOT NULL,
			loan_id INT(11) UNSIGNED,
			amount BIGINT(10) NOT NULL,
			create_date DATE NOT NULL,
			description VARCHAR(500),
			FOREIGN KEY (member_id) REFERENCES sgh_members(id) ON UPDATE CASCADE ON DELETE CASCADE,
			FOREIGN KEY (loan_id) REFERENCES sgh_loans(id) ON UPDATE CASCADE ON DELETE CASCADE
		)DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;"
	);

	// Insert admin user 
	$admins_select = $conn->query("SELECT id FROM sgh_admins");
	if ($admins_select->num_rows == 0) {
		$conn->query("INSERT INTO sgh_admins (username, password) VALUES('admin', '" . md5('admin') . "')");
	}

}