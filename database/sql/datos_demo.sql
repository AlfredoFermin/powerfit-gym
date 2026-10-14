-- PowerFit Gym · datos de demostración (se pueden ejecutar varias veces sin duplicar).
-- Contraseña de TODOS los usuarios de prueba: PowerFit2026
-- Uso: sudo mysql powerfit_gym < database/sql/datos_demo.sql

-- Usuarios -------------------------------------------------------------
INSERT IGNORE INTO usuarios (nombre, apellido, email, telefono, contrasena_hash, id_rol, activo)
SELECT 'Admin', 'PowerFit', 'admin@powerfit.test', '809-555-0100', '$2y$10$GxPhsnbbdNX0WIh6LD5qAemJnsbr.crDNk4JUBgyv.IgTHDq2IMiC', id_rol, 1 FROM roles WHERE nombre_rol = 'Administrador';
INSERT IGNORE INTO usuarios (nombre, apellido, email, telefono, contrasena_hash, id_rol, activo)
SELECT 'Rosa', 'Valdez', 'recepcion@powerfit.test', '809-555-0101', '$2y$10$GxPhsnbbdNX0WIh6LD5qAemJnsbr.crDNk4JUBgyv.IgTHDq2IMiC', id_rol, 1 FROM roles WHERE nombre_rol = 'Recepcionista';
INSERT IGNORE INTO usuarios (nombre, apellido, email, telefono, contrasena_hash, id_rol, activo)
SELECT 'Rafael', 'Castillo', 'entrenador1@powerfit.test', '809-555-0102', '$2y$10$GxPhsnbbdNX0WIh6LD5qAemJnsbr.crDNk4JUBgyv.IgTHDq2IMiC', id_rol, 1 FROM roles WHERE nombre_rol = 'Entrenador';
INSERT IGNORE INTO usuarios (nombre, apellido, email, telefono, contrasena_hash, id_rol, activo)
SELECT 'Daniela', 'Ortiz', 'entrenador2@powerfit.test', '809-555-0103', '$2y$10$GxPhsnbbdNX0WIh6LD5qAemJnsbr.crDNk4JUBgyv.IgTHDq2IMiC', id_rol, 1 FROM roles WHERE nombre_rol = 'Entrenador';
INSERT IGNORE INTO usuarios (nombre, apellido, email, telefono, contrasena_hash, id_rol, activo)
SELECT 'Carlos', 'Mejía', 'miembro1@powerfit.test', '809-555-0111', '$2y$10$GxPhsnbbdNX0WIh6LD5qAemJnsbr.crDNk4JUBgyv.IgTHDq2IMiC', id_rol, 1 FROM roles WHERE nombre_rol = 'Miembro';
INSERT IGNORE INTO usuarios (nombre, apellido, email, telefono, contrasena_hash, id_rol, activo)
SELECT 'Laura', 'Peña', 'miembro2@powerfit.test', '809-555-0112', '$2y$10$GxPhsnbbdNX0WIh6LD5qAemJnsbr.crDNk4JUBgyv.IgTHDq2IMiC', id_rol, 1 FROM roles WHERE nombre_rol = 'Miembro';
INSERT IGNORE INTO usuarios (nombre, apellido, email, telefono, contrasena_hash, id_rol, activo)
SELECT 'Miguel', 'Santos', 'miembro3@powerfit.test', '809-555-0113', '$2y$10$GxPhsnbbdNX0WIh6LD5qAemJnsbr.crDNk4JUBgyv.IgTHDq2IMiC', id_rol, 1 FROM roles WHERE nombre_rol = 'Miembro';
INSERT IGNORE INTO usuarios (nombre, apellido, email, telefono, contrasena_hash, id_rol, activo)
SELECT 'Ana', 'Rodríguez', 'miembro4@powerfit.test', '809-555-0114', '$2y$10$GxPhsnbbdNX0WIh6LD5qAemJnsbr.crDNk4JUBgyv.IgTHDq2IMiC', id_rol, 1 FROM roles WHERE nombre_rol = 'Miembro';

-- Tipos de membresía ---------------------------------------------------
INSERT INTO tipos_membresia (nombre, duracion_dias, precio)
SELECT 'Mensual', 30, 1500 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tipos_membresia WHERE nombre = 'Mensual');
INSERT INTO tipos_membresia (nombre, duracion_dias, precio)
SELECT 'Trimestral', 90, 4000 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tipos_membresia WHERE nombre = 'Trimestral');
INSERT INTO tipos_membresia (nombre, duracion_dias, precio)
SELECT 'Anual', 365, 14000 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM tipos_membresia WHERE nombre = 'Anual');

-- Membresías (una por miembro, si aún no tiene) -------------------------
INSERT INTO membresias (id_usuario, id_tipo, fecha_inicio, fecha_fin, estado)
SELECT u.id_usuario, t.id_tipo, DATE_ADD(CURDATE(), INTERVAL -25 DAY), DATE_ADD(DATE_ADD(CURDATE(), INTERVAL -25 DAY), INTERVAL t.duracion_dias DAY), 'activa'
FROM usuarios u JOIN tipos_membresia t ON t.nombre = 'Mensual'
WHERE u.email = 'miembro1@powerfit.test' AND NOT EXISTS (SELECT 1 FROM membresias x WHERE x.id_usuario = u.id_usuario);
INSERT INTO membresias (id_usuario, id_tipo, fecha_inicio, fecha_fin, estado)
SELECT u.id_usuario, t.id_tipo, DATE_ADD(CURDATE(), INTERVAL -10 DAY), DATE_ADD(DATE_ADD(CURDATE(), INTERVAL -10 DAY), INTERVAL t.duracion_dias DAY), 'activa'
FROM usuarios u JOIN tipos_membresia t ON t.nombre = 'Trimestral'
WHERE u.email = 'miembro2@powerfit.test' AND NOT EXISTS (SELECT 1 FROM membresias x WHERE x.id_usuario = u.id_usuario);
INSERT INTO membresias (id_usuario, id_tipo, fecha_inicio, fecha_fin, estado)
SELECT u.id_usuario, t.id_tipo, DATE_ADD(CURDATE(), INTERVAL -100 DAY), DATE_ADD(DATE_ADD(CURDATE(), INTERVAL -100 DAY), INTERVAL t.duracion_dias DAY), 'activa'
FROM usuarios u JOIN tipos_membresia t ON t.nombre = 'Anual'
WHERE u.email = 'miembro3@powerfit.test' AND NOT EXISTS (SELECT 1 FROM membresias x WHERE x.id_usuario = u.id_usuario);
INSERT INTO membresias (id_usuario, id_tipo, fecha_inicio, fecha_fin, estado)
SELECT u.id_usuario, t.id_tipo, DATE_ADD(CURDATE(), INTERVAL -40 DAY), DATE_ADD(DATE_ADD(CURDATE(), INTERVAL -40 DAY), INTERVAL t.duracion_dias DAY), 'vencida'
FROM usuarios u JOIN tipos_membresia t ON t.nombre = 'Mensual'
WHERE u.email = 'miembro4@powerfit.test' AND NOT EXISTS (SELECT 1 FROM membresias x WHERE x.id_usuario = u.id_usuario);

-- Pagos (uno por membresía, si aún no tiene) ----------------------------
INSERT INTO pagos (id_membresia, monto, fecha_pago, metodo_pago)
SELECT ms.id_membresia, t.precio, ms.fecha_inicio, 'efectivo'
FROM membresias ms JOIN usuarios u ON u.id_usuario = ms.id_usuario JOIN tipos_membresia t ON t.id_tipo = ms.id_tipo
WHERE u.email = 'miembro1@powerfit.test' AND NOT EXISTS (SELECT 1 FROM pagos p WHERE p.id_membresia = ms.id_membresia);
INSERT INTO pagos (id_membresia, monto, fecha_pago, metodo_pago)
SELECT ms.id_membresia, t.precio, ms.fecha_inicio, 'tarjeta'
FROM membresias ms JOIN usuarios u ON u.id_usuario = ms.id_usuario JOIN tipos_membresia t ON t.id_tipo = ms.id_tipo
WHERE u.email = 'miembro2@powerfit.test' AND NOT EXISTS (SELECT 1 FROM pagos p WHERE p.id_membresia = ms.id_membresia);
INSERT INTO pagos (id_membresia, monto, fecha_pago, metodo_pago)
SELECT ms.id_membresia, t.precio, ms.fecha_inicio, 'transferencia'
FROM membresias ms JOIN usuarios u ON u.id_usuario = ms.id_usuario JOIN tipos_membresia t ON t.id_tipo = ms.id_tipo
WHERE u.email = 'miembro3@powerfit.test' AND NOT EXISTS (SELECT 1 FROM pagos p WHERE p.id_membresia = ms.id_membresia);
INSERT INTO pagos (id_membresia, monto, fecha_pago, metodo_pago)
SELECT ms.id_membresia, t.precio, ms.fecha_inicio, 'efectivo'
FROM membresias ms JOIN usuarios u ON u.id_usuario = ms.id_usuario JOIN tipos_membresia t ON t.id_tipo = ms.id_tipo
WHERE u.email = 'miembro4@powerfit.test' AND NOT EXISTS (SELECT 1 FROM pagos p WHERE p.id_membresia = ms.id_membresia);

-- Asistencia (una visita de hoy por miembro activo, si no tiene registros) -
INSERT INTO asistencias (id_usuario, fecha_hora)
SELECT u.id_usuario, NOW() FROM usuarios u
WHERE u.email = 'miembro1@powerfit.test' AND NOT EXISTS (SELECT 1 FROM asistencias a WHERE a.id_usuario = u.id_usuario);
INSERT INTO asistencias (id_usuario, fecha_hora)
SELECT u.id_usuario, NOW() FROM usuarios u
WHERE u.email = 'miembro2@powerfit.test' AND NOT EXISTS (SELECT 1 FROM asistencias a WHERE a.id_usuario = u.id_usuario);
INSERT INTO asistencias (id_usuario, fecha_hora)
SELECT u.id_usuario, NOW() FROM usuarios u
WHERE u.email = 'miembro3@powerfit.test' AND NOT EXISTS (SELECT 1 FROM asistencias a WHERE a.id_usuario = u.id_usuario);

-- Clases grupales ------------------------------------------------------
INSERT INTO clases (nombre, id_entrenador, horario, capacidad_maxima)
SELECT 'Spinning', u.id_usuario, 'Lun y Mié 6:00 PM', 20 FROM usuarios u
WHERE u.email = 'entrenador1@powerfit.test' AND NOT EXISTS (SELECT 1 FROM clases c WHERE c.nombre = 'Spinning');
INSERT INTO clases (nombre, id_entrenador, horario, capacidad_maxima)
SELECT 'Yoga', u.id_usuario, 'Mar y Jue 7:00 AM', 15 FROM usuarios u
WHERE u.email = 'entrenador2@powerfit.test' AND NOT EXISTS (SELECT 1 FROM clases c WHERE c.nombre = 'Yoga');
