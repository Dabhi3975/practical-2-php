<?php
    if (isset($_POST['submit'])) {
    include "connection.php";
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $mobile   = $_POST['mobile'];
    $confirm_password=$_POST['confirm_password'];

    if (! preg_match("/^[a-zA-Z ]+$/", $name)) {
        echo "<script>
            alert('Name should contain only letters');
            window.location.href='Register.php';
          </script>";
        exit;
    }
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Please enter a valid email address');
            window.location.href='Register.php';
          </script>";
        exit;
    }
    $check_sql    = "SELECT * FROM user WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
            alert('Email already registered');
            window.location.href='Register.php';
          </script>";
        exit;
    }
    if (
        strlen($password) < 6 ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[^a-zA-Z0-9]/', $password) ||
        $password != $confirm_password
    ) {

        echo "<script>
            alert('Password must be at least 6 characters, contain a number, a special character, and match confirm password');
            window.location.href='Register.php';
        </script>";

        exit;
    }
    if (! preg_match("/^[0-9]{10}$/", $mobile)) {
        echo "<script>
            alert('Mobile number must contain exactly 10 digits');
            window.location.href='Register.php';
          </script>";
        exit;
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql      = "INSERT INTO user (name,email, password, mobile)VALUES
                                    ('$name', '$email', '$password', '$mobile')";
    if (! mysqli_query($conn, $sql)) {
        echo "Error: " . mysqli_error($conn);
    }
    header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="s.css">
</head>

<body>
<div class="container1">
    <h2>Registration Form</h2>
    <form method="POST">

        <label for="name">Name</label>
        <input type="text" id="name" name="name" >

        <label for="email">Email</label>
        <input type="email" id="email" name="email">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password">

        <label for="mobile">Mobile Number</label>
        <input type="tel" id="mobile" name="mobile">

        <input id="submit" type="submit" name="submit" value="Register">

    </form>
    <div class="login-link">
        <p>Already have an account?</p>
        <a href="login.php">Login Here</a>
    </div>
</div>
</body>
</html>
<script>

$(document).ready(function () {

    $("#registrationForm").validate({

        rules: {

            name: {
                required: true,
                pattern: /^[a-zA-Z ]+$/
            },

            email: {
                required: true,
                email: true
            },

            password: {
                required: true,
                minlength: 6
            },

            confirm_password: {
                required: true,
                equalTo: "#password"
            },

            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            }

        },

        messages: {

            name: {
                required: "Please enter your name",
                pattern: "Name should contain only letters"
            },

            email: {
                required: "Please enter your email",
                email: "Please enter a valid email"
            },

            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 6 characters"
            },

            confirm_password: {
                required: "Please confirm your password",
                equalTo: "Password does not match"
            },

            mobile: {
                required: "Please enter mobile number",
                digits: "Only numbers are allowed",
                minlength: "Mobile number must be 10 digits",
                maxlength: "Mobile number must be 10 digits"
            }

        }

    });

});

</script>