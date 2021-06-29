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
                        $AddAttr = $ticket->messages->createElement("message", $_POST['message']);
                        $AddAttr->addAttribute("userId", $_SESSION['userid']);//Grab from stored user id
                        date_default_timezone_set("America/Toronto");
                        $AddAttr->addAttribute("postedDate", date('Y-m-d'));
                        $AddAttr->addAttribute("time", date("h:i:sa"));
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
<html lang="en">
    <?php include 'views/head.php'?>
    <body>
    <div class="top">
        <div class="top-wave"></div>
        <h1><i class="fa fa-comments"></i> Support Ticket Sytem</h1>
        <a href="logout.php" class="btn btn-danger float-right" id="move-right-more">Logout</a><a>.</a>
    </div>
        <hr/>
        <div class="contain">
            <a href="ticketlist.php" class="btn btn-danger" id="move-right">Go Back</a>
            <div class="col-md-12" id="move-down">
                <?php foreach($ticketInfo->messages as $ticketCollet) { 
                    foreach($ticketCollet->message as $message){ ?>
                    <div class="message-prompt">
                        <?php foreach($loginXML as $login){ 
                            if((strpos($message->attributes()->userId, $login->userId)) !== false){ echo "<p class='first-p'><strong>".$login->username."</strong>"; if($login->attributes()->type == 'admin'){echo ' (Admin)'; } echo "</p>"; } ?><!--Figure out how to get the user type to show-->
                        <?php } ?>
                        <p class='second-p'><?= $message; ?></p>
                        <p class='third-p'><?= $message->attributes()->postedDate; ?> - <?= $message->attributes()->time; ?></p> 
                    </div> 
                <?php } }?>
                <?php if(((string) $ticketInfo->userId == (string) $userInfo->userId) || $admin) { ?>
                <?php if($ticketInfo->attributes()->status == 'open'){ ?>
                <div class="para-center text-submit">
                    <form action="" method="POST">
                        <textarea class="message-box" name="message" rows="4" cols="50" required></textarea><br/>
                        <input type="submit" class="btn btn-primary" id="text-submit" name="addmessage" value="Post">
                    </form>
                </div>
                <?php } else {echo '<div style="padding: 50px;"></div>';} ?>
            </div>
            <?php } ?>
        </div>
    </body>
    <?php include 'views/footer.php'?>
</html>