<?php
require('connection.php');    
session_start();
$id = $_SESSION['user_id'];
$sql1 = "SELECT organization_name, organizer_popularity FROM organizer NATURAL JOIN verified_organizer WHERE user_id = '$id'";
$query1 = $connection->query($sql1);
$result1 = $query1->fetch_assoc();

$sql2 = "SELECT event_title, event_date, event_location, event_quota
         FROM event E
         WHERE E.user_id = '$id' and E.event_date > CURRENT_TIMESTAMP";

$query2 = $connection->query($sql2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verified Organizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="organizer-style.css">
</head>
<body>
<div class="header row align-items-center" style="background-color: deeppink">
    <div class="col-8 my-header" style="font-size: 30px;" >
        <?php echo $result1['organization_name']; ?>
    </div>

    <div class="col-1 mt-3">
        Popularity: <?php echo $result1['organizer_popularity']; ?>
    </div>
    <div class="col-2 mt-3">
        IBAN
    </div>

    <div class="col-1 mt-3">
        <a href="./logout.php"><img class="image-back" src="icons/arrow-right-circle.svg" alt="back"/></a>
    </div>
</div>

<div class="span-style">Your Ongoing Events</div>


<div class="table-container">

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Title</th>
            <th scope="col">Date</th>
            <th scope="col">Location</th>
            <th scope="col">Participants</th>
            <th scope="col"> </th>
        </tr>
        </thead>
        <tbody>
        <?php while ($result2 = $query2->fetch_assoc()) { ?>
            
            <tr>
                <th scope="row"><?php echo $result2['event_title'];?></th>
                <td><?php echo $result2['event_date'];?></td>
                <td><?php echo $result2['event_location'];?></td>
                <td><?php echo $result2['event_quota'];?></td>
                <td>
                    <button class="edit" onclick="window.location.href='./edit_event_v.php';">edit</button>
                    <button class="view" onclick="window.location.href='./view_participants_v.php';">view participants</button>
                    <button class="cancel" onclick="window.location.href='./cancel_v.php';">cancel</button>
                </td>
            </tr>
                
        <?php } ?>
        </tbody>
    </table>



</div>


<div class="organize-btn-container">
    <button id="pink-btn" type="button" class="btn pink" >Organize A New Event!</button>
</div>
</body>
</html>
