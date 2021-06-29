<?php 

session_start();

//Create DomDocument and loads xml file to be read
$loginXML = new DOMDocument();

//Loads XML file
$loginXML->load("xml/login.xml");

if(isset($_SESSION['userid']) && isset($_SESSION['user'])){
    header('Location: ticketlist.php');
}

//Checking user indentifications from the xml
if(isset($_POST['adduser'])){
    //echo md5($_POST['password']);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if(empty($firstname)){
        $errorMsg = "first name must not be empty!";
    }
    elseif(empty($lastname)){
        $errorMsg = "last name must not be empty!";
    }
    elseif(empty($username)){
        $errorMsg = "username must not be empty!";
    }
    elseif(empty($email)){
        $errorMsg = "email must not be empty!";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorMsg= "please enter a valid email";
    }
    elseif(empty($password)){
        $errorMsg = "password must not be empty!";
    }
    else {
        //Create DomDocument
        $loginXML = new DOMDocument();
        //Formats new XML content
        $loginXML->preserveWhiteSpace = false;
        $loginXML->formatOutput = true;
        //Loads XML file
        $loginXML->load("xml/login.xml");

        //Create user and type attribute
        $AddUser = $loginXML->createElement('user');
        $AddUser->setAttribute('type', 'client');
        //Create user id child
        $userId = rand(4000, 90000);
        $AddId = $loginXML->createElement('userId', $userId);//Make into random number 
        //Create name child
        $AddName = $loginXML->createElement('name');
            //Create first and last child within name
            $AddFirst = $loginXML->createElement('first', $firstname);
            $AddLast = $loginXML->createElement('last', $lastname);
            $AddName->appendChild($AddFirst);
            $AddName->appendChild($AddLast);
        //Create username child
        $AddUsername = $loginXML->createElement('username', $username);
        //Create password child
        $AddPass = $loginXML->createElement('password');
            //Create encrypted child within password
            $AddEnc = $loginXML->createElement('encrypted', md5($password));
            $AddPass->appendChild($AddEnc);
        //Create email child
        $AddEmail = $loginXML->createElement('email', $email);
        //Append all elements
        $AddUser->appendChild($AddId);
        $AddUser->appendChild($AddName);
        $AddUser->appendChild($AddUsername);
        $AddUser->appendChild($AddPass);
        $AddUser->appendChild($AddEmail);

        $loginXML->documentElement->appendChild($AddUser);
        $loginXML->save("xml/login.xml");

        //Save username and id into a session
        $_SESSION['user'] = (string) $username;
        $_SESSION['userid'] = (string) $userId;
        //Once validated redirect the user to the 'ticketlist.php' page.
        header('Location: ticketlist.php');
    }
}

?>
<!DOCTYPE html>
<html>
    <?php include 'views/head.php'?>
    <body>
    <div class="top">
        <div class="top-wave"></div>
        <h1><i class="fa fa-comments"></i> Support Ticket Sytem</h1>
        <a href="login.php" class="btn btn-danger float-right" id="move-right-more">Go Back</a><a>.</a>
    </div>
        <hr/>
        <div class="contentCenter box-layout">
            <form action="" method="POST" class="add-form">
                <!-- <div id="radio-selection">
                    <p>Please select your corresponding user type<p>
                    <input type="radio" name="type" id="type-client" value="client" />
                    <label for="type-client">CLient</label>

                    <input type="radio" name="type" id="type-admin" value="admin" />
                    <label for="type-admin">Admin</label>
                    <?php ?><!--Check for user type--
                </div>-->
                <p>Fields marked with an <span class="asterisk">&ast;</span> are required</p>
                <label>First Name <span class="asterisk">&ast;</span></label>
                <input type="text" name="firstname" value="<?= isset($firstname)? $firstname : '';?>"/><br/>
                <label>Last Name <span class="asterisk">&ast;</span></label>
                <input type="text" name="lastname" value="<?= isset($lastname)? $lastname : '';?>"/><br/>
                <label>Userame <span class="asterisk">&ast;</span></label>
                <input type="text" name="username" value="<?= isset($username)? $username : '';?>"/><br/>
                <label>Email <span class="asterisk">&ast;</span></label>
                <input type="text" name="email" value="<?= isset($email)? $email : '';?>"/><br/>
                <label>Password <span class="asterisk">&ast;</span></label>
                <input type="password" name="password" value="<?= isset($password)? $password : '';?>"/><br/>
                <input type="submit" class="btn btn-access" id="text-submit" name="adduser" value="Register">
                <br/>
                <span id="spanErrors"><?= isset($errorMsg)? $errorMsg : ''; ?></span>
            </form>
        </div>
    </body>
    <?php include 'views/footer.php'?>
</html>