<head>
    <title>login page</title>
    <style>
            .error
            {
                color:red;
            }
    </style>
    <link rel="stylesheet" type="text/css" href="projectstyles.css"> 

</head>
<body>
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
        //echo "<br>Connected successfully";
        $nameErr = "";
        $pwdErr = "";
        $mysql = "";
        $incorrectpass = "";
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (empty($_POST["name"])) 
            {
                $nameErr = "Required to Enter Name";
            }
            else if (empty($_POST["pwd"])) 
            {
                $pwdErr = "Required to Enter Password";
            }
            else
            {
                $enteredname = test_input($_POST["name"]);
                $enteredpwd = test_input($_POST["pwd"]);
                $sql = "select cust_id from CustDetails where custname='$enteredname' and custpass='$enteredpwd'";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result)==1)
                {
                    $row = $result->fetch_assoc();
                    $custid = $row["cust_id"];
                    header('Location: BookCatalogue.php?uid='.$custid);
                }
                else
                {
                    $incorrectpass = "Incorrect Username or Password<br>Would you like to Register?";
                }
            }
        }   

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
            }
    ?>
    <h2> Login to The Book Buying King </h2><br>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        Username: <input type="text" name="name">
        <span class="error">* <?php echo $nameErr;?></span>
        <br><br>
        Password: <input type="password" name="pwd">
        <span class="error">* <?php echo $pwdErr;?></span>
        <br><br>
        <input type="submit" name="submit" value="Submit">  
    </form>
    <span class="error"><?php echo $incorrectpass;?></span>

    <?php

        $conn->close();
    ?>
    <br>
    <a href="jcomp-logout.html">Logout</a>
    <br>
    <a href="register_page.php"><b>Click here to Register</b></a>
</body>