<html>
<head>
<link rel="stylesheet" type="text/css" href="projectstyles.css"> 

</head>
<body>
<p class="right">Welcome User 
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor/autoload.php';

    $custid = $_GET["uid"];
    $servername = "localhost";
    $username = "advait_localhost";
    $password = "password";
    $dbname = "jcomp";
    $idtobuy = 0;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $generic_error="";
    // Check connection
    if ($conn->connect_error) {
    die("<br>Connection failed: " . $conn->connect_error);
    }
    $sql = "select * from custdetails where cust_id='$custid'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)==1)
    {
        $row = $result->fetch_assoc();
        $custmail = $row["custemail"];
        $name = $row["custname"];
        $bal = $row["custmoney"];
        echo $name."<br>Bal : ".$bal;
    }
?>
</p>

    <h1>List of all Books</h1>
    <br>
    <span style="color: cyan;">Select the Book ID of the book you want to purchase</span><br>
    <?php
        $tablebuilder = "<table><tr><th>Book ID</th><th>Book Name</th><th>Author</th><th>Price</th></tr>";
        $sql = "select book_id, bookname, authorname, price from bookdetails where stock > 0";
        $result = mysqli_query($conn, $sql);
        if($result -> num_rows > 0)
        {
            while($row = $result->fetch_assoc())
                {
                    $tablebuilder = $tablebuilder."<tr><td>".$row["book_id"]."</td><td>".$row["bookname"]."</td><td>".$row["authorname"]."</td><td>".$row["price"]."</td></tr>";
                }
        }
        else
        {
            echo "test<br>";
        }
        
        $tablebuilder = $tablebuilder."</table><br>";
        echo $tablebuilder;
    ?>

    <br>
    <h1 style="text-align: left;">Enter Book Id</h1><br>
    
    <form method="post">
        <label for="b_id">Book Id : </label>
        <input type="number" name="b_id" value="<?php echo $idtobuy?>" required><br>
        <input type="submit" name="button1" value="Buy">
    </form>

    <?php
        if(isset($_POST['button1'])) {
            $idtobuy = test_input($_POST['b_id']);
            if($idtobuy == 0)
            {
                $generic_error = "Invalid Book ID";
            }
            else
            {
                $sql = "select * from bookdetails where book_id = $idtobuy";
                $result = mysqli_query($conn, $sql);
                if($result -> num_rows == 1)
                {
                    while($row = $result->fetch_assoc())
                    {
                        $success = 1;
                        $costtobuy = $row["price"];
                        $bname = $row["bookname"];
                        $aname = $row["authorname"];

                        if($bal >= $costtobuy)
                        {
                            $newcash = $bal - $costtobuy;
                            $sql = "update custdetails set custmoney = '$newcash' where cust_id='$custid'";
                            if (mysqli_query($conn, $sql)) 
                            {
                                $stock = $row["stock"];
                                $stock-=1;
                                $sql = "update bookdetails set stock = $stock where book_id = $idtobuy";
                                if (mysqli_query($conn, $sql)) 
                                {
                                    $mail = new PHPMailer(true);
                                    try 
                                    {								
                                        $mail->isSMTP();											
                                        $mail->Host	 = 'smtp.gmail.com;';					
                                        $mail->SMTPAuth = true;							
                                        $mail->Username = 'advaitdeochakke@gmail.com';				
                                        $mail->Password = 'btdkwzhvcktacfao'; //appspecific password generation for automation						
                                        $mail->SMTPSecure = 'tls';							
                                        $mail->Port	 = 587;
                                        $mail->SMTPDebug = false;

                                        $mail->setFrom('advaitdeochakke@gmail.com', 'Advait');		
                                        $mail->addAddress($custmail, $name);
                                        
                                        $mail->isHTML(true);								
                                        $mail->Subject = 'Purchase of Book';
                                        $mail->Body = 'This is your Receipt for the purchase of '.$bname.' by '.$aname.' Succesffully. It will be delivered to you under 7 working days.';
                                        $mail->send();
                                    } 
                                    catch (Exception $e) 
                                    {
                                        $generic_error = $generic_error."<br>Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    }
                                    finally
                                    {
                                        header('Location: SuccessfulBuy.php?name='.$name.'&uid='.$custid.'&bname='.$bname.'&aname='.$aname);
                                    }
                                } 
                                else 
                                {
                                    $sql = "update custdetails set custmoney = '$bal' where cust_id='$custid'";
                                    if(mysqli_query($conn, $sql))
                                    {
                                        $generic_error = $generic_error."<br>Error purchasing. Please Try Later";
                                    }
                                    else
                                    {
                                        $generic_error = "CRITICAL ERROR : Please Contact Support";
                                    } //return bal
                                }
                            } 
                            else 
                            {
                                $generic_error = $generic_error."<br>Error purchasing. Please Try Later";
                            }
                        }
                        else
                        {
                            $generic_error = "You have insufficient Balance.<br>Check your Balance Top Right";
                        }
                    }
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
    <br>
    <form method="post">
        <input type="submit" name="button2" value="Click Here to Return to your Home"/><br>
        <input type="submit" name="button4" value="Click Here to Add Balance"/>
        <input type="submit" name="button3" value="Click Here to Logout"/>

    </form>

    <?php
        if(isset($_POST['button2'])) {
            header('Location: BookCatalogue.php?uid='.$custid);
        }
        if(isset($_POST['button3'])) {
            header('Location:jcomp-logout.html');
        }
        if(isset($_POST['button4'])) {
            header('Location: AddBal.php?uid='.$custid.'&bal='.$bal.'&name='.$name);
        }
    ?>
    <?php
    $conn->close();
    ?>    
    <span class="error"><?php echo $generic_error?></span>
</body>

</html>