<?php
include('db.php');
$jaNej = mysqli_fetch_assoc(mysqli_query($db,"SELECT yesNo FROM openclosed"));
?>