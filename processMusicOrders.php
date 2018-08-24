<?php
    extract($_REQUEST);
    $myCon = mysqli_connect("localhost","root","","musicbuydb");
    if(isset($logOut)){
        setcookie("id","",time()-3600);
        setcookie("name","",time()-3600);
        header("location: musicBuyLogin.php");
        exit();
    }
    if(isset($checkOut)){
?>

<html>
    <head>
        <style>
            body{
                text-align: center;
            }
        </style>
    </head>

    <body>
        <h1>Music Buy</h1>
        <h2>Order so far for <?php echo $_COOKIE['name']?></h2>  
        <?php if(empty($cardNumber) || !is_numeric($cardNumber)) { ?> 
        <strong><p>
            <?php echo "PLEASE PRESS BROWSER BACK BUTTON AND RE-ENTER YOUR CREDIT CARD NUMBER";?>
        </p></strong>
        <?php } else { ?>
            <form action="musicBuyLogin.php" method="post">
            <?php 
                if(!isset($_COOKIE["cart"])){
                    echo "<strong><p>Order has ALREADY been processed!!!</p></strong>";
                }
                else{
                    $cart = unserialize($_COOKIE["cart"]);
                    foreach ($cart as $val){
                        $sqlAddDownload = "UPDATE musictbl SET music_no_times = music_no_times + 1
                                            WHERE music_id = $val";
                        mysqli_query($myCon,$sqlAddDownload);
                    }
                    $sqlDel = "DELETE FROM ordertbl WHERE ord_cust_id = $_COOKIE[id]";
                    mysqli_query($myCon,$sqlDel);
                    setcookie("cart","",time()-3600);   
                    echo "<strong>Thank you, </strong>";              
                }    
            ?>
                <strong>Please Close Your Browser to exit
                <p>Or </strong><input type="submit" name="logOut" value="Log Out"></p>
            </form>
        <?php } } mysqli_close($myCon); ?>
    </body>
</html>

