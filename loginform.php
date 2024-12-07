<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'LTW');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate empty inputs
    if (empty($_POST['email']) || empty($_POST['password'])) {
        header("Location: login.php?error=Vui lòng nhập đầy đủ email và mật khẩu");
        exit();
    }

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=Email không hợp lệ");
        exit();
    }

    $sql = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password == $user['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=Email hoặc mật khẩu không chính xác");
            exit();
        }
    } else {
        header("Location: login.php?error=Email hoặc mật khẩu không chính xác");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>