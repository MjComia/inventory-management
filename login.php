<?php 
ob_start();
session_start();

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect("localhost", "root", "", "inventory-management");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}


if(isset($_POST['register'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST ['new_username']);
    $new_password = mysqli_real_escape_string($conn, $_POST ['new_password']);
    $role = mysqli_real_escape_string($conn, $_POST ['role']);

    $encrypted_password = md5($new_password);

    //checker for user if already existing
    $check_query = "SELECT * FROM users WHERE username = '$new_username'";
    $check_result = mysqli_query($conn, $check_query);

if(mysqli_num_rows($check_result) > 0) {
    $register_error = "Username already exist. Please choose a different username";
} else { //inserting new user in database
    $insert_query = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$encrypted_password', '$role')";    
    if (mysqli_query($conn, $insert_query)) {
        $register_success = "User registered successfully"; //hindi nag sshow kapag nag new register pwede tanggalin pero maganda ilagay

        $_SESSION['username'] = $new_username;
        $_SESSION['role'] = $role;

        if ($role == 'admin') {
            header("Location: admindash.php");
        } elseif ($role == 'middleman') {
            header("Location: middlemandash.php");
        } elseif ($role == 'sender') {
            header("Location: senderdash.php");
        }
        exit();
    } else {
        $register_error = "Error: " . mysqli_error($conn);
    }
  }
}

if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $encrypted_password = md5($password);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$encrypted_password'";
    $result = mysqli_query($conn, $query);
   
    if (mysqli_num_rows($result) == 1) {
        
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; 

        // Redirect according to user role
        if ($user['role'] == 'admin') {
            header("Location: admindash.php");
        } else if ($user['role'] == 'middleman') {
            header("Location: middlemandash.php"); 
        } else if ($user['role'] == 'sender') {
            header("Location: senderdash.php"); 
        }
        exit();
    } else {
        $error = "Invalid username and password.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($login_error)): ?>
        <p style="color: red;"><?php echo $login_error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required autocomplete="off"><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required autocomplete="off"><br><br>
        
        <button type="submit" name="login"> Login</button>
    </form>

    <h1>Register</h1>
    
    <?php if (isset($register_error)): ?>
        <p style= "color: red;"><?php echo $register_error; ?></p>
        <?php elseif (isset($register_success)): ?>
            <p style= "color: green;"> <?php echo  $register_success; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="new_username">Username: </label>
                <input type="text" id="new_username" name="new_username" required autocomplete="off"><br><br>

                <label for="new_password">Password: </label>
                <input type="password" id="new_password" name="new_password" required autocomplete="off"><br><br>

                <label for="role" > Role: </label> 
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="middleman">Middleman</option>
                    <option value="sender">Sender</option>
                </select><br><br>

                <button type="submit" name="register">Register</button>
        </form>
</body>
</html>