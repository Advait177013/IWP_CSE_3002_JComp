<?php
    $custid = $_GET["uid"];
    $name = $_GET["name"];
    $bal = $_GET["bal"];
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="projectstyles.css"> 

</head>
<body>
    <h1>Add Balance</h1>
    <br>
    <p> <?php echo $name?> : <?php echo $bal?>.</p>
    <br>
    <form method="post">
        <input type="number" name="baladd" value="0" required/><br>
        <input type="submit" name="button2" value="Click Here to Add Balance and return to your Home"/><br>
        <input type="submit" name="button3" value="Click Here to Logout"/>
    </form>

    <?php
        if(isset($_POST['button2'])) {
            $servername = "localhost";
            $username = "advait_localhost";
            $password = "password";
            $dbname = "jcomp";
            $addbal = test_input($_POST['baladd']);
            $bal=$bal+$addbal;
        
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            $generic_error="";
            // Check connection
            if ($conn->connect_error) {
            die("<br>Connection failed: " . $conn->connect_error);
            }
            $sql = "update custdetails set custmoney='$bal' where cust_id='$custid'";
            if(mysqli_query($conn, $sql))
            {
                header('Location: BookCatalogue.php?uid='.$custid);
            }
            else
            {
                echo "Error. Try Again<br>";
            }
            
        }
        if(isset($_POST['button3'])) {
            header('Location:jcomp-logout.html');
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
            }
    ?>

<?php

$conn->close();
?>
</body>
</html>