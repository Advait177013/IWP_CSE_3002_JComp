<html>
<head>
<link rel="stylesheet" type="text/css" href="projectstyles.css"> 

</head>
<body>
    <p class="right">Welcome User 
        <?php
            //Note: implementing without sessions and password hashing, as should be done ideally. 
            $servername = "localhost";
            $username = "advait_localhost";
            $password = "password";
            $dbname = "jcomp";
            $searchregex = "([stbl]+)"; //show all by default, in case of error
            
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            $generic_error="";
            // Check connection
            if ($conn->connect_error) {
            die("<br>Connection failed: " . $conn->connect_error);
            }
            $custid = $_GET["uid"];
            $sql = "select custname from custdetails where cust_id='$custid'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result)==1)
            {
                $row = $result->fetch_assoc();
                echo $row["custname"];
            }
        ?>
        </p><br>
    <h2>Special Books for You</h2><br>
    <?php
        $sql = "select preferences from Custpref where cust_id='$custid'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)==1)
        {
            $row = $result->fetch_assoc();
            $searchregex = $row["preferences"]; 
        }
        else
        {
            $generic_error = "Error while fetching preference matrix";
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
            }
    ?>

    <!-- create table of books with php -->
    <?php 
        $tablebuilder = "<table><tr><th>Book Name</th><th>Author</th><th>Price</th></tr>";
        $sql = "select bookname, authorname, price from bookdetails where stock > 0 and tags regexp '$searchregex'";
        $result = mysqli_query($conn, $sql);
        if($result -> num_rows > 0)
        {
            while($row = $result->fetch_assoc())
                {
                    $tablebuilder = $tablebuilder."<tr><td>".$row["bookname"]."</td><td>".$row["authorname"]."</td><td>".$row["price"]."</td></tr>";
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

    <h2>Do You Want to Purchase any Book? Click Link Below to Browse</h2>
    <form method="post">
        <input type="submit" name="button1" value="Click Here to head to Buy Page"/>
        <input type="submit" name="button3" value="Click Here to Logout"/>
    </form>

    <?php
        if(isset($_POST['button1'])) {
            header('Location: BookListBuyer.php?uid='.$custid);
        }
        if(isset($_POST['button3'])) {
            header('Location:jcomp-logout.html');
        }
    ?>

    <?php

    $conn->close();
    ?>   
    <span class="error"><?php echo $generic_error?></span>
</body>

</html>