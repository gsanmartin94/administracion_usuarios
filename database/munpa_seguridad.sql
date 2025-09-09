CREATE DATABASE munpa_seguridad CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE USER 'supervisor'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON munpa_seguridad.* TO 'supervisor'@'localhost';
FLUSH PRIVILEGES;

USE munpa_seguridad;

--VALORES DE PROVINCIAS, CANTONES Y PARROQUIAS RELACIONADOS
CREATE TABLE
    provincia (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descripcion VARCHAR(500) NOT NULL,
        estado VARCHAR(100) NOT NULL
    );

CREATE TABLE
    canton (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_provincia INT NOT NULL,
        descripcion VARCHAR(500) NOT NULL,
        estado VARCHAR(100) NOT NULL,
        FOREIGN KEY (id_provincia) REFERENCES provincia (id)
    );

CREATE TABLE
    parroquia (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_canton INT NOT NULL,
        descripcion VARCHAR(500) NOT NULL,
        estado VARCHAR(100) NOT NULL,
        FOREIGN KEY (id_canton) REFERENCES canton (id)
    );

--ENTIDAD MAYOR PERSONA, LAS PERSONAS PUEDEN TENER 1 O MAS USUARIOS
CREATE TABLE
    persona (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cedula VARCHAR(10) NOT NULL,
        nombres VARCHAR(250) NOT NULL,
        apellidos VARCHAR(250) NOT NULL,
        genero VARCHAR(150) NOT NULL,
        fecha_nacimiento DATE NOT NULL,
        telefono VARCHAR(10) NOT NULL,
        id_parroquia INT NOT NULL,
        estado VARCHAR(100) NOT NULL,
        FOREIGN KEY (id_parroquia) REFERENCES parroquia (id)
    );

--TIPO DE USUARIO (INTERNO O EXTERNO)
CREATE TABLE
    tipo_usuario (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descripcion VARCHAR(250) NOT NULL,
        estado VARCHAR(100) NOT NULL
    );

--TABLA DE ROL -> SOLO PARA REFERENCIA DE LOS POSIBLES PERMISOS
--QUE TENDRA EL USUARIO (SIMPLIFICA LA ASIGNACION DE PERMISOS)
CREATE TABLE
    rol (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descripcion VARCHAR(250) NOT NULL,
        estado VARCHAR(100) NOT NULL
    );

--ENTIDAD USUARIO, LOS USUARIOS TIENEN PERMISOS
--ESOS PERMISOS PUEDEN SER IGUAL A LOS DE UN ROL E INCLUSOÇ
--PUEDEN SER GESTIONADOS DE FORMA DIRECTA
CREATE TABLE
    usuario (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_persona INT NOT NULL,
        id_rol INT NOT NULL,
        id_tipo_usuario INT NOT NULL,
        correo VARCHAR(150) NOT NULL,
        username VARCHAR(150) NOT NULL,
        password VARCHAR(150) NOT NULL,
        estado VARCHAR(100) NOT NULL,
        FOREIGN KEY (id_persona) REFERENCES persona (id),
        FOREIGN KEY (id_rol) REFERENCES rol (id),
        FOREIGN KEY (id_tipo_usuario) REFERENCES tipo_usuario (id)
    );

--ENTIDAD DE MODULOS DEL SISTEMA, SUBMODULOS Y PERMISOS DE LOS SUBMODULOS
--CADA PERMISO DEPENDE DE LAS VISTAS QUE TENGAN LOS MODULOS
CREATE TABLE
    modulo (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descripcion VARCHAR(250) NOT NULL,
        estado VARCHAR(100) NOT NULL
    );

CREATE TABLE
    submodulo (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_modulo INT NOT NULL,
        descripcion VARCHAR(250) NOT NULL,
        ruta VARCHAR(250) NOT NULL,
        estado VARCHAR(100) NOT NULL,
        FOREIGN KEY (id_modulo) REFERENCES modulo (id)
    );

--PERMISOS QUE EXISTEN EN EL SISTEMA
CREATE TABLE
    permiso (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_submodulo INT NOT NULL,
        descripcion VARCHAR(250) NOT NULL,
        estado VARCHAR(100) NOT NULL,
        FOREIGN KEY (id_submodulo) REFERENCES submodulo (id)
    );

-- ASIGNACION DE PERMISOS A ROLES
CREATE TABLE
    rol_permiso (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_rol INT NOT NULL,
        id_permiso INT NOT NULL,
        estado VARCHAR(100) NOT NULL,
        FOREIGN KEY (id_rol) REFERENCES rol (id),
        FOREIGN KEY (id_permiso) REFERENCES permiso (id)
    );

-- ASIGNACION DE PERMISOS A USUARIOS (UNICO DE TOMAR EN CUENTA EN EL SISTEMA
-- PORQUE LA VALIDACION TOMA ESTA TABLA UNICAMENTE, COPIA EL FORMATO DE PERMISOS QUE SE DAN A UN ROL)
CREATE TABLE
    usuario_permiso (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        id_permiso INT NOT NULL,
        estado VARCHAR(100) NOT NULL,
        FOREIGN KEY (id_usuario) REFERENCES usuario (id),
        FOREIGN KEY (id_permiso) REFERENCES permiso (id)
    );