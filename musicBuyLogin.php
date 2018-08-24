<?php
    $error = array();
    $error[0] = "";
    $error[1] = "";
    $error[2] = "";
    $myCon = mysqli_connect("localhost","root","","musicbuydb");
    
    if(isset($_POST["logOut"])){
        setcookie("id","",time()-3600);
        setcookie("name","",time()-3600);
    }
    else{

    
        if(!empty($_REQUEST)){
            extract($_REQUEST);

            function validateInput (){
                global $error;
                global $lName;
                global $password;
                if (trim($lName) == "")
                $error[0] = "***Your last name?***";
                else if(strlen($lName) > 20)
                $error[0] = "***Your last name has TOO many characters?***";
                else $error[0] = "";
                
                if (trim($password) =="")
                $error[1] = "***Your password?***" ;
                else if(strlen($password) != 7)
                $error[1] = "***Your password MUST HAVE 7 characters?***";
                else $error[1] = "";
            }
            
            function logIn(){
                global $error;
                global $myCon;
                global $password;
                global $lName;
                validateInput();
                if($error[0]=="" && $error[1]==""){
                    if(mysqli_connect_errno()){
                        printf("Connection fail: %s\n",mysqli_connect_error());
                        exit();
                    }
                    else {
                        $sql = "Select * from customertbl WHERE cust_lname = '$lName' and cust_passw = '$password'";
                        $res = mysqli_query($myCon,$sql);
                        $record = mysqli_fetch_assoc($res);
                        if($res !== FALSE){
                            if(mysqli_num_rows($res) == 0 || strcmp($record["cust_passw"],$password) != 0) {
                                $error[2] ="***Your password DO NOT MATCH. Please Re-enter***";
                            }
                            else{
                                setcookie("id",$record["cust_id"]);
                                setcookie("name",$record["cust_fname"]." ".$record["cust_lname"]);
                                header("location: titleSrch.php");
                                exit();
                            }
                        }
                        else {
                            echo "Problem".mysqli_error($myCon);
                        }
                        mysqli_close($myCon);
                    }    
                }
            }

            if(isset($submit)){
                logIn();
            }

            if(isset($newMember)){
                header("location: addNewCust.php");
                exit();
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
            label{
                font-weight: bold;
            }
            table{
                width: 900px;
                margin: auto;
            }
            .first-col{
                width: 350px;
                text-align: right;
                padding-right: 10px;
            }
            .second-col{
                width: 200px;
            }
            .third-col{
                text-align: left;
            }
            .error{
                color: red;
            }
        </style>
    </head>

    <body>
        <h1>Music Buy</h1>
        <h2>Member login</h2>   
        <form action="<?php echo ($_SERVER['PHP_SELF']);?>" method="POST">
            <table>
                <tr>
                    <td class="first-col"><label for="lName">Enter Your Last Name (MAX: 20 characters)</label></td> 
                    <td class="second-col"><input type="text" name="lName" id="lName" size="20" 
                        value = "<?php if(isset($_POST['submit'])) echo $_POST['lName']; else echo ''; ?>"
                        /></td>
                    <td class="third-col">
                        <?php echo "<p class='error'>".$error[0]."</p>"; ?>
                    </td>
                </tr>
                <tr>
                    <td class="first-col"><label for="password">Enter Your Password (7 characters)</label></td>
                    <td class="second-col"><input type="password" name="password" id="password" size="7"
                        value="<?php if(isset($_POST['submit'])) echo $_POST['password']; else echo '';?>"
                        ></td>
                    <td class="third-col">
                        <?php echo "<p class='error'>".$error[1]."</p>"; ?>
                    </td>
                </tr>
                <tr>
                    <td class="first-col">&nbsp;</td>
                    <td class="second-col">
                        <input type="submit" value="Login" name="submit">&nbsp;&nbsp;
                        <input type="reset" value="Clear" name="reset">
                    </td class="third-col">
                </tr>
            </table>
            <p><?php echo "<p class='error'>".$error[2]."</p>"; ?></p>
            <p>
                <label style="color:blue">For New Members, Please login here</label>
                <input type="submit" name ="newMember" value="New Member">                  
            </p>
        </form>    
    </body>
</html>
