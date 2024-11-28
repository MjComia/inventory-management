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

// Login check
if (isset($_POST['login'])) {
    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Encrypt password
    $encrypted_password = md5($password);

    // Query for matching username and encrypted password
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$encrypted_password'";
    $result = mysqli_query($conn, $query);
   
    // Check if the query returns a valid user
    if (mysqli_num_rows($result) == 1) {
        // Fetch user data and set session variables
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; 

        // Redirect based on user role
        if ($user['role'] == 'admin') {
            header("Location: admindash.php");
        } else if ($user['role'] == 'middleman') {
            header("Location: middlemandash.php"); 
        } else if ($user['role'] == 'sender') {
            header("Location: senderdash.php"); 
        }
        exit();
    } else {
        // Invalid credentials error
        $error = "Invalid username or password.";
    }
}

mysqli_close($conn);
?>

<!--------------------------------H T M L------------------------------------------------------------------------------------------------------------------>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register</title>

    <!--Stylesheets-->
    <link rel="stylesheet" type="text/css" href="assets/cs/main.css"/>
    
</head>
<body>

<?php if (isset($error)): ?>
    <div class="error-message">
        <?php echo $error; ?>
    </div> 
<?php endif; ?>


    <!-- Login Form -->
    <h1>Log In</h1>
    <?php if (isset($login_error)): ?>
        <p style="color: red;"><?php echo $login_error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required autocomplete="off"><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required autocomplete="off"><br><br>
        
        <button type="submit" name="login"> Log In</button>
        <p>or</p>
        <a href = "register.php">Create new account</a>
    </form> 
    
        <!--JAVASCRIPTS-->
        <script type="text/javascripts" src="assets/js/main.js"></script>
</body>
</html>