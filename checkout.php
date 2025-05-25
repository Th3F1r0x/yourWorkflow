<?php
// php/checkout.php
require 'php/config.php';

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: checkout.php?id={$_POST['product_id']}&error=CSRF token inválido");
    exit();
}

// Validate product ID
$product_id = isset($_POST['product_id']) && (int)$_POST['product_id'] > 0 ? (int)$_POST['product_id'] : 0;
if (!$product_id) {
    header("Location: index.php?error=Producto no válido");
    exit();
}

// Fetch product
try {
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        header("Location: index.php?error=Producto no encontrado");
        exit();
    }
} catch (PDOException $e) {
    header("Location: index.php?error=Error de base de datos");
    exit();
}

// Validate form inputs
$card_name = trim($_POST['card_name'] ?? '');
$card_number = str_replace(' ', '', trim($_POST['card_number'] ?? ''));
$expiry_month = trim($_POST['expiry_month'] ?? '');
$expiry_year = trim($_POST['expiry_year'] ?? '');
$cvv = trim($_POST['cvv'] ?? '');

if (strlen($card_name) < 2 || strlen($card_name) > 50) {
    header("Location: checkout.php?id=$product_id&error=Nombre en la tarjeta inválido");
    exit();
}

// Basic card number validation (Luhn algorithm)
function validateLuhn($number) {
    $sum = 0;
    $isEven = false;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $digit = (int)$number[$i];
        if ($isEven) {
            $digit *= 2;
            if ($digit > 9) $digit -= 9;
        }
        $sum += $digit;
        $isEven = !$isEven;
    }
    return $sum % 10 === 0;
}

if (!preg_match('/^\d{13,19}$/', $card_number) || !validateLuhn($card_number)) {
    header("Location: checkout.php?id=$product_id&error=Número de tarjeta inválido");
    exit();
}

// Validate expiry date
$currentYear = (int)date('Y');
$currentMonth = (int)date('m');
if (!preg_match('/^\d{2}$/', $expiry_month) || !preg_match('/^\d{4}$/', $expiry_year) ||
    (int)$expiry_year < $currentYear || ((int)$expiry_year === $currentYear && (int)$expiry_month < $currentMonth)) {
    header("Location: checkout.php?id=$product_id&error=Fecha de expiración inválida");
    exit();
}

// Validate CVV
$isAmex = substr($card_number, 0, 1) === '3';
if (!preg_match($isAmex ? '/^\d{4}$/' : '/^\d{3}$/', $cvv)) {
    header("Location: checkout.php?id=$product_id&error=CVV inválido");
    exit();
}

// Sanitize optional billing fields
$billing_address = filter_var(trim($_POST['billing_address'] ?? ''), FILTER_SANITIZE_STRING);
$billing_city = filter_var(trim($_POST['billing_city'] ?? ''), FILTER_SANITIZE_STRING);
$billing_state = filter_var(trim($_POST['billing_state'] ?? ''), FILTER_SANITIZE_STRING);
$billing_zip = filter_var(trim($_POST['billing_zip'] ?? ''), FILTER_SANITIZE_STRING);

// TODO: Integrate payment gateway (e.g., Stripe)
// Example: $stripe->charges->create([...]);

// Store success message and redirect
$_SESSION['success'] = 'Compra completada exitosamente';
header("Location: index.php");
exit();
?>