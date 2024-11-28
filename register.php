<?php 
session_start();
$conn = mysqli_connect("localhost", "root", "", "inventory-management");

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
?>


<!--------------------------------H T M L------------------------------------------------------------------------------------------------------------------>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!--Stylesheets-->
    <link rel="stylesheet" type="text/css" href="assets/cs/main.css"/>
    
</head>

<body>

<?php if (isset($login_error)): ?>
    <div class="message-box error"><?php echo $login_error; ?></div>
<?php elseif (isset($register_error)): ?>
    <div class="message-box error"><?php echo $register_error; ?></div>
<?php elseif (isset($register_success)): ?>
    <div class="message-box success"><?php echo $register_success; ?></div>
<?php endif; ?> 

    
<h1>Register</h1>
    <?php if (isset($register_error)): ?>
        <p style= "color: red;"><?php echo $register_error; ?></p>
        <?php elseif (isset($register_success)): ?>
            <p style= "color: green;"> <?php echo  $register_success; ?></p>
            <?php endif; ?>

        <form method="post" action= "<?php htmlspecialchars($_SERVER["PHP_SELF"])?>">
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
        
        <!--JAVASCRIPTS-->
        <script type="text/javascripts" src="assets/js/main.js"></script>
</body>
</html>
