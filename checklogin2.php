<?php
session_start();

include("db.php");

$tbl_name="members2"; // Table name 
//
// username and password sent from form 
$myusername=$_POST['myusernameDoom']; 
$mypassword=$_POST['mypasswordDoom']; 

// encrypt password 
$encrypted_mypassword=md5($mypassword);
$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$encrypted_mypassword'";
$result=mysqli_query($db, $sql);

// To protect MySQL injection
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysqli_real_escape_string($db, $myusername);
$mypassword = mysqli_real_escape_string($db, $mypassword);
$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";
$result=mysqli_query($db, $sql);

// Mysql_num_row is counting table row
$count=mysqli_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){

// Register $myusername, $mypassword and redirect to file "login_success.php"
$_SESSION['myusernameDoom']= stripslashes($myusername);
$_SESSION['mypasswordDoom']= stripslashes($mypassword);
header("location:doom.php");
}
else {
echo "Wrong Username or Password. Ask an admin for help.";
}
?>