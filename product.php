<?php
require_once 'php/config.php';
require_once 'php/Parsedown.php';

$Parsedown = new Parsedown();

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php?error=Producto no encontrado");
    exit();
}

// Procesamos la guía en Markdown a HTML
$guia_html = $Parsedown->text($product['guia']);

$page_title = htmlspecialchars($product['name']) . " - n8n Store";
?>
<?php include 'header.php'; ?>
<main>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 img-banner-product">
                <img src="<?php echo htmlspecialchars($product['image_path'] ?? 'img/products/default.jpg'); ?>" class="product-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" />
            </div>
            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="card-especification">
                    <p class="card-categori"><?php echo htmlspecialchars($product['categoria'] ?? 'Sin descripción'); ?></p>
                    <p class="card-date"><?php echo htmlspecialchars($product['fecha_actualizacion'] ?? 'Sin descripción'); ?></p>
                </div>
                <p class="fw-bold">Precio: <span class="text-price"><?php echo number_format($product['price'], 2); ?>€</span></p>
                <a href="checkout.php?id=<?php echo $product['id']; ?>" class="btn btn-success"><?php echo htmlspecialchars($product['estado']); ?></a>
            </div>
            <?php if (!empty($product['img_n8n'])): ?>
                <img class="img-n8n-product img-fluid" src="<?php echo htmlspecialchars($product['img_n8n']); ?>" alt="Captura n8n" />
            <?php endif; ?>
            <div class="contenido">
                <p><?php echo htmlspecialchars($product['contenido']); ?></p>
            </div>
            <div class="guia-markdown">
                <?php echo $guia_html; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js" defer></script>
</body>
</html>