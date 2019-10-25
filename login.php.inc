<?php
require_once("Log.php.inc");
class loginDB
{
private $logindb;
private $l;    

public function __construct()
{
	$this->l =new iLog("./login.php.inc.log","a");
	$this->logindb = new mysqli("localhost","root"," ","it490");

	if ($this->logindb->connect_errno != 0)
	{
		$this->l->print("Error connecting to database:" .$this->logindb->connect_error.PHP_EOL);
		exit(1);
	}
	$this->l->print("correctly connected to database:".PHP_EOL);
}

public function validateLogin($username,$password)
{
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);
	$pw = $pw;
	$statement = "select * from users where username = '$un'";

	$this->l->print($statement .PHP_EOL); //Debug

	$response = $this->logindb->query($statement);

	while ($row = $response->fetch_assoc())
	{
		$this->l->print("checking password for $username".PHP_EOL);
		if ($row["password"] == $pw)
		{
			$this->l->print("passwords match for $username".PHP_EOL);
			$this->l->sendToRabbitMQ("./login.php.inc.log","./_logs/login.php.inc.log");
			return 1;// password match
		}
		$this->l->print("passwords did not match for $username".PHP_EOL);
	}
	$this->l->sendToRabbitMQ("./login.php.inc.log","./_logs/login.php.inc.log");
	return 0;//no users matched username
}

public function register($user, $email, $pass, $confirmPass, $plainPass)
{
	$user = $this->logindb->real_escape_string($user);
	$email = $this->logindb->real_escape_string($email);
	
	//Check if user is in DB already
	$statement = "select * from users where username = '$user'";
	$this ->l -> print($statement .PHP_EOL); //Debug
	$response = $this->logindb->query($statement);
	$num = mysqli_num_rows($response);
	
	if ($num > 0){
		$this ->l -> print("Username is already taken.:").PHP_EOL;
		$this ->l ->sendToRabbitMQ("./login.php.inc.log","./_logs/login.php.inc.log");
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
	if (strcmp($pass, $confirmPass)!==0){
		$this ->l ->print("Passwords do not match. Must confirm password.").PHP_EOL;
		$this ->l ->sendToRabbitMQ("./login.php.inc.log","./_logs/login.php.inc.log");
		return 0;
	}

	//Add to DB
	$statement = "INSERT INTO users (username, password, plainpassword, email, verify) VALUES ('$user','$pass','$plainpass','$email,'0')";
	$this ->l ->print($statement .PHP_EOL); //Debug
	$response=$this->logindb->query($statement);
	if($response===TRUE){
	
	$this ->l ->print("Success. Check DB and client response.");
	
	return 1;//user added to DB successfully
	}
	else{
		$this ->l ->print($response);
		$this ->l ->print("Shit went wrong");
		$this ->l ->sendToRabbitMQ();
		return 0;
	}
}

}
?>
