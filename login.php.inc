<?php

class loginDB
{
private $logindb;

public function __construct()
{
	$this->logindb = new mysqli("localhost","root"," ","OmarDB");

	if ($this->logindb->connect_errno != 0)
	{
		echo "Error connecting to database: ".$this->logindb->connect_error.PHP_EOL;
		exit(1);
	}
	echo "correctly connected to database".PHP_EOL;
}

public function validateLogin($username,$password)
{
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);
	$pw = $pw;
	$statement = "select * from users where username = '$un'";

	echo $statement .PHP_EOL; //Debug

	$response = $this->logindb->query($statement);

	while ($row = $response->fetch_assoc())
	{
		echo "checking password for $username".PHP_EOL;
		if ($row["password"] == $pw)
		{
			echo "passwords match for $username".PHP_EOL;
			return 1;// password match
		}
		echo "passwords did not match for $username".PHP_EOL;
	}
	return 0;//no users matched username
}

public function register($user,$email,$pass,$confirmPass,$plainPass)
{
	$user = $this->logindb->real_escape_string($user);
	$email = $this->logindb->real_escape_string($email);
	
	//Check if user is in DB already
	$statement = "select * from users where username = '$user'";
	echo $statement .PHP_EOL; //Debug
	$response = $this->logindb->query($statement);
	$num = mysqli_num_rows($response);
	
	if ($num > 0){
		echo "Username is already taken.".PHP_EOL;
		return 0;
	}

	//Check if email is in DB already
	/*$statement = "select * from users where email = '$email'";
	echo $statement .PHP_EOL; //Debug
	$response = $this->logindb->query($statement);
	$num = mysqli_num_rows($response);
	
	if ($num > 0){
		echo "Email is already taken.".PHP_EOL;
		return 0;
	}*/

	//Confirm password
	if (strcmp($pass,$confirmPass)!==0){
		echo "Passwords do not match. Must confirm password.".PHP_EOL;
		return 0;
	}

	//Add to DB
	$statement = "INSERT INTO users (username, password, email, plainPass) VALUES ('$user','$pass','$email','$plainPass')";
	echo $statement .PHP_EOL; //Debug
	$response=$this->logindb->query($statement);
	if($response===TRUE){
	
	echo "Success. Check DB and client response.";
	
	return 1;//user added to DB successfully
	}
	else{
		echo $response;
		echo "Shit went wrong";
		return 0;
	}
}

}
?>
