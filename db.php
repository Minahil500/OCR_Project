<?php
$conn = mysqli_connect( "localhost", "root", "root", "ocr_db",
    8889
);
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>