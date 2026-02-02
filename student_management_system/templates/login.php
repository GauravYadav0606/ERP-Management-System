<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NIET</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="logo" style="justify-content: center;">
                🎓 NIET
            </div>
            <h2>Welcome Back</h2>
            
            <?php if (isset($_POST['login'])): ?>
                <?php
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    if ($auth->login($username, $password)) {
                        header('Location: index.php');
                    } else {
                        echo '<div style="color: red; margin-bottom: 1rem;">Invalid credentials</div>';
                    }
                ?>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="admin" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="admin123" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
