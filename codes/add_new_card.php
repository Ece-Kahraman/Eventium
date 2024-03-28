<?php
//ehuehue
require('connection.php');    
session_start();
$id = $_SESSION['user_id'];
$sql1 = "SELECT wallet_id FROM has WHERE user_id = '$id'";
$query1 = $connection->query($sql1);
$result1 = $query1->fetch_assoc();
$wallet_id = $result1['wallet_id'];

if(isset($_POST['no']) && isset($_POST['name']) && isset($_POST['date']) && isset($_POST['cvc'])){

    if ($stmt = $connection->prepare("INSERT INTO card VALUES ('$wallet_id', ?, ?, ?, ?)")) {

        $stmt->bind_param("isis", $_POST['no'], $_POST['name'], $_POST['cvc'], $_POST['date']);

        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Addition is successful!');</script>";
            echo "<script>window.location = 'wallet.php';</script>";
        } else {
            echo "<script type='text/javascript'>alert('exe unsuccessful!');</script>";
            echo "<script>window.location = 'wallet.php';</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('prep unsuccessful!');</script>";
        echo "<script>window.location = 'wallet.php';</script>";
    }

} else {
    echo "<script type='text/javascript'>alert('post unsuccessful!');</script>";
    echo "<script>window.location = 'wallet.php';</script>";
}

$connection->close();

?>
