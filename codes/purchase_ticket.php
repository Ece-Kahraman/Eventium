<?php
session_start();
require('connection.php');


$userID = $_SESSION['user_id'];
$eventID = $_SESSION['event_id'];
$price = $_POST['btn'];
$amount = $_POST['amount'];

if ($_POST['refundable'] == "TRUE") {
    $refund = 1;
} else if ($_POST['refundable'] == "FALSE") {
    $refund = 0;
}


$total = (float) $price * (float) $amount;
$query = $connection->query("SELECT balance FROM wallet WHERE wallet_id = ( SELECT wallet_id FROM has WHERE user_id = '$userID' )");
$balance = ($query->fetch_assoc())['balance'];
if ($balance < $total) {
    echo "<script type='text/javascript'>alert('Please increase your balance!');</script>";
    echo "<script>window.location = 'wallet.php';</script>";
} else {
    $newBalance = $balance - $total;
    $connection->query("UPDATE wallet SET balance = '$newBalance' WHERE wallet_id = ( SELECT wallet_id FROM has WHERE user_id = '$userID' )");

    for ($i = 1; $i <= $amount; $i++) {
        if ($stmt = $connection->prepare("INSERT INTO ticket VALUES (NULL, ?, ?, ? )")) {
            $stmt->bind_param("iii", $refund, $price, $eventID);
            if ($stmt->execute()) {
                $anan = "SELECT ticket_id FROM ticket ORDER BY ticket_id DESC LIMIT 1";
                $ticketID = ($connection->query($anan))->fetch_assoc()['ticket_id'];
                $stmt2 = $connection->prepare("INSERT INTO purchase (`ticket_id`, `user_id`) VALUES (?, ?)");
                $stmt2->bind_param("ii", $ticketID, $userID);
                if ($stmt2->execute()) {
                    echo "<script type='text/javascript'>alert('ticket '".$ticketID."' is purchased!');</script>";
                }
            }
        }
        
        
    }
    

    $query = $connection->query("SELECT event_quota FROM event WHERE event_id = '$eventID'");
    $quota = ($query->fetch_assoc())['event_quota'];
    $newQuota = $quota - $amount; 
    $connection->query("UPDATE event SET event_quota = '$newQuota' WHERE event_id = '$eventID'");

    
    echo "<script>window.location = 'participant_home.php';</script>";
}

?>