<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style type="text/css">
        *
        {
            font-family: sans-serif;
            margin-left: auto;
            margin-right: auto;
        }
        h2{
            text-align: center;
        }
        input{
            width: 200px;
            height:25px;
            margin: 5px;
            border-radius: 10px;
        }
        .error
        {
            border: solid 2px black;
            background-color: lightgray;
            color:red;
            width: 400px;
            height: 20px;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }
        .success
        {
            border: solid 2px black;
            background-color: lightgray;
            color:green;
            width: 300px;
            height: 20px;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }
        form{
            border: solid 2px black;
            border-radius: 5px;
            width: 400px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: lightgray;
        }
        #button{
            width: 100px;
            height: 35px;
            border-radius: 15px;
            background-color: green;
            border: solid 2px green;
            color: white;
            margin-left: 145px;
        }
        
    </style>
</head>
<body style="background-color:#A5AAAB;">
    
<?php
        if(isset($_SESSION['errors']))
        {
            foreach($_SESSION['errors'] as $error)
            {
                echo "<p class='error'> $error </p>";
            }

            unset($_SESSION['errors']);
        }
        if(isset($_SESSION['success_message']))
        {
            echo "<p class='success'>{$_SESSION['success_message']} </p>";
            unset($_SESSION['success_message']);
        }
?>
    <form action='process.php' method='POST'>

    <h2>Register</h2>
        <input type='hidden' name='action' value='register'>
        First Name: <input type='text' name='first_name'><br>
        Last Name: <input type='text' name='last_name'><br>
        Contact Number: <input type='text' name='contact'><br>
        Email Address: <input type='text' name='email'><br>
        Password: <input type='password' name='password'><br>
        Confirm Password: <input type='password' name='confirm_password'><br>
        <input type='submit' id='button' value='Register'>
    </form>
    <form action='process.php' method='POST'>
    <h2>Login</h2>
        <input type='hidden' name='action' value='login'>
        Email Address: <input type='text' name='email'><br>
        Password: <input type='password' name='password'><br>
        <input type='submit' id='button' value='Login'><br>
        <a href='update.php' class='reset' name="reset">Reset Password?</a>
    </form>
</body>
</html>