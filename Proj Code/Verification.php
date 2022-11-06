<?php
    $tempid = $_GET["uid"]; //can use for resend verification code notification if wanted
    //too tired for verification code resends
    $servername = "localhost";
    $username = "advait_localhost";
    $password = "password";
    $dbname = "jcomp";
    $ok = 1;
    $custname = "";
    $custpass = "";
    $custemail = "";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $generic_error="";
    // Check connection
    if ($conn->connect_error) {
    die("<br>Connection failed: " . $conn->connect_error);
    }

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="projectstyles.css"> 

</head>
<body>
    <h1>Verify Yourself</h1>
    <br>
    <form method="post">
        <label for="custverifyid">Temp ID sent to your email : </label>
        <input type="number" name="custverifyid" value="0" required/><br>
        <label for="verifycode">Verification Code : </label>
        <input type="text" name="verifycode" value="" required/><br>
        <input type="submit" name="button2" value="Submit"/><br>
        <input type="submit" name="button4" value="Resend Registration Email"/>
        <input type="submit" name="button3" value="Click Here to Cancel Registration"/><br>
    </form>
    <span class="error">
    <?php
        if(isset($_POST['button2'])) {
            
            $custverifyid = test_input($_POST['custverifyid']);
            $custverifycode = test_input($_POST['verifycode']);
            $sql = "select custname, custpass, custemail from tempdetails where cust_id='$custverifyid' and verificationcode='$custverifycode'";
            $result = mysqli_query($conn, $sql);
            if($result -> num_rows == 1)
            {
                $row = $result->fetch_assoc();
                $custname = $row["custname"];
                $custpass = $row["custpass"];
                $custemail = $row["custemail"];
                echo $custname.$custpass.$custemail;
                $ok=2;
            }
            else
            {
                $ok=0;
                echo "eror test<br>";
            }
        }
        if(isset($_POST['button3'])) {
            $sql = "drop from tempdetails where cust_id='$tempid'";
            if(mysqli_query($conn, $sql))
            {
                header('Location:jcomp-logout.html');
            }
            else
            {
                $generic_error = $generic_error."Error. Please try cancelling again.";
            }
        }
        if(isset($_POST['button3'])) {
            //resend email
        }
        if($ok==2)
        {
            $sql = "delete from tempdetails where cust_id='$tempid'";
            if(mysqli_query($conn, $sql))
            {
                $generic_error = $generic_error."successful deletion";
            }
            else
            {
                $generic_error = $generic_error."Error. Please try cancelling again.";
            }

            $sql = "insert into custdetails (custname, custpass, custmoney, custemail) values ('$custname', '$custpass', '500', '$custemail')";
            if(mysqli_query($conn, $sql))
            {
                $last_id = $conn->insert_id;
                header('Location: BookCatalogue.php?uid='.$last_id);
            }
            else
            {
                $generic_error = $generic_error."Error. Please try cancelling again.";
            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
            }
    ?>
    </span>
<?php

$conn->close();
?>
</body>
</html>