create database if not exists inventario;
use inventario;

drop database inventarios;
CREATE TABLE Categoria (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre_categoria VARCHAR(50) NOT NULL,
    descripcion_categoria VARCHAR(50) NOT NULL);

CREATE TABLE Proveedor (
    id_proveedor INT PRIMARY KEY AUTO_INCREMENT,
    nombre_proveedor VARCHAR(100) NOT NULL,
    direccion_proveedor VARCHAR(200),
    telefono_proveedor VARCHAR(20));


CREATE TABLE Producto (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre_producto VARCHAR(100) NOT NULL,
    imagen_producto LONGBLOB,
    descripcion_producto TEXT,
    precio_producto DECIMAL(10, 2) NOT NULL,
    cantidad_producto INT,
	estado_producto VARCHAR(30) NOT NULL,
    fecha_ingreso DATE,
    id_categoria INT NOT NULL,
    id_proveedor INT NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria),
    FOREIGN KEY (id_proveedor) REFERENCES Proveedor(id_proveedor));


CREATE TABLE Usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(50) NOT NULL,
    apellido_usuario VARCHAR(50) NOT NULL,
    email_usuario VARCHAR(100) UNIQUE NOT NULL,
    password_usuario VARCHAR(255) NOT NULL,
    telefono_usuario VARCHAR(10) NOT NULL,
    direccion_usuario VARCHAR(20) NOT NULL,
    rol_usuario ENUM('Administrador', 'Vendedor', 'Comprador') NOT NULL);


CREATE TABLE Venta (
    id_venta INT PRIMARY KEY AUTO_INCREMENT,
    fecha_venta DATE NOT NULL,
    total_venta DECIMAL(10, 2) NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario));


CREATE TABLE Detalle_Venta (
    id_detalle_venta INT PRIMARY KEY AUTO_INCREMENT,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES Venta(id_venta),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto));


CREATE TABLE Inventario (
    id_inventario INT PRIMARY KEY AUTO_INCREMENT,
    id_producto INT NOT NULL,
    fecha_movimiento DATE NOT NULL,
    tipo_movimiento ENUM('Entrada', 'Salida') NOT NULL,
    cantidad INT NOT NULL,
    descripcion VARCHAR(255),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto));