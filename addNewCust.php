<?php
    $error = array();
    $error[0] = "";
    $error[1] = "";
    $error[2] = "";
    $error[3] = "";
    $has_error = false;
    $myCon = mysqli_connect("localhost","root","","musicbuydb");
    
    if(!empty($_REQUEST)){
        extract($_REQUEST);
        $fName = trim($fName);
        $lName = trim($lName);
        $email = trim($email);
        $password = trim($password);
        
        if(isset($submit)){
            
            if ($fName == "")
                $error[0] = "***Your first name?***";
            else if(strlen($fName) > 20)
                $error[0] = "***Your first name has TOO many characters?***";
            else $error[0] = "";
                
            if ($lName == "")
                $error[1] = "***Your last name?***";
            else if(strlen($lName) > 20)
                $error[1] = "***Your last name has TOO many characters?***";
            else $error[1] = "";
                
            if ($email == "")
                    $error[2] = "***Your e-mail?***";
            else if(strlen($email) > 20)
                    $error[2] = "***Your e-mail has TOO many characters?***";
            else $error[2] = "";
                
            if ($password =="")
                    $error[3] = "***Your password?***" ;
            else if(strlen($password) != 7)
                    $error[3] = "***Your password MUST HAVE 7 characters?***";
            else if(ctype_upper(substr($password,0,1)))
                    $error[3] = "***Invalid character***";
            else if (is_numeric($password))
                    $error[3] = "***Your password cannot be numeric***";
            else if ($lName != "" && $password != ""){
                $sql = "Select * from customertbl WHERE cust_lname = '$lName' and cust_passw = '$password'";
                $res = mysqli_query($myCon,$sql);
                $record = mysqli_fetch_assoc($res);
                    
                if (strcmp($record["cust_lname"],$lName) == 0 && strcmp($record["cust_passw"],$password) == 0)
                    $error[3]= "***Password is prohibited, please Re-enter***";
                else $error[3] = "";
                }
         
            if($error[0] == "" && $error[1]=="" && $error[2]=="" && $error[3]==""){
                $sql2 = "Insert into customertbl(cust_fname, cust_lname,cust_email,cust_passw) 
                        values('$fName','$lName','$email','$password')";
                $res2 = mysqli_query($myCon,$sql2);
                if($res2 !== FALSE){
                    setcookie("id", mysqli_insert_id($myCon) );
                    setcookie("name",$fName." ".$lName);
                    header("location: titleSrch.php");
                    exit();
                }
                else echo "Problem ".mysqli_error($myCon);
            }
            mysqli_close($myCon);
        }
    }

    if (!empty($error[0]) || !empty($error[1]) || !empty($error[2]) || !empty($error[3]))
        $has_error = true;
?>

<html>
    <head>
        <style>
            body{
                text-align: center;
            }
            table{
                width: 850px;
                margin: auto;
                border: 1px outset;
            }
            td{
				border: 1px inset;
			}
            .first-col{
                width: 350px;
                text-align: right;
                padding-right: 10px;   
            }
            .third-col{
                width: 300px;
                text-align: left;
            }
            .error{
                color: red;
            }
            ul{
                list-style-position: inside;
            }
        </style>
    </head>

    <body>
        <h1>Music Buy</h1>
        <h2>Member login</h2>   
        <form action="<?php echo ($_SERVER['PHP_SELF']);?>" method="POST">
            <table>
                <!-- First name -->
                <tr>
                    <td class="first-col"><label for="fName">
                        Enter Your <b>First Name</b> (MAX 20 chars.)</label>
                    </td> 
                    <td class="second-col">
                        <input type="text" name="fName" id="fName" size="20" 
                        value = "<?php if(isset($_POST['submit'])) echo $_POST['fName']; else echo ''; ?>"
                        />
                    </td>
                    <?php if ($has_error){ ?>
                        <td class="third-col">
                            <?php echo "<p class='error'>".$error[0]."</p>"; ?>
                        </td>
                    <?php } ?>
                </tr>
                <!-- Last name -->
                <tr>
                    <td class="first-col">
                        <label for="password">Enter Your <b>Last Name</b> (MAX 20 chars.)</label>
                    </td>
                    <td>
                        <input type="text" name="lName" id="lName" size="20"
                        value="<?php if(isset($_POST['submit'])) echo $_POST['lName']; else echo '';?>"
                        />
                    </td>
                    <?php if ($has_error){ ?>
                        <td class="third-col">
                            <?php echo "<p class='error'>".$error[1]."</p>"; ?>
                        </td>
                    <?php } ?>
                </tr>
                <!-- Email -->
                <tr>
                    <td class="first-col">
                        <label for="email">Your <b>e-mail</b> address (MAX 20 chars.)</label>
                    </td>
                    <td class="second-col">
                        <input type="text" name="email" id="email" size="20"
                        value="<?php if(isset($_POST['submit'])) echo $_POST['email']; else echo '';?>"
                        />
                    </td>
                    <?php if ($has_error){ ?>
                    <td class="third-col">
                        <?php echo "<p class='error'>".$error[2]."</p>"; ?>
                    </td>
                    <?php } ?>
                </tr>
                <!-- Password -->
                <tr> 
                    <td class="first-col">
                        <label for="password">Your <b>password</b>
                            <ul>
                                <li>MUST BE 7 CHARACTERS</li>
                                <li><b>CANNOT</b> BE ALL DIGITS</li>
                                <li><b>MUST BEGIN</b> with lowercase LETTER of the alphabet</li>
                                <li><b>ONLY lowercase LETTERS OF THE ALPHABET ALLOWED</b></li>
                            </ul>
                        </label>
                    </td>
                    <td class="second-col">
                        <input type="text" name="password" id="password" size="7"
                        value="<?php if(isset($_POST['submit'])) echo $_POST['password']; else echo '';?>"
                        />
                    </td>
                    <?php if ($has_error) { ?>
                        <td class="third-col">
                            <?php echo "<p class='error'>".$error[3]."</p>"; ?>
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td class="first-col">&nbsp;</td>
                    <td class="second-col">
                        <input type="submit" value="Submit" name="submit">    	
                    </td>
                </tr>
            </table>
        </form>    
    </body>
</html>
