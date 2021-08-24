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
            exit;
        }
        elseif(empty($username)){
            $errorMsg = "username must not be empty!";
        }
        elseif(empty($password)){
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
    <?php include 'views/head.php'?>
    <body>
        <div class="top">
            <div class="top-wave"></div>
            <h1><i class="fa fa-comments"></i> Support Ticket Sytem</h1>
        </div>
        <div class="page__container">
            <div class="formContent centerContent">
                <form action="" method="POST" class="add-form">
                    <div>
                    <input type="text" name="username" placeholder="username" 
                    value="<?= isset($_POST['username'])? $_POST['username']: '';?>"/>
                    <input type="password" name="password" placeholder="password" 
                    value="<?= isset($_POST['username'])? $_POST['password']: '';?>"/>
                    <input type="submit" name="login" class="btn-access" value="Login">
                    <br/>
                    <span id="spanErrors"><?=$errorMsg?></span>
                    </div>
                </form>
                <a href="addUser.php" class="add-link">Create A New User</a>
            </div>
        </div>
    </body>
    <?php include 'views/footer.php'?>
</html>