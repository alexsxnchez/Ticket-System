<?php

session_start();

$loginXML = simplexml_load_file("xml/login.xml");
$ticketArray = [];

//Check if the user id is stored in the server 
if(isset($_SESSION['userid'])){
    foreach ($loginXML as $user){
        //Check to see if the stored value matches the xml element
        if($_SESSION['userid'] == $user->userId){
            $admin = false;
            //checking to see if login type is 'admin' 
            foreach($user->attributes() as $type){
                if($type == 'admin'){
                    $admin = true;
                }
            }
            //push to array
            $ticketXML = simplexml_load_file("xml/tickets.xml");
            foreach ($ticketXML as $ticket){
                if($_SESSION['userid'] == $ticket->userId || $admin) {
                    array_push($ticketArray, $ticket);
                }
            }
            //pupdate status
            if (isset($_POST['updatestatus'])) {
                foreach ($ticketXML as $ticket) {
                    if($_POST['ticketId'] == $ticket->ticketId) {
                        $ticket->attributes()->status = $_POST['status'];
                        $ticketXML->saveXML("xml/tickets.xml");  
                        break;
                    }
                }
            }
        }
    }
}

if(isset($_POST['addticket'])){
    
    $category = $_POST['category'];
    
    if(empty($category) || $category == 'ex. return'){
        $errorMsg = "category must not be empty!";
    }
    else {
        //Create DomDocument
        $ticketXML = new DOMDocument();
        //Formats new XML content
        $ticketXML->preserveWhiteSpace = false;
        $ticketXML->formatOutput = true;
        //Loads XML file
        $ticketXML->load("xml/tickets.xml");
        //Create ticket child
        $addTicket = $ticketXML->createElement('ticket');
            //Set attributes status and category child within ticket
            $addTicket->setAttribute('status', 'open');
            $addTicket->setAttribute('category', $category);
        //Create ticket id child
        $addId = $ticketXML->createElement('ticketId', rand(100, 900000));
        //Create issue date child
        date_default_timezone_set("America/Toronto");
        $addDate = $ticketXML->createElement('issueDate', date('Y-m-d'));
        //Create user id child
        $addUserId = $ticketXML->createElement('userId', $_SESSION['userid']);
        //Create messages child
        $addMessages = $ticketXML->createElement('messages');
        //Append all elements
        $addTicket->appendChild($addId);
        $addTicket->appendChild($addDate);
        $addTicket->appendChild($addUserId);
        $addTicket->appendChild($addMessages);

        $ticketXML->documentElement->appendChild($addTicket);
        $ticketXML->save("xml/tickets.xml");

        header('Location: ticketlist.php');
    }
}

// echo '<pre>';
// echo var_dump($tickets);
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'views/head.php'?>
    <body>
    <div class="top">
        <div class="top-wave"></div>
        <h1><i class="fa fa-comments"></i> Support Ticket Sytem</h1>
        <a href="logout.php" class="top__btn">Logout</a><a>.</a>
    </div>
        <div class="para-center">
            <p>Welcome Back <?= $_SESSION['user']; ?></p>
        </div>
        <div class="contain">
            <div class="col-md-12 para-center" id="move-down-less">
                <table class="table table-bordered s">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th>Issue Date</th>
                            <th>Category</th>
                            <th>Status</th>
                            <?php if($admin) { ?>
                            <th>Change Status</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($ticketArray as $ticket) { ?>
                        <tr>
                            <td data-label="Details"><a href="details.php?ticketId=<?= $ticket->ticketId; ?>">Ticket Details</a></td>
                            <td data-label="Issue Date"><?= $ticket->issueDate; ?></td>
                            <td data-label="Category"><?= $ticket->attributes()->category; ?></td>
                            <?php
                                if($ticket->attributes()->status=='open'){
                                    echo '<td data-label="Status" id="open">' .$ticket->attributes()->status. '</td>';
                                } else { echo '<td data-label="Status" id="closed">' .$ticket->attributes()->status. '</td>'; }
                            ?>
                            <?php if($admin) { ?>
                            <td data-label="Change Status">
                                <form action="" method="POST">
                                    <input type="hidden" name="ticketId" value="<?= $ticket->ticketId; ?>">
                                    <select name="status" onclick="return false;">
                                        <option value="open" <?= $ticket->status == "open" ? "selected" :"" ?>>open</option>
                                        <option value="closed" <?= $ticket->status == "closed" ? "selected" :"" ?>>closed</option>
                                    </select>
                                    <input type="submit" value="Update Status" name="updatestatus" class="btr btn btn-default">
                                </form>
                            </td>
                            <?php } ?>  
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php if(!$admin) { ?>
                <div class="contentCenter box-layout">
                    <form action="" method="POST" class="add-form">
                        <p>Fill in the field to create a new ticket</p>
                        <label>Category <span class="asterisk">&ast;</span></label>
                        <input type="text" name="category" value="<?= isset($category)? $category : '';?>" placeholder="ex. return"/><br/>
                        <input type="submit" class="btn btn-access" id="text-submit" name="addticket" value="Create a new ticket">
                        <br/>
                        <span id="spanErrors"><?= isset($errorMsg)? $errorMsg : ''; ?></span>
                    </form>
                </div>
            <?php } ?>  
        </div>
    </body>
    <?php include 'views/footer.php'?>
</html>