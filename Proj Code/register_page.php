<head>
    <title>register page</title>
    <link rel="stylesheet" type="text/css" href="projectstyles.css"> 
</head>
<body>
    <?php
        function generateRandomString($length = 6) {
            return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        }
    ?>
    <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        require 'vendor/autoload.php';
        //Note: implementing without sessions and password hashing, as should be done ideally. 
        $servername = "localhost";
        $username = "advait_localhost";
        $password = "password";
        $dbname = "jcomp";
        $last_id=0;

        $nameErr = "";
        $emailErr = "";
        $pwdErr = "";
        $repwdErr = "";
        $generic_error = "";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
        die("<br>Connection failed: " . $conn->connect_error);
        }

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $enteredpwd = test_input($_POST["pwd"]);
            $retypedpwd = test_input($_POST["repwd"]);
            if($enteredpwd==$retypedpwd)
            {
                $custname = test_input($_POST["name"]);
                $custemail = test_input($_POST["email"]);
                $verificationcode = generateRandomString();
                $sql = "insert into tempdetails (custname, custpass, custemail, verificationcode) values ('$custname', '$enteredpwd', '$custemail', '$verificationcode')";
                if(mysqli_query($conn, $sql))
                {
                    $last_id = $conn->insert_id;
                    //redirect to verification page + send email
                    $mail = new PHPMailer(true);
                    try 
                    {									
                        $mail->isSMTP();											
                        $mail->Host	 = 'smtp.gmail.com;';					
                        $mail->SMTPAuth = true;							
                        $mail->Username = 'advaitdeochakke@gmail.com';				
                        $mail->Password = '<Password Removed>'; //appspecific password generation for automation						
                        $mail->SMTPSecure = 'tls';							
                        $mail->Port	 = 587;
                        $mail->SMTPDebug = false;

                        $mail->setFrom('advaitdeochakke@gmail.com', 'Advait');		
                        $mail->addAddress($custemail, $custname);
                        
                        $mail->isHTML(true);								
                        $mail->Subject = 'Verification Code for Best Books Ever';
                        $mail->Body = 'This is your Verification Code <br> '.$verificationcode.' <br>Your Temp Id is :  '.$last_id.'<br>Please use the above credentials to verify yourself.';
                        $mail->send();
                    } 
                    catch (Exception $e) 
                    {
                        $generic_error = $generic_error."<br>Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    finally
                    {
                        header('Location: Verification.php?uid='.$last_id);
                    }
                } 
                else 
                {
                    echo "Error: <br>".mysqli_error($conn);
                }
            }
            else
            {
                echo "Passwords dont match<br>";
            }
            
        }   

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
            }
    ?>
    <h2> Register for The Best Books Service </h2><br>
    <h3> Register now and get free 500 Book Dollars </h3><br>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        Username: <input type="text" name="name" required>
        <span class="error">* <?php echo $nameErr;?></span>
        <br><br>
        Email: <input type="email" name="email" required>
        <span class="error"><?php echo $emailErr;?></span>
        <br><br>
        Password: <input type="password" name="pwd" required>
        <span class="error">* <?php echo $pwdErr;?></span>
        <br><br>
        Retype Password: <input type="password" name="repwd" required>
        <span class="error"><?php echo $repwdErr;?></span>
        <br><br>
        <input type="submit" name="submit" value="Submit">  
    </form>

    <?php

        $conn->close();
    ?>
    <span class="error"><?php echo $generic_error?></span>
    <br>
    <a href="jcomp-home.html">Head back to the home page</a>
</body>
