<?php 
ob_start();
session_start();
include('inc/header.php');

// Database connection
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "ims_db";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "You are connected <br>";
}

$registerError = '';
$registerSuccess = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input and sanitize
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $confirmPassword = filter_input(INPUT_POST, "confirm_password", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);

    // Validate input
    if (empty($username)) {
        $registerError = "Please enter a username.";
    } elseif (empty($email)) {
        $registerError = "Please enter a valid email address.";
    } elseif (empty($password)) {
        $registerError = "Please enter a password.";
    } elseif ($password !== $confirmPassword) {
        $registerError = "Passwords do not match.";
    } elseif (empty($role)) {
        $registerError = "Please specify a role.";
    } else {
        // Hash the password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $sql = "INSERT INTO ims_user (name, email, password, type) VALUES ('$username', '$email', '$hash', '$role')";

        try {
            if (mysqli_query($conn, $sql)) {
                $registerSuccess = "Registration successful! Redirecting to login...";
                header("Refresh: 2; url=index.php"); // Redirect after 2 seconds
                exit();
            } else {
                $registerError = "Error: " . mysqli_error($conn);
            }
        } catch (Exception $e) {
            $registerError = "Error during registration: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Inventory Management System</title>
</head>
<body>
    <h1 class="text-center my-4 py-3 text-light" id="title">Inventory Management System - PHP</h1>    

    <!-- Registration Form -->
    <div class="col-lg-4 col-md-5 col-sm-10 col-xs-12 mt-4">
        <div class="card rounded-0 shadow">
            <div class="card-header">
                <div class="card-title h3 text-center mb-0 fw-bold">Register</div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form method="post" action="">
                        <div class="form-group">
                            <?php if ($registerError) { ?>
                                <div class="alert alert-danger rounded-0 py-1"><?php echo $registerError; ?></div>
                            <?php } elseif ($registerSuccess) { ?>
                                <div class="alert alert-success rounded-0 py-1"><?php echo $registerSuccess; ?></div>
                            <?php } ?>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="control-label">Username</label>
                            <input name="username" id="username" type="text" class="form-control rounded-0" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" class="form-control rounded-0" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" class="form-control rounded-0" id="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="control-label">Confirm Password</label>
                            <input type="password" class="form-control rounded-0" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="control-label">Role</label>
                            <input type="text" class="form-control rounded-0" id="role" name="role" placeholder="Enter Role" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="register" class="btn btn-success rounded-0">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php 
include('inc/footer.php'); 
mysqli_close($conn);
?>
