<?php
    include "connection.php";
    if (! isset($_COOKIE['user_id'])) 
        {
        header("Location: login.php");
        exit;
        }
    $user_id = $_COOKIE['user_id'];
    $sql     = "SELECT * FROM user WHERE id = '$user_id'";
    $result  = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
        {
        $user = mysqli_fetch_assoc($result);
        } 
    else {
        echo "User not found";
        exit;
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="s.css">
</head>

<body>
    <div class="container">
        <h2>My Profile</h2>
        <p>
            <b>Name:</b>
            <?php echo $user['name']; ?>
        </p>
        <p>
            <b>Email:</b>
            <?php echo $user['email']; ?>
        </p>
        <p>
            <b>Mobile:</b>
            <?php echo $user['mobile']; ?>
        </p>
        <a href="home.php">Home</a>
        <hr>
    <div class="mail-columns">

        <div class="mail-column">

            <h2>Received Mail</h2>

            <div id="received-mail">
                Loading...
            </div>

        </div>


        <div class="mail-column">

            <h2>Sent Mail</h2>

            <div id="sent-mail">
                Loading...
            </div>

        </div>

    </div>
        <br>
        <a href="mail.php">Send New Mail</a>
        <br><br>
        <a href="logout.php">Logout</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

$(document).ready(function () {

    loadMails();

    function loadMails() {

        $.ajax({

            url: "mail_data.php",
            type: "POST",
            dataType: "json",

            success: function (response) {

                if (response.status == "success") {

                    $("#received-mail").html(response.received);
                    $("#sent-mail").html(response.sent);

                } else {

                    $("#received-mail").html("Unable to load mails");
                    $("#sent-mail").html("Unable to load mails");

                }

            },

            error: function () {

                $("#received-mail").html("Something went wrong");
                $("#sent-mail").html("Something went wrong");

            }

        });

    }
 setInterval(function () {

        loadMails();

    }, 5000);
});

</script>
</body>
</html>