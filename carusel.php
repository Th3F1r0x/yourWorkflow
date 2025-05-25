<form class="d-flex ms-3">
    <input class="form-control me-2" type="search" placeholder="Buscar automatizaciones..." aria-label="Search" id="searchInput">
</form>

<div class="container">
    <div class="product-carousel-container">
        <button class="scroll-btn left" id="scrollLeft">❮</button>
        <div class="product-carousel" id="carousel">
            <?php if (empty($products)): ?>
                <div class="text-center text-muted">
                    <p>No hay productos disponibles.</p>
                </div>
            <?php else: ?>
                <?php foreach (array_chunk($products, 6) as $chunk): ?>
                    <div class="carousel-page">
                        <?php foreach ($chunk as $product): ?>
                            <div class="product-card"
                                 data-name="<?php echo htmlspecialchars(strtolower($product['name'] ?? '')); ?>"
                                 data-description="<?php echo htmlspecialchars(strtolower($product['description'] ?? '')); ?>">
                                <div class="card shadow-sm w-100 h-100 border-0">
                                    <img src="<?php echo htmlspecialchars($product['image_path'] ?? 'img/products/default.jpg'); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($product['name'] ?? 'Producto'); ?>">
                                    <div class="card-body ">
                                        <h5 class="card-title text-dark"><?php echo htmlspecialchars($product['name'] ?? 'Sin nombre'); ?></h5>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars($product['description'] ?? 'Sin descripción'); ?></p>
                                        <div class="card-especification">
                                            <p class="card-categori"><?php echo htmlspecialchars($product['categoria'] ?? 'Sin descripción'); ?></p>
                                        </div>
                                        <p class="card-text card-text-price fw-bold">Precio: <span class="text-price"><?php echo number_format($product['price'] ?? 0, 2); ?>€</span></p>
                                        <a href="product.php?id=<?php echo $product['id'] ?? ''; ?>" class="btn btn-primary mt-auto">Ver más</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="scroll-btn right" id="scrollRight">❯</button>
    </div>

    <div id="noResults" class="text-center text-muted" style="display: none;">
        <p>No se encontraron resultados para su búsqueda.</p>
    </div>
</div>