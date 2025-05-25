<?php
session_start();
require 'php/config.php';
include 'navbar.php';

$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($search_query)) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $search_term = "%" . $search_query . "%";
    $stmt->execute([$search_term, $search_term]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda - n8n Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1>Resultados de la búsqueda para "<?php echo htmlspecialchars($search_query); ?>"</h1>
        <?php if (!empty($results)): ?>
            <div class="row">
                <?php foreach ($results as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100 border-0">
                            <img src="<?php echo htmlspecialchars($product['image_path'] ?? 'img/products/default.jpg'); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($product['name'] ?? 'Producto'); ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                                <p class="card-text fw-bold">Precio: <span class="text-price"><?php echo number_format($product['price'], 2); ?>€</span></p>
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary mt-auto">Ver más</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No se encontraron resultados para su búsqueda.</p>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>