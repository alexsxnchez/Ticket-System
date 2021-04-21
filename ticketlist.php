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

// echo '<pre>';
// echo var_dump($tickets);
// echo '</pre>';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ticket List</title>
        <!--Links and Scripts-->
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/global.css" rel="stylesheet"/>
    </head>
    <body>
        <h1>Tickets In The System</h1>
        <div class="para-center">
            <p>Welcome Back <?= $_SESSION['user']; ?></p>
        </div>
        <hr/>
        <a href="logout.php" class="btn btn-danger float-right" id="move-right-more">Logout</a><a>.</a>
        <div class="col-md-12 para-center" id="move-down-less">
            <table class="table table-bordered">
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
                        <td><a href="details.php?ticketId=<?= $ticket->ticketId; ?>">Details</a></td>
                        <td><?= $ticket->issueDate; ?></td>
                        <td><?= $ticket->attributes()->category; ?></td>
                        <?php
                            if($ticket->attributes()->status=='open'){
                                echo '<td id="open">' .$ticket->attributes()->status. '</td>';
                            } else { echo '<td id="closed">' .$ticket->attributes()->status. '</td>'; }
                        ?>
                        <?php if($admin) { ?>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="ticketId" value="<?= $ticket->ticketId; ?>">
                                <select name="status" onclick="return false;">
                                    <option value="open" <?= $ticket->status == "open" ? "selected" :"" ?>>open</option>
                                    <option value="closed" <?= $ticket->status == "closed" ? "selected" :"" ?>>closed</option>
                                </select>
                                <input type="submit" value="Update Status" name="updatestatus" class="btn btn-default">
                            </form>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
    <?php include 'footer.php'?>
</html>