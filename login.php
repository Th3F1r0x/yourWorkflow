<?php
require 'php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validaciones
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Todos los campos son obligatorios");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=Email no válido");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        header("Location: login.php?error=Credenciales incorrectas");
        exit();
    }
}

$page_title = "Iniciar Sesión - n8n Store";
include 'header.php';
?>
<main>
    <div class="container mt-5">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="visually-hidden">(requerido)</span></label>
                <input type="email" class="form-control" id="email" name="email" required aria-describedby="email_help">
                <div id="email_help" class="form-text">Ingresa tu correo electrónico registrado.</div>
                <div class="invalid-feedback">Por favor, ingresa un correo electrónico válido.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña <span class="visually-hidden">(requerido)</span></label>
                <input type="password" class="form-control" id="password" name="password" required aria-describedby="password_help">
                <div id="password_help" class="form-text">Ingresa tu contraseña.</div>
                <div class="invalid-feedback">Por favor, ingresa tu contraseña.</div>
            </div>
            <button type="submit" class="btn btn-primary btn-primary-login">Iniciar Sesión</button>
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js" defer></script>
</body>
</html>