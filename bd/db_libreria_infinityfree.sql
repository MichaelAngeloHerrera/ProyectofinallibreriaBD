-- Para InfinityFree: importar en phpMyAdmin con la base de datos ya seleccionada.
-- (Este archivo NO crea la base, porque InfinityFree no lo permite.)

CREATE TABLE publicadores (
  id_pub INT PRIMARY KEY,
  nombre_pub VARCHAR(60) NOT NULL,
  ciudad VARCHAR(40),
  estado CHAR(2),
  pais VARCHAR(30) DEFAULT 'USA'
);

CREATE TABLE autores (
  id_autor VARCHAR(11) PRIMARY KEY,
  apellido VARCHAR(40) NOT NULL,
  nombre VARCHAR(20) NOT NULL,
  telefono VARCHAR(20),
  direccion VARCHAR(60),
  ciudad VARCHAR(30),
  estado CHAR(2),
  codigo_postal VARCHAR(10)
);

CREATE TABLE titulos (
  id_titulo VARCHAR(6) PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  tipo VARCHAR(20),
  id_pub INT,
  precio DECIMAL(8,2),
  avance DECIMAL(10,2),
  regalias INT,
  ventas_ytd INT,
  notas VARCHAR(200),
  fecha_pub DATE,
  FOREIGN KEY (id_pub) REFERENCES publicadores(id_pub)
);

CREATE TABLE titulo_autor (
  id_autor VARCHAR(11),
  id_titulo VARCHAR(6),
  orden_autor TINYINT,
  porcentaje_regalias INT,
  PRIMARY KEY (id_autor, id_titulo),
  FOREIGN KEY (id_autor) REFERENCES autores(id_autor),
  FOREIGN KEY (id_titulo) REFERENCES titulos(id_titulo)
);

CREATE TABLE tiendas (
  id_tienda INT PRIMARY KEY,
  nombre_tienda VARCHAR(60) NOT NULL,
  direcc_tienda VARCHAR(60),
  ciudad VARCHAR(40),
  estado CHAR(2),
  codigo_postal VARCHAR(10)
);

CREATE TABLE ventas (
  id_tienda INT,
  num_orden VARCHAR(20),
  fecha_orden DATE,
  forma_pago VARCHAR(30),
  PRIMARY KEY (id_tienda, num_orden),
  FOREIGN KEY (id_tienda) REFERENCES tiendas(id_tienda)
);

CREATE TABLE detalle_venta (
  id_tienda INT,
  num_orden VARCHAR(20),
  id_titulo VARCHAR(6),
  cantidad INT,
  PRIMARY KEY (id_tienda, num_orden, id_titulo),
  FOREIGN KEY (id_tienda, num_orden) REFERENCES ventas(id_tienda, num_orden),
  FOREIGN KEY (id_titulo) REFERENCES titulos(id_titulo)
);

CREATE TABLE descuento (
  id_descuento INT AUTO_INCREMENT PRIMARY KEY,
  tipo_descuento VARCHAR(40) NOT NULL,
  id_tienda INT NULL,
  cantidad_min INT,
  cantidad_max INT,
  descuento DECIMAL(4,2),
  FOREIGN KEY (id_tienda) REFERENCES tiendas(id_tienda)
);

CREATE TABLE derechos (
  id_derecho INT AUTO_INCREMENT PRIMARY KEY,
  id_titulo VARCHAR(6),
  rango_min INT,
  rango_max INT,
  regalia INT,
  FOREIGN KEY (id_titulo) REFERENCES titulos(id_titulo)
);

CREATE TABLE biografias (
  id_autor VARCHAR(11) PRIMARY KEY,
  biografia TEXT,
  FOREIGN KEY (id_autor) REFERENCES autores(id_autor)
);

CREATE TABLE fotografias (
  id_fotografia INT AUTO_INCREMENT PRIMARY KEY,
  id_autor VARCHAR(11),
  ruta VARCHAR(120),
  FOREIGN KEY (id_autor) REFERENCES autores(id_autor)
);

-- ===== DATOS DE EJEMPLO =====

INSERT INTO publicadores VALUES
(736, 'New Moon Books', 'Boston', 'MA', 'USA'),
(877, 'Binnet & Hardley', 'Washington', 'DC', 'USA'),
(1389, 'Algodata Infosystems', 'Berkeley', 'CA', 'USA'),
(1622, 'Five Lakes Publishing', 'Chicago', 'IL', 'USA'),
(1756, 'Ramona Publishers', 'Dallas', 'TX', 'USA');

INSERT INTO autores VALUES
('172-32-1176', 'White', 'Johnson', '408 496-7223', '10932 Bigge Rd.', 'Menlo Park', 'CA', '94025'),
('213-46-8915', 'Green', 'Marjorie', '415 986-7020', '309 63rd St. #411', 'Oakland', 'CA', '94618'),
('238-95-7766', 'Carson', 'Cheryl', '415 548-7723', '589 Darwin Ln.', 'Berkeley', 'CA', '94705'),
('267-41-2394', 'O''Leary', 'Michael', '408 286-2428', '22 Cleveland Av. #14', 'San Jose', 'CA', '95128'),
('274-80-9391', 'Straight', 'Dean', '415 834-2919', '5420 College Av.', 'Oakland', 'CA', '94609'),
('341-22-1782', 'Smith', 'Meander', '913 843-0462', '10 Mississippi Dr.', 'Lawrence', 'KS', '66044');

INSERT INTO titulos VALUES
('BU1032', 'The Busy Executive''s Database Guide', 'business', 1389, 19.99, 5000.00, 10, 4095, 'Una guía de bases de datos para ejecutivos.', '1991-06-12'),
('PS7777', 'Emotional Security: A New Algorithm', 'psychology', 736, 7.99, 4000.00, 10, 3336, 'Un nuevo enfoque de seguridad emocional.', '1991-06-12'),
('TC4203', 'Fifty Years in Buckingham Palace Kitchens', 'trad_cook', 877, 11.95, 4000.00, 14, 15096, 'Recetas y anécdotas de palacio.', '1991-06-12'),
('MC2222', 'Silicon Valley Gastronomic Treats', 'mod_cook', 877, 19.99, 0.00, 12, 2032, 'Cocina moderna del valle del silicio.', '1991-06-09'),
('PC8888', 'Secrets of Silicon Valley', 'popular_comp', 1389, 20.00, 8000.00, 10, 4095, 'Los secretos de la industria tecnológica.', '1994-06-12');

INSERT INTO titulo_autor VALUES
('172-32-1176', 'PS7777', 1, 100),
('213-46-8915', 'BU1032', 2, 40),
('238-95-7766', 'PC8888', 1, 100),
('267-41-2394', 'TC4203', 1, 100),
('274-80-9391', 'BU1032', 1, 60),
('341-22-1782', 'MC2222', 1, 100);

INSERT INTO tiendas VALUES
(6380, 'Eric the Read Books', '788 Catamaugus Ave.', 'Seattle', 'WA', '98056'),
(7066, 'Barnum''s', '567 Pasadena Ave.', 'Tustin', 'CA', '92789'),
(7067, 'News & Brews', '577 First St.', 'Los Gatos', 'CA', '96745'),
(7131, 'Doc-U-Mat: Quality Laundry and Books', '24-A Avogadro Way', 'Remulade', 'WA', '98014'),
(8042, 'Bookbeat', '679 Carson St.', 'Portland', 'OR', '89076');

INSERT INTO ventas VALUES
(6380, '6871', '1994-09-14', 'Net 60'),
(7066, 'A2976', '1993-05-24', 'Net 30'),
(7067, 'P2121', '1992-06-15', 'Net 30'),
(7131, 'N914008', '1994-09-14', 'Net 30'),
(8042, '423LL922', '1994-09-14', 'ON invoice');

INSERT INTO detalle_venta VALUES
(6380, '6871', 'BU1032', 5),
(7066, 'A2976', 'PC8888', 50),
(7067, 'P2121', 'TC4203', 40),
(7131, 'N914008', 'MC2222', 20),
(8042, '423LL922', 'PS7777', 15);

INSERT INTO descuento (tipo_descuento, id_tienda, cantidad_min, cantidad_max, descuento) VALUES
('Initial Customer', NULL, NULL, NULL, 10.50),
('Volume Discount', NULL, 100, 1000, 6.70),
('Customer Discount', 8042, NULL, NULL, 5.00);

INSERT INTO derechos (id_titulo, rango_min, rango_max, regalia) VALUES
('BU1032', 0, 5000, 10),
('BU1032', 5001, 50000, 12),
('PC8888', 0, 5000, 10),
('PS7777', 0, 5000, 10);

INSERT INTO biografias VALUES
('172-32-1176', 'Autor de libros sobre psicología aplicada.'),
('213-46-8915', 'Escritora especializada en negocios y bases de datos.'),
('238-95-7766', 'Autora de obras sobre tecnología y computación.'),
('274-80-9391', 'Coautor de guías para ejecutivos.');

INSERT INTO fotografias (id_autor, ruta) VALUES
('172-32-1176', 'img/white.jpg'),
('213-46-8915', 'img/green.jpg');
