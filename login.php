<?php

session_start();

$errorMsg = "";
//Get the xml
$loginXML = simplexml_load_file("xml/login.xml");
if(isset($_SESSION['userid']) && isset($_SESSION['user'])){
    header('Location: ticketlist.php');
  }

//Checking login indentifications from the xml
if(isset($_POST['login'])){
    foreach($loginXML as $user){
        //echo md5($_POST['password']);
        $username = $_POST['username'];
        $password = $_POST['password'];
        //Checking if the username input matches the XML element -&- 
        //Checking if the password input matches the XML MD5 encrypted element.
        if($username == $user->username && md5($password) == $user->password->encrypted){
            //Storing both the username and userid as strings.
            $_SESSION['user'] = (string) $user->username;
            $_SESSION['userid'] = (string) $user->userId;
            //Once validated redirect the user to the 'ticketlist.php' page.
            header('Location: ticketlist.php');
        }
        elseif(empty($_POST['username'])){
            $errorMsg = "username must not be empty!";
        }
        elseif(empty($_POST['password'])){
            $errorMsg = "password must not be empty!";
        }
        else {
            $errorMsg = "this username or password doesn't exist";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <!--Links and Scripts-->
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/global.css" rel="stylesheet"/>
    </head>
    <body>
        <h1>Login</h1>
        <hr/>
        <div class="wrapper fadeInDown">
            <div class="formContent" id="centerContent">
                <form action="" method="POST">
                    <div>
                    <input type="text" id="username" name="username" placeholder="username" 
                    value="<?= isset($_POST['username'])? $_POST['username']: '';?>"/>
                    <input type="password" id="password" name="password" placeholder="password" 
                    value="<?= isset($_POST['username'])? $_POST['password']: '';?>"/>
                    <input type="submit" name="login" class="btn-success" value="Login">
                    <br/>
                    <span id="spanErrors"><?=$errorMsg?></span>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <?php include 'footer.php'?>
</html>