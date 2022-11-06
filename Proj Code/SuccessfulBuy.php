<?php
    $custid = $_GET["uid"];
    $name = $_GET["name"];
    $bname = $_GET["bname"];
    $aname = $_GET["aname"];
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="projectstyles.css"> 

</head>
<body>
    <h1>Congrats on Succesfull purchase</h1>
    <br>
    <p>Thank you <?php echo $name?> for purchasing <?php echo $bname?> by <?php echo $aname?>.</p>
    <br>
    <form method="post">
        <input type="submit" name="button1" value="Click Here to Return to buy Page"/><br>
        <input type="submit" name="button2" value="Click Here to Return to your Home"/><br>
        <input type="submit" name="button3" value="Click Here to Logout"/>
    </form>

    <?php
        if(isset($_POST['button1'])) {
            header('Location: BookListBuyer.php?uid='.$custid);
        }
        if(isset($_POST['button2'])) {
            header('Location: BookCatalogue.php?uid='.$custid);
        }
        if(isset($_POST['button3'])) {
            header('Location:jcomp-logout.html');
        }
    ?>
</body>
</html>
