<?php
include ("accounts.php");

//Connect to MySQL
$db = mysqli_connect($hostname,$username, $password ,$project);
if (mysqli_connect_errno()) {
	  print "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
print "Successfully connected to MySQL.\n\n";

echo "Entered user $argv[1]\n";
echo "Entered pass $argv[2]\n\n";

//Try select statement
//Authentication Function
function authentication ($user, $pass){
   global $db ;
   $pass = sha1 ($pass);
   $s ="select * from users where username='$user' and password= '$pass'";
   ($t1= mysqli_query($db, $s) ) or  die ( mysqli_error( $db ) );
   $num = mysqli_num_rows ($t1); 
   if ($num == 0){
     return false;  }//Incorrect user/password
   else
     return true; //Correct info!
}

//Let's try the authentication function
if (authentication ($argv[1],$argv[2])){
	print ("Authentication successful!\n\n");
}
else{
print ("Incorrect Username/Password.\n\n");
}

?>
