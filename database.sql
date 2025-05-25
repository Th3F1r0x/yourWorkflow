-- Tabla de usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de órdenes
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insertar un producto de ejemplo
INSERT INTO products (name, description, price, file_path)
VALUES ('Automatización n8n Básica', 'JSON y guía para automatizar tareas con n8n', 19.99, 'files/automation1.json');


INSERT INTO products (name, price, description, image_path, categoria, guia, fecha_actualizacion, estado, contenido, img_n8n)
VALUES (
    'Automated AI Social Media System',
    34.50,
    'Sistema de automatización para redes sociales con IA.',
    'img/products/social-media.jpg',
    'Automatización',
    '# Guía de Uso\n1. Configura tu API de redes sociales.\n2. Instala el flujo en n8n.',
    '2025-05-01',
    'Comprar ahora',
    'Incluye flujos para Twitter, LinkedIn y más.',
    'img/products/n8n-social.jpg'
);