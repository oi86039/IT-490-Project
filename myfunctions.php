<?php
//My Functions

//Authentication Function
function auth ($user, $pass){
   global $db ;
   $pass = sha1 ($pass);
   $s ="select * from users where user='$user' and pass= '$pass'";
   ($t1= mysqli_query($db, $s) ) or  die ( mysqli_error( $db ) );
   $num = mysqli_num_rows ($t1); 
   if ($num == 0){
     return false;  } //Incorrect info
   else
     return true; //Correct info
}

//Getdata function
function getdata($name){
   global $db;
   $temp = $_GET [ $name ] ;
   $temp = mysqli_real_escape_string($db, $temp);
   return $temp; 
}

//Redirect function
function redirect($message,$delay,$target){
  echo $message;
  header("refresh: $delay url = $target");
  exit();
}

//Gatekeeper function
function gatekeeper(){
  if(!isset($_SESSION["logged"])){
    redirect ("Must login first. Redirecting...", 3, "Login.php");
  }
}

//Show function
function show ($user,$account,$number,&$output ) {	
  global $db;
  
  $output = "<br>$user logged in successfully!<br>";
  
  //Print A2  
  $s = "select * from A2 where user = '$user' and account = '$account'" ;
  $output .= "<br>SQL statement is : $s<br>";
	($t = mysqli_query($db, $s)) or die (mysqli_error($db));
	$num = mysqli_num_rows ($t); 

  while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
     $pass = $r["pass"];
     $plainPass = $r["plainPass"];
     $mail = $r ["address"];
     $current = $r["current"];
     $trans = $r["recent_trans"];
     
     $output.= "<br>Username: $user<br>";
     $output.= "Password (Hashed): $pass <br>";
     $output.="Account #: $account <br> ";
     $output.= "Password (Unhashed): $plainPass <br>";
     $output.= "Address: $mail<br>";
     $output.=  "Current Balance: $current<br>";
     $output.= "Most Recent trans: $trans<br>";
  };
  //Print T2   
  $s1 = "select * from T2 where user = '$user' and account = '$account' order by date desc LIMIT $number";
  $output .= "<br>SQL statement: $s1<br>";
  ($t1= mysqli_query($db, $s1) ) or  die ( mysqli_error( $db ) ); 
  $num = mysqli_num_rows ($t1);  
  $output .= "<br>Number of rows: $num<br>";
   
  while ( $r1 = mysqli_fetch_array ( $t1, MYSQLI_ASSOC) ) {
    $type= $r1["type" ];
    $amount= $r1[ "amount" ];
    $date= $r1[ "date" ];
    $mail= $r1[ "mail_receipt" ];
 
    $output .="<br> Account #: $account | ";
    $output .="Transaction Type: $type | ";
    $output .="Amount: $amount | ";
    $output .="Date: $date | ";
    $output .="Email Address: $mail";
    };
    echo $output;
}

// Deposit Function
function deposit($user, $account,  &$output, $mail, $amount){
    global $db ;
    
    $s1 = "insert into T2 values( '$user', '$account', 'D', '$amount', NOW(), '$mail')";
    $output.= "<br> SQL statement is : $s1 <br>";
	  ( $t1 = mysqli_query($db, $s1) ) or die ( mysqli_error( $db ) );
      
    $s2 = "update A2 set current = current+ $amount, recent_trans = NOW()  where user= '$user' and account = '$account';";
    $output.= "<br> SQL statement is : $s2 <br>";
	  ( $t2 = mysqli_query($db, $s2) ) or die ( mysqli_error( $db ) );
     
    show ($user,$account,$number,$output);
}

// Withdraw Function
function withdraw($user, $account, &$output, $mail, $amount){
    global $db ;

    $s = "select current from A2 where user = '$user' and account = '$account' ";  
    ( $t = mysqli_query($db, $s) ) or die ( mysqli_error( $db ) );
    ( $rd = mysqli_fetch_array ( $t, MYSQLI_ASSOC) );
    $over = $rd ["current"];
    if ($amount > $over){
        exit("<br><br>  Overdraw detected. Please enter valid amount.");}
    
    $s1 = "insert into T2 values( '$user', '$account', 'W', '$amount', NOW(), '$mail')";
    $output.= "<br> SQL statement is : $s1 <br>";
	  ( $t1 = mysqli_query($db, $s1) ) or die ( mysqli_error( $db ) );
      
    $s2 = "update A2 set current = current- $amount, recent_trans = NOW()  where user= '$user' and account = '$account';";
    $output.= "<br> SQL statement is : $s2 <br>";
	  ( $t2 = mysqli_query($db, $s2) ) or die ( mysqli_error( $db ) );
     
    show ($user,$account,$number,$output );
}

// Mailer Function
function mailer($user, &$output){
   global $db;
   $headers[] = 'Content-type: text/html; charset=iso-8859-1';
   
   $s = "select * from A2 where user = '$user' ";
   ( $t = mysqli_query($db, $s) ) or die ( mysqli_error( $db ) );
   $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC);
   $mail= $r["mail"];
   
   $to = $mail;
   $subject = "Transaction Data of" . $user;
   $message = $output;
   mail($to, $subject, $message,implode("\r\n", $headers));
   echo "<br><br>Email sent successfully!";
}

?>


