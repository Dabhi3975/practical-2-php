<?php
if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit;
}
include "connection.php";
$user_id = $_COOKIE['user_id'];
$sql = "SELECT * FROM user WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="s.css">
</head>

<body>
    <div class="home-card">
        <h1>Welcome to Home Page</h1>
         <h2>hello,<?php echo $row['name']?>👋</h2>
        <div class="user-info-box">
            <p><b>Name:</b> <span><?php echo $row['name']; ?></span></p>
            <p><b>Email:</b> <span><?php echo $row['email']; ?></span></p>
            <p><b>Mobile:</b> <span><?php echo $row['mobile']; ?></span></p>
        </div>
        <div class="button-group">
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>