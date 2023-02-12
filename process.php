<?php
session_start();
require('new_connection.php');

    if(isset($_POST['action']) && $_POST['action'] == 'register')
    {
        //call to function
        register_user($_POST); //use the ACTUAL POST!
    }

    else if(isset($_POST['action']) && $_POST['action'] == 'login')
    {
        login_user($_POST);
    } 

    else if(isset($_POST['action']) && $_POST['action'] == 'reset'){
        reset_user($_POST);
    }

    else 
    {
        session_destroy();
        header('location: index.php');
        die();
    }


    function register_user($post) //just a parameter called post
    {
    
        $_SESSION['errors'] = array();
        $salt = bin2hex(openssl_random_pseudo_bytes(22));
        $encrypted_password = md5($post['password'].''.$salt);

        if(empty($post['first_name']))
        {
            $_SESSION['errors'][] = "First name can't be blank!";
        } else if(strlen($post['first_name']) < 2)
        {
            $_SESSION['errors'][] = "Minimum of 2 characters for First Name!";
        } else {
            $length = strlen($post['first_name']);
            for($num = 0; $num < $length; $num++){
                if(is_numeric($post['first_name'][$num])){
                $_SESSION['errors'][] = "First Name should contain letters only";
                }
            }
        }
        if(empty($post['last_name']))
        {
            $_SESSION['errors'][] = "Last name can't be blank!";
        } else if(strlen($post['last_name']) < 2)
        {
            $_SESSION['errors'][] = "Minimum of 2 characters for Last Name!";
        } else {
            $length = strlen($post['last_name']);
            for($num = 0; $num < $length; $num++){
                if(is_numeric($post['last_name'][$num])){
                $_SESSION['errors'][] = "Last Name should contain letters only";
                }
            }
        }
        if(empty($post['contact']))
        {
            $_SESSION['errors'][] = "Please provide contact number!";
        } 
        else if(!is_numeric($post['contact']))
        {
            $_SESSION['errors'][] = "Must be a valid number!";
        } 
        else if(substr($post['contact'], 0, 2) != '09')
        {
            $_SESSION['errors'][] = "Must be a valid number!";
        } 
        else if(strlen($post['contact']) != 11)
        {
            $_SESSION['errors'][] = "Must be a 11 digit number!";
        }

        if(empty($post['password']) < 8)
        {
            $_SESSION['errors'][] = "Password must be at least 8 characters long!";
        }
        if($post['password'] !== $post['confirm_password'])
        {
            $_SESSION['errors'][] = "Password didn't match!";
        }
        if(filter_var(!$post['email'], FILTER_VALIDATE_EMAIL))
        {
            $_SESSION['errors'][] = "Please use a valid email address!";
        }

        //header('location: index.php');
        //die();

        if(count($_SESSION['errors']) > 0)
        {
            header('location: index.php');
            die();
        } else {
        $query = "INSERT INTO users (first_name, last_name, contact_number, email, password, salt, created_at, updated_at) VALUES ('{$post['first_name']}', '{$post['last_name']}', '{$post['contact']}', '{$post['email']}', '{$encrypted_password}', '{$salt}', NOW(), NOW())";
            run_mysql_query($query);

            $_SESSION['success_message'] = "User successfully created!";
            header('location: index.php');
            die();
        }
    }

    function login_user($post) //just a parameter called post
    {
        $query = "SELECT * FROM users WHERE /*users.password = '{$post['password']}' AND */ users.email = '{$post['email']}'";
        $user = fetch_record($query); //go and attempt to grab user with above credentials!
        /*if(count($user) > 0)
        {
            $_SESSION['user_id'] = $user[0]['id'];
            $_SESSION['first_name'] = $user[0]['first_name'];
            $_SESSION['logged_in'] = TRUE;
            header('location: success.php');
        } 
        else 
        {
            $_SESSION['errors'][] = "can't find a user with those credentials.";
            header('location: index.php');
            die();
        }*/
        if(!empty($user)){
            $encrypted_password = md5($post["password"]. '' . $user['salt']);
            if($user['password'] == $encrypted_password){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            header("Location: success.php");
            } else {
                $_SESSION['errors'][] = "Please enter the correct password.";
            }
        } else {
            $_SESSION['errors'][] = "Please enter valid email address.";
        }
        if(!empty($_SESSION['errors'])){
            header("Location: index.php");
        }
    }

    function reset_user($post){
        $query = "SELECT * FROM users WHERE users.contact_number = '{$post['contact']}'";
        $user = fetch_record($query);
        var_dump($user);
        if(!empty($user)){
            if($post['contact'] == $user['contact']){
                $new_password = "village88";
                $encrypted_password = md5($new_password.''.$user['salt']);
            $query = "UPDATE users SET password = '{$encrypted_password}', updated_at = NOW() WHERE users.contact = '{$post['contact']}'";
            run_mysql_query($query);
            } else {
                $_SESSION['errors'][] = "Please enter valid contact number.";
            }
        } else {
            $_SESSION['errors'][] = "Please enter valid contact number.";
        }
        if(!empty($_SESSION['errors'])){
            header("Location: index.php");
        }
    }
?>