<?php
//Note: implementing without sessions and password hashing, as should be done ideally. 
$servername = "localhost";
$username = "advait_localhost";
$password = "password";
$dbname = "jcomp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("<br>Connection failed: " . $conn->connect_error);
}
echo "<br>Connected successfully";

// $sql = "CREATE DATABASE jcomp";
// if ($conn->query($sql) === TRUE) 
// {
//   echo "<br>Database created successfully";
// } 
// else 
// {
//   echo "<br>Error creating database: " . $conn->error;
// }

// $sql = "CREATE TABLE BookDetails (
//     book_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     bookname VARCHAR(30) NOT NULL,
//     authorname VARCHAR(50) not null,
//     stock int(6) unsigned not null,
//     price int(6) unsigned not null,
//     tags varchar(3) not null
//     )";
// if ($conn->query($sql) === TRUE) 
// {
//     echo "<br>Table BookDetails created successfully";
// } 
// else 
// {
//     echo "<br>Error creating table: " . $conn->error;
// }

// $sql = "CREATE TABLE CustDetails (
//     cust_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     custname VARCHAR(30) NOT NULL,
//     custpass VARCHAR(30) not null,
//     custmoney int(6) unsigned not null,
//     custemail varchar(30) not null
//     )";
// if ($conn->query($sql) === TRUE) 
// {
//     echo "<br>Table CustDetails created successfully";
// } 
// else 
// {
//     echo "<br>Error creating table: " . $conn->error;
// }

// $sql = "CREATE TABLE CustVerify (
//     cust_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     verificationcode int(6) not null
//     )";
// if ($conn->query($sql) === TRUE) 
// {
//     echo "<br>Table CustDetails created successfully";
// } 
// else 
// {
//     echo "<br>Error creating table: " . $conn->error;
// }

// $sql = "CREATE TABLE CustPref (
//     cust_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     preferences int(6) not null
//     )";
// if ($conn->query($sql) === TRUE) 
// {
//     echo "<br>Table CustDetails created successfully";
// } 
// else 
// {
//     echo "<br>Error creating table: " . $conn->error;
// }

//preferences is 4 categories so 6 size int, start and end with 1, middle 4 can be 0 or 1 
//depending on if category is prefered
//sci-fi, biography, thriller, literature

$conn->close();
?>
