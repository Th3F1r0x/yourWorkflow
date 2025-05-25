<?php
require 'php/config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Inicia sesión para comprar");
    exit();
}

// Validar product_id desde GET
$product_id = isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) > 0 ? (int)$_GET['id'] : 0;
if (!$product_id) {
    header("Location: index.php?error=Producto no válido");
    exit();
}

// Obtener producto
try {
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    if (!$product) {
        header("Location: index.php?error=Producto no encontrado");
        exit();
    }
} catch (PDOException $e) {
    error_log("Error de base de datos: " . $e->getMessage());
    header("Location: index.php?error=Error de base de datos");
    exit();
}

// Generar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - n8n Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<main>
    <div class="container mt-5">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <h2>Checkout</h2>
        <p>Producto: <?php echo htmlspecialchars($product['name']); ?> - Precio: <?php echo number_format($product['price'], 2); ?>€</p>
        <form action="php/checkout.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <div class="mb-3">
                <label for="card_name" class="form-label">Nombre en la tarjeta</label>
                <input type="text" class="form-control" id="card_name" name="card_name" required minlength="2" maxlength="50">
                <div class="invalid-feedback">Por favor, introduce un nombre válido.</div>
            </div>
            <div class="mb-3">
                <label for="card_number" class="form-label">Número de tarjeta</label>
                <input type="text" class="form-control" id="card_number" name="card_number" required pattern="\d{4} \d{4} \d{4} \d{4}">
                <div class="invalid-feedback">Por favor, introduce un número de tarjeta válido (16 dígitos).</div>
                <span id="card_type" class="form-text"></span>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="expiry_month" class="form-label">Mes de expiración</label>
                    <input type="text" class="form-control" id="expiry_month" name="expiry_month" required pattern="\d{2}">
                    <div class="invalid-feedback">Por favor, introduce un mes válido (MM).</div>
                </div>
                <div class="col">
                    <label for="expiry_year" class="form-label">Año de expiración</label>
                    <input type="text" class="form-control" id="expiry_year" name="expiry_year" required pattern="\d{4}">
                    <div class="invalid-feedback">Por favor, introduce un año válido (YYYY).</div>
                </div>
            </div>
            <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" required pattern="\d{3,4}">
                <div class="invalid-feedback">Por favor, introduce un CVV válido (3-4 dígitos).</div>
            </div>
            <div class="mb-3">
                <label for="billing_address" class="form-label">Dirección de facturación (opcional)</label>
                <input type="text" class="form-control" id="billing_address" name="billing_address">
            </div>
            <div class="mb-3">
                <label for="billing_city" class="form-label">Ciudad (opcional)</label>
                <input type="text" class="form-control" id="billing_city" name="billing_city">
            </div>
            <div class="mb-3">
                <label for="billing_state" class="form-label">Estado/Provincia (opcional)</label>
                <input type="text" class="form-control" id="billing_state" name="billing_state">
            </div>
            <div class="mb-3">
                <label for="billing_zip" class="form-label">Código postal (opcional)</label>
                <input type="text" class="form-control" id="billing_zip" name="billing_zip">
            </div>
            <button type="submit" class="btn btn-success">Comprar y Descargar</button>
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js" defer></script>
</body>
</html>