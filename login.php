<?php
	session_start();
	include('connect.php');
	$username = $_POST["username"];
	$password = $_POST["password"];

	$authen = exec("echo '$username $password' | sudo /usr/lib/squid/basic_ldap_auth -v 3 -b 'dc=localdomain,dc=com' -f 'uid=%s' localdomain.com");
	if ($authen == "OK") {
		try {
			$stmt = $conn->prepare("SELECT * FROM user WHERE username=:username");
			$stmt->bindParam(":username", $username);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if (empty($result)) {
				$output;
				exec("sudo ldapsearch -x -LLL -b dc=localdomain,dc=com '(uid=$username)' uid givenName sn uidNumber gidNumber", $output);
				foreach ($output as $o) {
					list($key, $value) = explode(": ", $o, 2);
					$key = trim($key);
					$value = trim($value);
					$userData[$key] = $value;
				}

				$stmtInsert = $conn->prepare("INSERT INTO user (uid, username, firstname, lastname, role)
										VALUES (:uid, :username, :firstname, :lastname, :role)");
				$stmtInsert->bindParam(":uid", $userData["uidNumber"]);
				$stmtInsert->bindParam(":username", $userData["uid"]);
				$stmtInsert->bindParam(":firstname", $userData["givenName"]);
				$stmtInsert->bindParam(":lastname", $userData["sn"]);
				$stmtInsert->bindValue(":role", $userData["gidNumber"] == "10000" ? "admin" : ($userData["gidNumber"] == "20000" ? "manager" : "customer"));

				$stmtInsert->execute();

				$stmt->execute();
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			$_SESSION["user"] = $result;
		} catch (PDOException $e) {
			echo "error" . $e->getMessage();
		}
	}
	header("refresh: 0; url=../");
?>
