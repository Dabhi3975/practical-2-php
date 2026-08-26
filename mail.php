<?php
    include "connection.php";
    if (! isset($_COOKIE['user_id'])) 
        {
        header("Location: login.php");
        exit;
        }
    $sender_id = $_COOKIE['user_id'];
    $sql = "SELECT id, name, email FROM user WHERE id != '$sender_id' ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    if (isset($_POST['submit'])) 
        {
        $to      = $_POST['to'];
        $message = $_POST['message'];
        $sql     = "SELECT * FROM user WHERE email = '$to'";
        $result  = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) 
            {
                echo "<script>
                        alert('Receiver email does not exist');
                    </script>";
                exit;
            }
        $receiver      = mysqli_fetch_assoc($result);
        $receiver_id   = $receiver['id'];
        $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];
        $attachment    = "";
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) 
            {
                $file_name = $_FILES['file']['name'];
                $file_tmp  = $_FILES['file']['tmp_name'];
                $file_size = $_FILES['file']['size'];
                $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                if (! in_array($file_ext, $allowed_types)) 
                    {
                        echo "<script>
                                alert('Only PDF, JPG, JPEG and PNG files are allowed');
                                window.location.href='mail.php';
                            </script>";
                        exit;
                    }
                $max_size = 5 * 1024 * 1024;
                if ($file_size > $max_size) 
                    {
                        echo "<script>
                                alert('File size must be less than 5 MB');
                                window.location.href='mail.php';
                            </script>";
                        exit;
                    }
                $file_name     = $_FILES['file']['name'];
                $file_name     = preg_replace("/[^a-zA-Z0-9._-]/", "_", $file_name);
                $new_file_name = time() . "_" . $file_name;
                $upload_path   = "uploads/" . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) 
                    {
                        $attachment = $new_file_name;
                    } 
                else {
                        echo "<script>
                                alert('File upload failed');
                                window.location.href='profile.php';
                            </script>";
                        exit;
                    }
            }
        $sql = "INSERT INTO mail (sender_id, receiver_id, message, attachment,size) VALUES
                                ('$sender_id', '$receiver_id', '$message', '$attachment','$file_size')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    // alert('Mail sent successfully');
                    window.location.href='profile.php';
                </script>";
            exit;
        } else {
            echo "<script>
                    alert('Mail sending failed');
                    window.location.href='mail.php';
                </script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Send Mail</title>
    <link rel="stylesheet" href="s.css">
</head>

<body>
<div class="container1">
    <h2>Send Mail</h2>
    <form method="POST"
          enctype="multipart/form-data">
        <label for="to">To</label>
        <select id="to" name="to" required>
            <option value="">-- Select Receiver --</option>
            <?php while ($user = mysqli_fetch_assoc($result)) { 
                echo "<option value=".$user['email'].">".$user['email']."</option>";
            }?>

            </select>
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="6" placeholder="Enter your message" required></textarea>
        <label for="file">Attachment</label>
        <input type="file" id="file" name="file">
        <small class="file-info">
            Maximum file size: 5 MB<br>
            Allowed types: PDF, JPG, JPEG, PNG
        </small>
        <input type="submit" name="submit" value="Send Mail">
    </form>
    <div class="login-link">
        <a href="profile.php">Profile</a>
    </div>
</div>

</body>

</html>
