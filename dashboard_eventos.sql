create database dashboard_eventos;

use dashboard_eventos; 

CREATE TABLE eventos (
    id_evento INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL COMMENT 'Nombre del evento',
    descripcion TEXT COMMENT 'Descripción detallada del evento',
    departamento VARCHAR(50) COMMENT 'Departamento donde se realiza',
    municipio INT(11) COMMENT 'ID del municipio',
    id_organizador INT(20) UNSIGNED COMMENT 'ID del usuario organizador',
    ubicacion TEXT COMMENT 'Ubicación específica del evento',
    direccion VARCHAR(100) COMMENT 'Dirección física del evento',
    imagen_principal VARCHAR(1000) COMMENT 'URL de la imagen principal',
    imagen_secundaria VARCHAR(1000) COMMENT 'URL de una imagen secundaria',
    imagen_ubicacion VARCHAR(1000) COMMENT 'URL de una imagen de la ubicación',
    fecha_inicio DATETIME COMMENT 'Fecha y hora de inicio del evento',
    fecha_fin DATETIME COMMENT 'Fecha y hora de finalización del evento',
    tipo VARCHAR(100) COMMENT 'Tipo del evento',
    categoria INT(11) COMMENT 'ID de la categoría del evento',
    subcategoria INT(11) COMMENT 'ID de la subcategoría del evento',
    aforo_maximo INT(11) COMMENT 'Capacidad máxima de asistentes',
    contador_visitas INT(11) COMMENT 'Contador de visitas al evento',
    calificacion DECIMAL(3,2) COMMENT 'Calificación promedio del evento',
    precio_entrada DECIMAL(10,2) COMMENT 'Precio de entrada al evento',
    es_gratuito TINYINT(1) DEFAULT 0 COMMENT '1 si es gratuito, 0 si es pagado',
    estado ENUM('pendiente_aprobacion', 'activo', 'pospuesto', 'cancelado') DEFAULT 'pendiente_aprobacion' COMMENT 'Estado actual del evento',
    cupos_disponibles INT(11) COMMENT 'Número de cupos disponibles',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del registro',
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización'
);

CREATE TABLE registro_asistencia_evento (
    id_registro INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_evento INT(11) NOT NULL COMMENT 'ID del evento al que se registra',
    id_usuario INT(11) NOT NULL COMMENT 'ID del usuario que se registra',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora del registro',
    asistio TINYINT(1) DEFAULT 0 COMMENT '1 si asistió, 0 si no asistió',
    qr TEXT COMMENT 'Código QR generado para el asistente',

    FOREIGN KEY (id_evento) REFERENCES eventos(id_evento) ON DELETE CASCADE
);

INSERT INTO eventos (nombre, descripcion, departamento, municipio, id_organizador, ubicacion, direccion, fecha_inicio, fecha_fin, categoria, subcategoria, aforo_maximo, contador_visitas, precio_entrada, es_gratuito, estado, cupos_disponibles) VALUES
('TechConf 2025', 'Conferencia anual sobre las últimas tendencias en tecnología y desarrollo de software', 'CUNDINAMARCA', 1, 1, 'Centro de Convenciones Compensar', 'Av. 68 # 49A-47', '2025-06-15 09:00:00', '2025-06-16 18:00:00', 1, 2, 500, 4, 180000.00, 0, 'activo', 500),

('Festival Música Viva', 'El festival de música independiente más grande de la región', 'ANTIOQUIA', 30, 2, 'Parque Norte', 'Calle 73 # 52-25', '2025-07-20 14:00:00', '2025-07-20 23:00:00', 2, 4, 2000, 9, 120000.00, 0, 'activo', 1500),

('Workshop Emprende+', 'Taller práctico para emprendedores que quieren llevar sus ideas al siguiente nivel', 'VALLE DEL CAUCA', 0, 3, 'Hotel Spiwak', 'Av. 6D Norte # 36N-18', '2025-05-10 08:00:00', '2025-05-10 17:00:00', 3, 7, 150, 2, 50000.00, 0, 'activo', 120),

('Salud Mental en el Trabajo', 'Charla informativa sobre la importancia de la salud mental en entornos laborales', 'ATLANTICO', 146, 4, 'Biblioteca Piloto del Caribe', 'Calle 36 # 46-66', '2025-04-30 10:00:00', '2025-04-30 12:00:00', 4, 9, 80, 27, 0.00, 1, 'activo', 65),

('Maratón por la Vida', 'Carrera 10K para recaudar fondos para la fundación contra el cáncer', 'SANTANDER', 0, 5, 'Parque del Agua', 'Calle 30 # 25-15', '2024-12-05 06:00:00', '2024-12-05 12:00:00', 5, 11, 1000, 8, 35000.00, 0, 'activo', 0),

('ExpoCareers 2025', 'La mayor feria de empleo y oportunidades profesionales', 'RISARALDA', 895, 6, 'Centro de Convenciones Expo Futuro', 'Calle 49 # 10-90', '2025-08-18 08:00:00', '2025-08-18 18:00:00', 6, 12, 3000, 0, 0.00, 1, 'pospuesto', 3000);

INSERT INTO registro_asistencia_evento (id_evento, id_usuario, fecha_registro, asistio, qr) VALUES

(1, 101, '2025-05-20 08:30:00', 1, 'QR-TECH-001'),
(1, 102, '2025-05-20 09:15:00', 1, 'QR-TECH-002'),
(1, 103, '2025-05-20 10:45:00', 0, 'QR-TECH-003'),
(1, 104, '2025-05-20 14:20:00', 1, 'QR-TECH-004'),
(1, 105, '2025-05-20 16:30:00', 1, 'QR-TECH-005'),


(2, 201, '2025-05-22 11:00:00', 1, 'QR-MUSIC-001'),
(2, 202, '2025-05-22 13:30:00', 1, 'QR-MUSIC-002'),
(2, 203, '2025-05-22 15:45:00', 0, 'QR-MUSIC-003'),
(2, 204, '2025-05-22 17:20:00', 1, 'QR-MUSIC-004'),


(3, 301, '2025-05-18 09:00:00', 1, 'QR-WORK-001'),
(3, 302, '2025-05-18 10:30:00', 1, 'QR-WORK-002'),
(3, 303, '2025-05-18 12:00:00', 0, 'QR-WORK-003'),


(4, 401, '2025-05-25 08:45:00', 1, 'QR-SALUD-001'),
(4, 402, '2025-05-25 09:30:00', 1, 'QR-SALUD-002'),
(4, 403, '2025-05-25 11:15:00', 1, 'QR-SALUD-003'),
(4, 404, '2025-05-25 14:00:00', 0, 'QR-SALUD-004'),
(4, 405, '2025-05-25 16:45:00', 1, 'QR-SALUD-005');
