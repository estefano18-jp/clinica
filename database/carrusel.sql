USE clinicaDB;

CREATE TABLE carrusel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imagen VARCHAR(255) NOT NULL,
    descripcion TEXT
);
SELECT * FROM carrusel;
SELECT * FROM carrusel ORDER BY id DESC LIMIT 3;
SELECT * FROM carrusel;

INSERT INTO carrusel (imagen, descripcion) VALUES
('imagenCarousel01.jpg', 'Primera imagen de prueba'),
('imagenCarousel02.jpg', 'Segunda imagen de prueba'),
('imagenCarousel03.jpg', 'Tercera imagen de prueba');
