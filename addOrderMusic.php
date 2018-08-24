<?php
    extract($_REQUEST);
    $myCon = mysqli_connect("localhost","root","","musicbuydb");
    if(isset($submit)){
        if(!empty($addCart)){
            if(isset($_COOKIE["cart"])){
                $oldCart = unserialize($_COOKIE["cart"]);
                if($addCart != $oldCart){
                    $addCart = array_unique(array_merge($addCart,$oldCart));
                }
            }
            setcookie("cart",serialize($addCart));

            $sqlDel = "DELETE FROM ordertbl WHERE ord_cust_id = $_COOKIE[id]";
            mysqli_query($myCon,$sqlDel);

            $sqlgetPrice = "SELECT musictbl.music_id, music_data.m_price  FROM music_data, musictbl WHERE musictbl.music_type = music_data.m_type
                     AND musictbl.music_id IN(".implode(",",$addCart).")";
            $resgetPrice = mysqli_query($myCon,$sqlgetPrice);
            
            $prices = array();
            if(mysqli_num_rows($resgetPrice) != 0){
                for ($row = 1; $row <= mysqli_num_rows($resgetPrice); $row++){ 
                    $record = mysqli_fetch_assoc($resgetPrice);
                    $prices[$record['music_id']] = $record['m_price'];
                }
            } 
            foreach ($addCart as $items){
                $sqlInsert = "INSERT INTO ordertbl(ord_cust_id, ord_music_id,ord_date_added,ord_price)
                        VALUES('$_COOKIE[id]',$items,now(),$prices[$items])";
                mysqli_query($myCon,$sqlInsert);
            }  
        }
    }       
?>

<html>
    <head>
        <style>
            body{
                text-align: center;
            }
            table{
                width: 900px;
                margin: auto;
                border: 1px solid;
                text-align: center;
            }
            td{
				border: 1px solid;
			}
            thead{
                font-weight: bold;
            }
            td:first-child{
                width: 350px;
            }
            .c{
                background: green;
                color: yellow;
            }
            .p{
                background: red;
                color: white;
            }
            .j{
                background: blue;
                color: white;
            }
            .image img{
                width: 60px;
                height: 40px; 
            }
            .image{
                width: 170px;
            }
            .idNumber{
                width: 60px;
            }
            .price{
                width: 120px;
            }
            .time{
                width: 200px;
            }
        </style>
    </head>

    <body>
        <h1>Music Buy</h1>
        <h2>Order so far for <?php echo $_COOKIE['name']?></h2>   
        <form action="processMusicOrders.php" method="post">
            <?php if(empty($addCart))
                echo "You have NOT selected anything!, 
                BUT below are your current selections in your CART from before";
            ?>
            <table>
                <thead>
                    <tr>
                        <td>Title</td>
                        <td>Id</td>
                        <td>Type</td>
                        <td>Date Added<br />(mm-dd-yy)</td>
                        <td>Price</td>
                    </tr>
                </thead>
                <tbody>
                    <?php      
                        $sql = "SELECT * FROM ordertbl, musictbl,music_data
                                WHERE (ord_cust_id = $_COOKIE[id])
                                AND (ordertbl.ord_music_id = musictbl.music_id)
                                AND (musictbl.music_type = music_data.m_type) 
                                ORDER BY musictbl.music_title";
                        $res = mysqli_query($myCon,$sql);
                        $sum = 0;
                        for ($row = 1; $row <= mysqli_num_rows($res); $row++){
                            $record = mysqli_fetch_assoc($res);
                            $sum += $record['ord_price'];
                    ?>
                        <tr>
                            <td class="<?php echo $record['music_type']?>"><?php echo $record['music_title'] ?></td>
                            <td class ="idNumber"><?php echo $record['ord_music_id']?></td>
                            <td class="image"><img src="<?php echo $record['m_icon']?>" alt="" ></td>
                            <td class="time"><?php echo date('m-d-Y',strtotime($record['ord_date_added']))?></td>
                            <td class="price"><?php echo $record['ord_price']?></td>
                        </tr>
                        <?php  } ?>
                        <tr>
                            <td colspan="4" style="font-weight: bold; text-align: right">Total:</td>
                            <td style="font-weight: bold"><?php echo "$".$sum;?></td>
                        </tr>
                </tbody>
            </table>
            <br />
            <?php if(!empty($addCart) || isset($_COOKIE["cart"])){ ?>
            <p>Enter your Credit Card Number: <input type="password" name="cardNumber" id=""></p>
            <input type="submit" name="checkOut" value="CheckOut">
            Or <input type="submit" name="logOut" value="Log Out">
            <?php }
                else echo "<p>PLEASE CLICK BROWSER BACK BUTTON TO RETRY</p>";
                mysqli_close($myCon);
            ?>
        </form>       
    </body>
</html>

