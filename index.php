<?php echo 'hi' ?>
<?php echo 'hi2' ?>
<?php
	$op;
	$val;
	
	$username = "john";
	$password = "1234";
	//$test = exec("echo '$username $password' | sudo /usr/lib/squid/basic_ldap_auth -v 3 -b 'dc=localdomain,dc=com' -f 'uid=%s' localdomain.com", $op, $val);
	$test = exec("echo '6309682091' | sudo -S ls -l");
	echo $val;
	foreach ($array as $op) {
		echo "<br>";
		echo $array;
	}
	echo "<br>";
	echo $test;

	echo "<br>";
	$test2 = shell_exec("sudo whoami");
	//$test2 = shell_exec("echo '$username $password' | sudo /usr/lib/squid/basic_ldap_auth -v 3 -b 'dc=localdomain,dc=com' -f 'uid=%s' localdomain.com");
	echo $test2;

?>
