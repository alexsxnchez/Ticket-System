<?php

session_start();

$loginXML = simplexml_load_file("xml/login.xml");
//variables
$userInfo;
$ticketInfo;

//Check to see if user id is stored in the server
if(isset($_SESSION['userid'])){
    foreach ($loginXML as $user){
        //check to see if the stored value matches the xml element
        if($_SESSION['userid'] == $user->userId){
            $userInfo = $user;
            //check to see if the type of user is an 'admin' 
            foreach($userInfo->attributes() as $key => $value){
                if($value == 'admin'){
                    $admin = true;
                }
            }
            $ticketXML = simplexml_load_file("xml/tickets.xml");
            foreach ($ticketXML as $ticket){
                if($_GET['ticketId'] == $ticket->ticketId) {
                    $ticketInfo = $ticket;
                    if(isset($_POST['addmessage'])) {
                        //add new children/attributes to messages
                        $AddAttr = $ticket->messages->addChild("message", $_POST['message']);
                        $AddAttr->addAttribute("userId", $_SESSION['userid']);//Grab from stored user id
                        $AddAttr->addAttribute("postedDate", date('Y-m-d'));
                        $AddAttr->addAttribute("time", date("h:i:s A"));
                        //save all inside the xml file
                        $ticketXML->saveXML("xml/tickets.xml");
                        false;
                    }  
                }
            }
        }
    }
} else {
    header('Location: login.php');
}

//var_dump($ticketInfo->messages);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ticket Detail</title>
        <!--Links and Scripts-->
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/global.css" rel="stylesheet"/>
    </head>
    <body>
        <h1>Ticket Messages</h1>
        <hr/>
        <a href="ticketlist.php" class="btn btn-danger" id="move-right">Go Back</a>
        <div class="col-md-12" id="move-down">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Messages</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ticketInfo->messages as $ticketCollet) { 
                        foreach($ticketCollet->message as $message){ ?>
                        <tr>
                            <td><?= $message->attributes()->userId; ?></td><!--Figure out how to get the user type to show-->
                            <td><?= $message->attributes()->postedDate; ?></td>
                            <td><?= $message->attributes()->time; ?></td>
                            <td><?= $message; ?></td>
                        </tr>    
                    <?php } }?>
                </tbody>
            </table>
            <?php if(((string) $ticketInfo->userId == (string) $userInfo->userId) || $admin) { ?>
            <div class="para-center text-submit">
                <form action="" method="POST">
                    <textarea class="message-box" name="message" rows="4" cols="50" required></textarea><br/>
                    <input type="submit" class="btn btn-primary" id="text-submit" name="addmessage" value="Post">
                </form>
            </div>
            <?php } ?>
        </div>
    </body>
    <?php include 'footer.php'?>
</html>