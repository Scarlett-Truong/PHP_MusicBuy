<?php
    extract($_REQUEST);
    $myCon = mysqli_connect("localhost","root","","musicbuydb");
    $sql = "SELECT * FROM musictbl, music_data 
            WHERE musictbl.music_type = music_data.m_type";
    $sqlarr = array();
    if(isset($types)){
        foreach ($types as $val ){
            array_push($sqlarr,"music_type = '".substr($val,0,1)."'");
        }
        $sql = $sql." AND (".implode(" OR ",$sqlarr).")";
    }
    
    if ($srchName !=""){
        if(isset($searchBy)){
            switch($searchBy){
                case "withinTitle":
                    $sql = $sql." AND music_title LIKE '%$srchName%'";
                    break;
                case "startWith":
                    $sql = $sql. " AND music_title LIKE '$srchName%'";
                    break;
                case "exactTitle":
                    $sql = $sql. " AND music_title = '$srchName'";
                    break;
            }
        }
    }
 
    if(isset($orderBy)){
        switch($orderBy){
            case "title" : 
                $sql = $sql." ORDER BY music_title";
                break;
            case "musicType":
                $sql = $sql." ORDER BY music_type";
                break;
            case "popularity":
                $sql = $sql." ORDER BY music_no_times DESC, music_title ASC";
                break;
            case "goToCart": 
                header("location:addOrderMusic.php");
                exit();
                break;
        }
    }

    $res = mysqli_query($myCon,$sql);
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
            .image,.download{
                width: 150px;
            }
            .idNumber{
                width: 40px;
            }
            .price{
                width: 100px;
                background: orange;
            }
            .download{
                background: yellow;
            }
        </style>
    </head>

    <body>
        <h1>Music Buy</h1>
        <h2>Title search Results</h2>   
        <form action="addOrderMusic.php" method="post">
            <table>
                <thead>
                    <tr>
                        <td>Title</td>
                        <td>Type</td>
                        <td>Id</td>
                        <td>Downloaded</td>
                        <td>Price</td>
                        <td>Add to <br />Cart</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        global $addCart;
                        if(mysqli_num_rows($res) != 0){       
                            for ($row = 1; $row <= mysqli_num_rows($res); $row++){ 
                                $record = mysqli_fetch_assoc($res);                     
                    ?>
                        <tr>
                            <td class="<?php echo $record['music_type']?>"><?php echo $record['music_title'] ?></td>
                            <td class="image">
                                <img src="<?php echo $record['m_icon']?>" alt="" >
                            </td>
                            <td class ="idNumber"><?php echo $record['music_id']?></td>
                            <td class="download"><?php echo $record['music_no_times']?></td>
                            <td class="price"><?php echo $record['m_price']?></td>
                            <td>
                            <input type="checkbox" name="addCart[]" value="<?php echo $record['music_id']?>"></td>
                        </tr>
                    <?php        
                            }            
                        } mysqli_close($myCon);
                    ?>
                </tbody>
            </table>
            <br />
            <input type="submit" name="submit" value="Submit">
            <input type="reset" value="Clear">
        </form>       
    </body>
</html>

