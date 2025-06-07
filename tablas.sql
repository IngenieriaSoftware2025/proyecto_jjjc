CREATE TABLE marc_cel (
    marca_id SERIAL PRIMARY KEY,
    marca_nombre VARCHAR(100) NOT NULL UNIQUE,
    marca_descripcion VARCHAR(250),
    marca_estado SMALLINT DEFAULT 1,
    marca_fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND
);

-- Tabla de clientes
CREATE TABLE clientes (
    cli_id SERIAL PRIMARY KEY,
    cli_nombres VARCHAR(255) NOT NULL,
    cli_apellidos VARCHAR(255) NOT NULL,
    cli_nit VARCHAR(15),
    cli_telefono VARCHAR(15) NOT NULL,
    cli_correo VARCHAR(100),
    cli_direccion VARCHAR(250),
    cli_estado SMALLINT DEFAULT 1,
    cli_fecha_registro DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND
);

CREATE TABLE usuario(
usuario_id SERIAL PRIMARY KEY,
usuario_nom1 VARCHAR (50) NOT NULL,
usuario_nom2 VARCHAR (50) NOT NULL,
usuario_ape1 VARCHAR (50) NOT NULL,
usuario_ape2 VARCHAR (50) NOT NULL,
usuario_tel INT NOT NULL, 
usuario_direc VARCHAR (150) NOT NULL,
usuario_dpi VARCHAR (13) NOT NULL,
usuario_correo VARCHAR (100) NOT NULL,
usuario_contra LVARCHAR (1056) NOT NULL,
usuario_token LVARCHAR (1056) NOT NULL,
usuario_fecha_creacion DATE DEFAULT TODAY,
usuario_fecha_contra DATE DEFAULT TODAY,
usuario_fotografia LVARCHAR (2056),
usuario_situacion SMALLINT DEFAULT 1
);

CREATE TABLE aplicacion(
app_id SERIAL PRIMARY KEY,
app_nombre_largo VARCHAR (250) NOT NULL,
app_nombre_medium VARCHAR (150) NOT NULL,
app_nombre_corto VARCHAR (50) NOT NULL,
app_fecha_creacion DATE DEFAULT TODAY,
app_situacion SMALLINT DEFAULT 1
);

CREATE TABLE permiso(
permiso_id SERIAL PRIMARY KEY, 
permiso_app_id INT NOT NULL,
permiso_nombre VARCHAR (150) NOT NULL,
permiso_clave VARCHAR (250) NOT NULL,
permiso_desc VARCHAR (250) NOT NULL,
permiso_fecha DATE DEFAULT TODAY,
permiso_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (permiso_app_id) REFERENCES aplicacion(app_id) 
);

CREATE TABLE asig_permisos(
asignacion_id SERIAL PRIMARY KEY,
asignacion_usuario_id INT NOT NULL,
asignacion_app_id INT NOT NULL,
asignacion_permiso_id INT NOT NULL,
asignacion_fecha DATE DEFAULT TODAY,
asignacion_usuario_asigno INT NOT NULL,
asignacion_motivo VARCHAR (250) NOT NULL,
asignacion_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (asignacion_usuario_id) REFERENCES usuario(usuario_id),
FOREIGN KEY (asignacion_app_id) REFERENCES aplicacion(app_id),
FOREIGN KEY (asignacion_permiso_id) REFERENCES permiso(permiso_id)
);

CREATE TABLE historial_act(
historial_id SERIAL PRIMARY KEY,
historial_usuario_id INT NOT NULL,
historial_fecha DATETIME YEAR TO MINUTE,
historial_ruta INT NOT NULL,
historial_ejecucion LVARCHAR (1056) NOT NULL,
historial_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (historial_usuario_id) REFERENCES usuario(usuario_id),
FOREIGN KEY (historial_ruta) REFERENCES rutas(ruta_id)
);

CREATE TABLE rutas(
ruta_id SERIAL PRIMARY KEY,
ruta_app_id INT NOT NULL,
ruta_nombre LVARCHAR (1056) NOT NULL,
ruta_descripcion VARCHAR (250) NOT NULL,
ruta_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (ruta_app_id) REFERENCES aplicacion(app_id)
);



-- Tabla de inventario de celulares
CREATE TABLE invent_cel (
    invent_id SERIAL PRIMARY KEY,
    invent_modelo VARCHAR(100) NOT NULL,
    invent_marca_id INT NOT NULL,
    invent_precio_compra DECIMAL(10,2) NOT NULL,
    invent_precio_venta DECIMAL(10,2) NOT NULL,
    invent_cantidad_disponible INT NOT NULL DEFAULT 0,
    invent_descripcion VARCHAR(250),
    invent_estado SMALLINT DEFAULT 1,
    invent_fecha_ingreso DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    FOREIGN KEY (invent_marca_id) REFERENCES marc_cel(marca_id)--TABLA MARC_CEL
);

-- Tabla de tipos de servicios de reparación
CREATE TABLE tipo_servicio (
    serv_id SERIAL PRIMARY KEY,
    serv_nombre VARCHAR(100) NOT NULL,
    serv_precio DECIMAL(10,2) NOT NULL,
    serv_descripcion VARCHAR(250),
    serv_estado SMALLINT DEFAULT 1
);

-- Tabla de empleados/trabajadores
CREATE TABLE empleados (
    empleado_id SERIAL PRIMARY KEY,
    empleado_nombres VARCHAR(255) NOT NULL,
    empleado_apellidos VARCHAR(255) NOT NULL,
    empleado_telefono VARCHAR(15),
    empleado_especialidad VARCHAR(100),
    empleado_estado SMALLINT DEFAULT 1,
    empleado_fecha_ingreso DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND
);

-- Tabla de ventas
CREATE TABLE rep_ventas (
    venta_id SERIAL PRIMARY KEY,
    venta_cliente_id INT NOT NULL,
    venta_total DECIMAL(10,2) NOT NULL,
    venta_fecha DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    venta_estado VARCHAR(20) DEFAULT 'COMPLETADA',
    venta_tipo VARCHAR(20) DEFAULT 'VENTA', -- VENTA o REPARACION
    FOREIGN KEY (venta_cliente_id) REFERENCES clientes(cli_id)--TABLA CLEINTES
);

-- Tabla de detalle de ventas (productos vendidos)
CREATE TABLE detalle_ventas (
    detalle_id SERIAL PRIMARY KEY,
    detalle_venta_id INT NOT NULL,
    detalle_inventario_id INT NOT NULL,
    detalle_cantidad INT NOT NULL,
    detalle_precio_unitario DECIMAL(10,2) NOT NULL,
    detalle_subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (detalle_venta_id) REFERENCES ventas(venta_id), --TABLA VENTAS
    FOREIGN KEY (detalle_inventario_id) REFERENCES invent_cel(invent_id) --TABLA INVENT_CEL
);

-- Tabla de órdenes de reparación
CREATE TABLE ordenes_reparacion (
    orden_id SERIAL PRIMARY KEY,
    orden_cli_id INT NOT NULL,
    orden_empleado_id INT,
    orden_serv_id INT,
    orden_modelo_celular VARCHAR(100) NOT NULL,
    orden_marca_celular VARCHAR(100) NOT NULL,
    orden_motivo_ingreso VARCHAR(250) NOT NULL,
    orden_diagnostico VARCHAR(250),
    orden_precio_servicio DECIMAL(10,2),
    orden_fecha_ingreso DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    orden_fecha_asignacion DATETIME YEAR TO SECOND,
    orden_fecha_finalizacion DATETIME YEAR TO SECOND,
    orden_estado VARCHAR(20) DEFAULT 'RECIBIDO', -- RECIBIDO, ASIGNADO, EN_PROCESO, TERMINADO, ENTREGADO
    orden_observaciones VARCHAR(250),
    FOREIGN KEY (orden_cli_id) REFERENCES clientes(cli_id),--TABLA CLIENTES
    FOREIGN KEY (orden_empleado_id) REFERENCES empleados(empleado_id),--TABLA EMPLEADOS
    FOREIGN KEY (orden_serv_id) REFERENCES tipo_servicio(serv_id)--TABLA TIPO_SERVICIO
);