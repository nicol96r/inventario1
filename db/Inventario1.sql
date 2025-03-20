-- Eliminar la base de datos si existe y volver a crearla
DROP DATABASE IF EXISTS inventarios;
CREATE DATABASE inventarios;
USE inventarios;

-- Tabla de roles de usuario
CREATE TABLE rol_usuario (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- Insertar roles en la tabla rol_usuario
INSERT INTO rol_usuario (nombre) VALUES
('Administrador'),
('Comprador'),
('Proveedor');

-- Tabla de usuarios
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    tipo_documento ENUM('Cédula', 'Tarjeta de Identidad', 'Pasaporte') NOT NULL,
    documento VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabla de relación usuario - rol (muchos a muchos)
CREATE TABLE usuario_rol (
    id_usuario_rol INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_rol INT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_rol) REFERENCES rol_usuario(id_rol) ON DELETE CASCADE
);

-- Tabla de categorías
CREATE TABLE categoria (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT
);

-- Tabla de subcategorías
CREATE TABLE subcategoria (
    id_subcategoria INT PRIMARY KEY AUTO_INCREMENT,
    id_categoria INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria) ON DELETE CASCADE
);

-- Tabla de productos
CREATE TABLE producto (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    id_categoria INT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    imagen LONGBLOB,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria) ON DELETE CASCADE
);

-- Tabla de pedidos
CREATE TABLE pedido (
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(50),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

-- Tabla de detalle de pedidos
CREATE TABLE detalle_pedido (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT,
    id_producto INT,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto) ON DELETE CASCADE
);

-- Insertar categorías
INSERT INTO categoria (nombre, descripcion) VALUES
('Supermercado', 'Productos de supermercado y abarrotes'),
('Vehículos', 'Automóviles, motocicletas y repuestos'),
('Tecnología', 'Dispositivos electrónicos y accesorios');


-- Insertar subcategorías
INSERT INTO subcategoria (id_categoria, nombre, descripcion) VALUES
-- Supermercado
((SELECT id_categoria FROM categoria WHERE nombre='Supermercado'), 'Alimentos Supermercado', 'Productos de consumo diario'),
((SELECT id_categoria FROM categoria WHERE nombre='Supermercado'), 'Bebidas Supermercado', 'Bebidas alcohólicas y no alcohólicas'),
((SELECT id_categoria FROM categoria WHERE nombre='Supermercado'), 'Limpieza Supermercado', 'Productos de limpieza del hogar'),

-- Vehículos
((SELECT id_categoria FROM categoria WHERE nombre='Vehículos'), 'Autos', 'Automóviles nuevos y usados'),
((SELECT id_categoria FROM categoria WHERE nombre='Vehículos'), 'Motos', 'Motocicletas de diferentes estilos'),
((SELECT id_categoria FROM categoria WHERE nombre='Vehículos'), 'Partes y accesorios', 'Repuestos y accesorios para vehículos');

-- Insertar usuarios con correos únicos
INSERT INTO usuario (tipo_documento, documento, nombre, apellido, fecha_nacimiento, genero, email, password) VALUES
('Cédula', '12345678', 'Admin', 'Ejemplo', '1990-01-01', 'Masculino', 'admin1@example.com', '1234'),
('Cédula', '12345679', 'Admin', 'Ejemplo', '1990-01-01', 'Masculino', 'admin2@example.com', '1234'),
('Cédula', '12345680', 'Admin', 'Ejemplo', '1990-01-01', 'Masculino', 'admin3@example.com', '1234'),
('Cédula', '123456789', 'User', 'Visual', '1995-05-05', 'Femenino', 'user@example.com', '1234');

-- Asignar roles a usuarios específicos (usuarios existentes)
INSERT INTO usuario_rol (id_usuario, id_rol) VALUES
(1, (SELECT id_rol FROM rol_usuario WHERE nombre = 'Administrador')),
(2, (SELECT id_rol FROM rol_usuario WHERE nombre = 'Proveedor')),
(3, (SELECT id_rol FROM rol_usuario WHERE nombre = 'Comprador'));