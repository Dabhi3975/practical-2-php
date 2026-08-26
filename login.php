<?php
    if (isset($_POST['submit'])) {
    include "connection.php";
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $sql      = "SELECT * FROM user WHERE email = '$email'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            setcookie("user_id", $row['id'], time() + 3600, "/");
            header("Location: home.php");
            exit;
        } else {
            echo "<script>
            alert('wrong password');
            window.location.href='login.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Email does not exist');
            window.location.href='login.php';
          </script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="s.css">
</head>
<body>
    <div class="container1">
        <h2>Login...</h2>
        <form  method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <input id="submit" type="submit" name="submit" value="Login">
        </form>
        <div class="login-link">
            <p>Don't have an account?</p>
            <a href="Register.php">Register Here</a>
        </div>
    </div>
</body>
</html>
