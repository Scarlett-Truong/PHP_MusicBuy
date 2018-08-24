<html>
    <head>
        <style>
            body{
                text-align: center;
            }
            table{
                width: 750px;
                margin: auto;
                border: 1px outset;
            }
            td{
				border: 1px inset;
			}
            tr:first-child{
                height: 50px;
            }
            .first-col{
                width: 70px;
                text-align: right;
                font-weight: bold;
            }
            #searchBy{
                font-weight: bold;
            }
            .center{
                text-align: center;
            }
            .right{
                text-align: right;
            }
        </style>
    </head>

    <body>
        <h1>Music Buy</h1>
        <h3>
            <?php 
                if(isset($_COOKIE["name"])) echo "Welcome ".$_COOKIE["name"];
            ?>
        </h3>
        <h2>Title search</h2>   
        <form action="fetchDisplayMusic.php" method="POST">
            <table>
                <tr>
                    <td class="first-col">Title</td>
                    <td colspan="3" class="center">
                        <input type="text" name="srchName" size="30"/>
                    </td>
                    <td class="center">
                        <input type="submit" value="Search">
                    </td>
                </tr>
                <tr>
                    <td rowspan="3"></td>
                    <td id="searchBy" class="right">Search By:</td>
                    <td id="searchBy"><input type="radio" name="searchBy" checked value="withinTitle">Within Title</td>
                    <td rowspan="3">
                        <input type="checkbox" name="types[]" value="pop" id="">Pop<br />
                        <input type="checkbox" name="types[]" value="country" id="">Country<br />
                        <input type="checkbox" name="types[]" value="jazz" id="">Jazz<br />
                        <b>All Types (If NO check box is selected)</Td></b>
                    </td>
                    <td rowspan="3"></td>
                </tr>
                <tr>
                    <td rowspan="2" class="right">
                        <select name="orderBy">
                            <option value="title" selected>Order by Title (a-z)</option>
                            <option value="musicType">Order by Music Type</option>
                            <option value="popularity">Order by Popularity</option>
                            <option value="goToCart">Go to Cart</option>
                        </select>
                    </td>
                    <td id="searchBy"><input type="radio" name="searchBy"value="startWith" >Starting with</td>                    
                </tr>
                <tr>
                    <td id="searchBy"><input type="radio" name="searchBy" value="exactTitle" >Exact Title</td>
                </tr>              
            </table>
            <p><input type="reset" value="Clear"></p>
        </form>    
    </body>
</html>
