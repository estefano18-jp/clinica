-- Usando la base de datos
USE clinicaDB;

-- Insertando especialidades
INSERT INTO especialidades (especialidad, precioatencion) VALUES 
('Medicina General', 50.00),
('Cardiología', 120.00),
('Pediatría', 80.00),
('Ginecología', 100.00),
('Traumatología', 90.00),
('Dermatología', 85.00),
('Oftalmología', 75.00),
('Neurología', 150.00),
('Psiquiatría', 130.00),
('Odontología', 70.00);

-- Insertando diagnósticos
INSERT INTO diagnosticos (nombre, descripcion, codigo) VALUES 
('Hipertensión Arterial', 'Presión arterial alta sostenida', 'HTA001'),
('Diabetes Mellitus Tipo 2', 'Trastorno metabólico que causa niveles elevados de azúcar en sangre', 'DMT2001'),
('Asma Bronquial', 'Enfermedad crónica caracterizada por inflamación de las vías respiratorias', 'ASM001'),
('Gastritis Crónica', 'Inflamación persistente del revestimiento del estómago', 'GST001'),
('Migraña', 'Dolores de cabeza recurrentes moderados a intensos', 'MIG001'),
('Rinitis Alérgica', 'Inflamación de la mucosa nasal por alérgenos', 'RIN001'),
('Artrosis', 'Degeneración del cartílago articular', 'ART001'),
('Hipotiroidismo', 'Producción insuficiente de hormonas tiroideas', 'HPT001'),
('Depresión', 'Trastorno del estado de ánimo caracterizado por tristeza persistente', 'DEP001'),
('Ansiedad Generalizada', 'Preocupación y temor excesivos y persistentes', 'ANS001');

-- Insertando alergias
INSERT INTO alergias (tipoalergia, alergia) VALUES 
('Medicamento', 'Penicilina'),
('Medicamento', 'Aspirina'),
('Medicamento', 'Sulfas'),
('Alimento', 'Maní'),
('Alimento', 'Mariscos'),
('Alimento', 'Lácteos'),
('Ambiental', 'Polen'),
('Ambiental', 'Ácaros'),
('Ambiental', 'Pelo de Mascotas'),
('Picadura', 'Abeja');

-- Insertando tipos de servicios
INSERT INTO tiposervicio (tiposervicio, servicio, precioservicio) VALUES 
('Laboratorio', 'Hemograma Completo', 35.00),
('Laboratorio', 'Perfil Lipídico', 45.00),
('Laboratorio', 'Glucosa en Ayunas', 20.00),
('Laboratorio', 'Examen de Orina', 25.00),
('Imagen', 'Radiografía', 80.00),
('Imagen', 'Ecografía', 120.00),
('Imagen', 'Tomografía', 250.00),
('Procedimiento', 'Curaciones', 40.00),
('Procedimiento', 'Nebulización', 30.00),
('Procedimiento', 'Inyectables', 15.00);

-- Insertando empresas
INSERT INTO empresas (razonsocial, ruc, direccion, nombrecomercial, telefono, email) VALUES 
('SEGUROS RIMAC S.A.', '20100041953', 'Av. Paseo de la República 3505, San Isidro', 'RIMAC SEGUROS', '01-4111111', 'contacto@rimac.com.pe'),
('PACÍFICO SEGUROS', '20332970411', 'Av. Juan de Arona 830, San Isidro', 'PACÍFICO', '01-5135000', 'atencion@pacifico.com.pe'),
('MAPFRE PERÚ', '20202380621', 'Av. 28 de Julio 873, Miraflores', 'MAPFRE', '01-2137373', 'servicioalcliente@mapfre.com.pe'),
('INTERSEGURO COMPAÑÍA DE SEGUROS S.A.', '20382748566', 'Av. Pardo y Aliaga 634, San Isidro', 'INTERSEGURO', '01-6193900', 'servicioalcliente@interseguro.com.pe'),
('LA POSITIVA SEGUROS', '20100210909', 'Calle Francisco Masías 370, San Isidro', 'LA POSITIVA', '01-2110000', 'atencion@lapositiva.com.pe');

-- Insertando personas (administradores, doctores, enfermeras, pacientes)
-- Administradores
INSERT INTO personas (apellidos, nombres, tipodoc, nrodoc, telefono, fechanacimiento, genero, direccion, email) VALUES 
('Rodríguez Silva', 'Carlos Alberto', 'DNI', '45678912', '987654321', '1985-06-15', 'M', 'Av. Arequipa 1250, Lince', 'carlos.rodriguez@clinica.com'),
('Mendoza Huamán', 'María Elena', 'DNI', '40123456', '987123456', '1980-03-22', 'F', 'Calle Los Pinos 450, Miraflores', 'maria.mendoza@clinica.com');

-- Doctores
INSERT INTO personas (apellidos, nombres, tipodoc, nrodoc, telefono, fechanacimiento, genero, direccion, email) VALUES 
('Sánchez Torres', 'Juan Carlos', 'DNI', '30456789', '999888777', '1975-09-20', 'M', 'Av. La Marina 1050, San Miguel', 'juan.sanchez@clinica.com'),
('Pérez García', 'Ana María', 'DNI', '29876543', '999777666', '1978-11-05', 'F', 'Calle Las Flores 240, San Borja', 'ana.perez@clinica.com'),
('Fernández Castro', 'Roberto José', 'DNI', '31234567', '999666555', '1982-04-18', 'M', 'Av. Brasil 890, Jesús María', 'roberto.fernandez@clinica.com'),
('López Díaz', 'Carmen Rosa', 'DNI', '28765432', '999555444', '1980-07-25', 'F', 'Calle Los Olivos 180, Surco', 'carmen.lopez@clinica.com'),
('García Mendoza', 'Pedro Raúl', 'DNI', '27654321', '999444333', '1976-12-30', 'M', 'Jr. Huallaga 450, Centro de Lima', 'pedro.garcia@clinica.com');

-- Enfermeras
INSERT INTO personas (apellidos, nombres, tipodoc, nrodoc, telefono, fechanacimiento, genero, direccion, email) VALUES 
('Torres Ramos', 'Luisa Fernanda', 'DNI', '36543210', '988777666', '1988-05-12', 'F', 'Jr. Los Halcones 320, Surquillo', 'luisa.torres@clinica.com'),
('Ramos Quispe', 'Juana María', 'DNI', '35432109', '988666555', '1990-03-08', 'F', 'Av. Venezuela 780, Breña', 'juana.ramos@clinica.com');

-- Pacientes
INSERT INTO personas (apellidos, nombres, tipodoc, nrodoc, telefono, fechanacimiento, genero, direccion, email) VALUES 
('Flores Morales', 'Miguel Ángel', 'DNI', '10293847', '976543210', '1990-08-25', 'M', 'Calle Los Nogales 123, La Molina', 'miguel.flores@gmail.com'),
('Castro Lara', 'Lucía Beatriz', 'DNI', '20394857', '976432109', '1988-04-15', 'F', 'Av. Benavides 456, Miraflores', 'lucia.castro@gmail.com'),
('Díaz Romero', 'José Manuel', 'DNI', '30485967', '976321098', '1975-11-20', 'M', 'Jr. Unión 789, Barranco', 'jose.diaz@gmail.com'),
('Romero Silva', 'Claudia Patricia', 'DNI', '40586978', '976210987', '1982-06-10', 'F', 'Calle Las Begonias 234, San Isidro', 'claudia.romero@gmail.com'),
('Silva Paredes', 'Ricardo Antonio', 'DNI', '50687989', '976109876', '1978-09-05', 'M', 'Av. Javier Prado 567, San Borja', 'ricardo.silva@gmail.com'),
('Paredes Ríos', 'Mariana Isabel', 'DNI', '60788990', '976098765', '1995-03-28', 'F', 'Jr. Huáscar 890, Pueblo Libre', 'mariana.paredes@gmail.com'),
('Ríos Vargas', 'Daniel Eduardo', 'DNI', '70889001', '976987654', '1992-12-15', 'M', 'Calle Los Eucaliptos 123, La Molina', 'daniel.rios@gmail.com'),
('Vargas Rojas', 'Gabriela Sofía', 'DNI', '80990112', '976876543', '1985-07-22', 'F', 'Av. Angamos 456, Surquillo', 'gabriela.vargas@gmail.com'),
('Rojas Medina', 'Fernando José', 'DNI', '91001223', '976765432', '1970-05-18', 'M', 'Jr. Cusco 789, Magdalena', 'fernando.rojas@gmail.com'),
('Medina Chávez', 'Patricia Elena', 'DNI', '10112334', '976654321', '1980-10-30', 'F', 'Calle Los Jazmines 234, San Miguel', 'patricia.medina@gmail.com');

-- Insertando colaboradores
-- Administradores
INSERT INTO colaboradores (idpersona, idespecialidad) VALUES 
(1, NULL),  -- Carlos Rodríguez (Administrador)
(2, NULL);  -- María Mendoza (Administradora)

-- Doctores
INSERT INTO colaboradores (idpersona, idespecialidad) VALUES 
(3, 1),  -- Juan Sánchez (Médico General)
(4, 4),  -- Ana Pérez (Ginecóloga)
(5, 2),  -- Roberto Fernández (Cardiólogo)
(6, 3),  -- Carmen López (Pediatra)
(7, 5);  -- Pedro García (Traumatólogo)

-- Enfermeras
INSERT INTO colaboradores (idpersona, idespecialidad) VALUES 
(8, 1),  -- Luisa Torres (Medicina General)
(9, 1);  -- Juana Ramos (Medicina General)

-- Insertando contratos
INSERT INTO contratos (idcolaborador, fechainicio, fechafin, tipocontrato) VALUES 
(1, '2022-01-01', NULL, 'INDEFINIDO'),  -- Carlos Rodríguez
(2, '2022-01-01', NULL, 'INDEFINIDO'),  -- María Mendoza
(3, '2022-02-01', NULL, 'INDEFINIDO'),  -- Juan Sánchez
(4, '2022-02-15', NULL, 'INDEFINIDO'),  -- Ana Pérez
(5, '2022-03-01', NULL, 'INDEFINIDO'),  -- Roberto Fernández
(6, '2022-03-15', NULL, 'INDEFINIDO'),  -- Carmen López
(7, '2022-04-01', NULL, 'INDEFINIDO'),  -- Pedro García
(8, '2022-04-15', NULL, 'INDEFINIDO'),  -- Luisa Torres
(9, '2022-05-01', NULL, 'INDEFINIDO');  -- Juana Ramos

-- Insertando usuarios
-- Contraseñas en este ejemplo son las primeras 6 letras del nombre en minúsculas con '123' al final
-- En un entorno real, se utilizaría una función de hash adecuada como bcrypt o Argon2
INSERT INTO usuarios (idcontrato, nomuser, passuser, estado) VALUES 
(1, 'admin.carlos', '$2y$10$5I8VF.z1V0iCJc/WN.1QT.HFZ9KXF5E2QJUijVZXPpKW3BBUj5ZEG', TRUE),  -- carlos123
(2, 'admin.maria', '$2y$10$FDR2gHnwDO4jr0wU1ZKiMegxiMr4rKbxUi0hnGwGqA.ZgbOq5zM0O', TRUE),   -- maria123
(3, 'doctor.juan', '$2y$10$PWuAdHvT5.fbQ/TDaJEwnOmGrfK9ezvgAxl6zTwzxo0aLGrB2wNT.', TRUE),   -- juanca123
(4, 'doctora.ana', '$2y$10$JdgA3QF/FNPnBWsArcKgzeZA1qLnXYm5GQgM4lD4FvY9q7nWvQIry', TRUE),   -- anamar123
(5, 'doctor.roberto', '$2y$10$kvdEiF8gw/DGvr3.Qkr8t.rPcL5QqfgBvz3VD5pJNd2.oBdcXBfV.', TRUE), -- robert123
(6, 'doctora.carmen', '$2y$10$9w6a.RqbLZnJwA/uQY0bseIaE.JdGa8BO.mOXK4xQEZJJAXwbYBwC', TRUE), -- carmen123
(7, 'doctor.pedro', '$2y$10$ZT9PQRGkxaKdKbIWOaKzfO0WLMQTYJGEzBQfS/r6KTQOYKqWwU2O.', TRUE),   -- pedrora123
(8, 'enfermera.luisa', '$2y$10$8WJXw/0D1PnqKUE/GHoYoe3Yjxw/i.eYZBhYPQxZv8R4tFm6XEWJS', TRUE), -- luisaf123
(9, 'enfermera.juana', '$2y$10$0Wq5c2zZTZ9rQzx7J/2mUOZbXlQ0pGAGHftC5STc6YRrq7B9sCvVO', TRUE); -- juanam123

-- Insertando pacientes
INSERT INTO pacientes (idpersona, fecharegistro) VALUES 
(10, '2023-01-10'),  -- Miguel Flores
(11, '2023-01-15'),  -- Lucía Castro
(12, '2023-02-05'),  -- José Díaz
(13, '2023-02-20'),  -- Claudia Romero
(14, '2023-03-08'),  -- Ricardo Silva
(15, '2023-03-25'),  -- Mariana Paredes
(16, '2023-04-12'),  -- Daniel Ríos
(17, '2023-04-30'),  -- Gabriela Vargas
(18, '2023-05-15'),  -- Fernando Rojas
(19, '2023-05-28');  -- Patricia Medina

-- Insertando atenciones (días de atención de médicos)
INSERT INTO atenciones (idcontrato, diasemana) VALUES 
(3, 'LUNES'),    -- Juan Sánchez (Lunes)
(3, 'MIERCOLES'), -- Juan Sánchez (Miércoles)
(3, 'VIERNES'),  -- Juan Sánchez (Viernes)
(4, 'MARTES'),   -- Ana Pérez (Martes)
(4, 'JUEVES'),   -- Ana Pérez (Jueves)
(5, 'LUNES'),    -- Roberto Fernández (Lunes)
(5, 'JUEVES'),   -- Roberto Fernández (Jueves)
(6, 'MARTES'),   -- Carmen López (Martes)
(6, 'VIERNES'),  -- Carmen López (Viernes)
(7, 'MIERCOLES'), -- Pedro García (Miércoles)
(7, 'SABADO');   -- Pedro García (Sábado)

-- Insertando horarios
INSERT INTO horarios (idatencion, horainicio, horafin) VALUES 
(1, '08:00:00', '13:00:00'),  -- Juan Sánchez (Lunes mañana)
(2, '14:00:00', '20:00:00'),  -- Juan Sánchez (Miércoles tarde)
(3, '08:00:00', '13:00:00'),  -- Juan Sánchez (Viernes mañana)
(4, '08:00:00', '13:00:00'),  -- Ana Pérez (Martes mañana)
(5, '14:00:00', '20:00:00'),  -- Ana Pérez (Jueves tarde)
(6, '14:00:00', '20:00:00'),  -- Roberto Fernández (Lunes tarde)
(7, '08:00:00', '13:00:00'),  -- Roberto Fernández (Jueves mañana)
(8, '14:00:00', '20:00:00'),  -- Carmen López (Martes tarde)
(9, '08:00:00', '13:00:00'),  -- Carmen López (Viernes mañana)
(10, '08:00:00', '13:00:00'), -- Pedro García (Miércoles mañana)
(11, '09:00:00', '14:00:00'); -- Pedro García (Sábado)

-- Insertando clientes
INSERT INTO clientes (tipocliente, idempresa, idpersona) VALUES 
('NATURAL', NULL, 10),  -- Miguel Flores
('NATURAL', NULL, 11),  -- Lucía Castro
('NATURAL', NULL, 12),  -- José Díaz
('NATURAL', NULL, 13),  -- Claudia Romero
('EMPRESA', 1, NULL),   -- Rímac Seguros
('EMPRESA', 2, NULL);   -- Pacífico Seguros

-- Insertando citas
INSERT INTO citas (fecha, hora, estado, observaciones, idpersona) VALUES 
('2023-06-01', '09:00:00', 'REALIZADA', 'Primera consulta del paciente', 10),
('2023-06-01', '10:00:00', 'REALIZADA', 'Control mensual', 11),
('2023-06-01', '11:00:00', 'REALIZADA', 'Consulta por dolor de cabeza', 12),
('2023-06-02', '09:00:00', 'REALIZADA', 'Consulta por dolor de garganta', 13),
('2023-06-02', '10:00:00', 'REALIZADA', 'Consulta de rutina', 14),
('2023-06-03', '15:00:00', 'REALIZADA', 'Control de presión arterial', 15),
('2023-06-05', '09:00:00', 'REALIZADA', 'Consulta por alergia', 16),
('2023-06-05', '10:00:00', 'REALIZADA', 'Control de embarazo', 17),
('2023-06-06', '15:00:00', 'REALIZADA', 'Consulta por dolor de espalda', 18),
('2023-06-07', '09:00:00', 'REALIZADA', 'Consulta por resfriado', 19),
('2023-06-08', '15:00:00', 'PROGRAMADA', 'Control', 10),
('2023-06-09', '09:00:00', 'PROGRAMADA', 'Control', 11),
('2023-06-12', '09:00:00', 'PROGRAMADA', 'Control', 12),
('2023-06-13', '15:00:00', 'PROGRAMADA', 'Control', 13);

-- Insertando consultas
INSERT INTO consultas (fecha, idhorario, horaprogramada, horaatencion, idpaciente, condicionpaciente, iddiagnostico) VALUES 
('2023-06-01', 1, '09:00:00', '09:10:00', 1, 'ESTABLE', 6),  -- Miguel Flores con Juan Sánchez - Rinitis Alérgica
('2023-06-01', 1, '10:00:00', '10:05:00', 2, 'ESTABLE', 4),  -- Lucía Castro con Juan Sánchez - Gastritis Crónica
('2023-06-01', 1, '11:00:00', '11:15:00', 3, 'ESTABLE', 5),  -- José Díaz con Juan Sánchez - Migraña
('2023-06-02', 4, '09:00:00', '09:05:00', 4, 'ESTABLE', 1),  -- Claudia Romero con Ana Pérez - Hipertensión Arterial
('2023-06-02', 4, '10:00:00', '10:10:00', 5, 'ESTABLE', 2),  -- Ricardo Silva con Ana Pérez - Diabetes Mellitus Tipo 2
('2023-06-05', 10, '09:00:00', '09:20:00', 6, 'ESTABLE', 7), -- Mariana Paredes con Pedro García - Artrosis
('2023-06-05', 10, '10:00:00', '10:15:00', 7, 'ESTABLE', 3), -- Daniel Ríos con Pedro García - Asma Bronquial
('2023-06-06', 8, '15:00:00', '15:05:00', 8, 'ESTABLE', 8),  -- Gabriela Vargas con Carmen López - Hipotiroidismo
('2023-06-07', 7, '09:00:00', '09:15:00', 9, 'ESTABLE', 10), -- Fernando Rojas con Roberto Fernández - Ansiedad Generalizada
('2023-06-03', 6, '15:00:00', '15:10:00', 10, 'ESTABLE', 9); -- Patricia Medina con Roberto Fernández - Depresión

-- Insertando recetas
INSERT INTO recetas (idconsulta, medicacion, cantidad, frecuencia) VALUES 
(1, 'Loratadina 10mg', '30 tabletas', 'Una vez al día por 30 días'),
(2, 'Omeprazol 20mg', '30 tabletas', 'Una vez al día en ayunas por 30 días'),
(3, 'Ibuprofeno 400mg', '20 tabletas', 'Cada 8 horas si hay dolor'),
(4, 'Enalapril 10mg', '30 tabletas', 'Una vez al día por 30 días'),
(5, 'Metformina 850mg', '60 tabletas', 'Una tableta cada 12 horas por 30 días'),
(6, 'Diclofenaco 50mg', '30 tabletas', 'Una tableta cada 8 horas por 10 días'),
(7, 'Salbutamol Inhalador', '1 inhalador', '2 inhalaciones cada 8 horas cuando sea necesario'),
(8, 'Levotiroxina 50mcg', '30 tabletas', 'Una tableta en ayunas por 30 días'),
(9, 'Alprazolam 0.5mg', '30 tabletas', 'Una tableta en la noche por 30 días'),
(10, 'Sertralina 50mg', '30 tabletas', 'Una tableta en la mañana por 30 días');

-- Actualizando la tabla tratamiento con los tratamientos de cada paciente
INSERT INTO tratamiento (medicacion, dosis, frecuencia, duracion, idreceta) VALUES 
('Loratadina', '10mg', 'Una vez al día', '30 días', 1),
('Omeprazol', '20mg', 'Una vez al día en ayunas', '30 días', 2),
('Ibuprofeno', '400mg', 'Cada 8 horas si hay dolor', '7 días', 3),
('Enalapril', '10mg', 'Una vez al día', '30 días', 4),
('Metformina', '850mg', 'Una tableta cada 12 horas', '30 días', 5),
('Diclofenaco', '50mg', 'Una tableta cada 8 horas', '10 días', 6),
('Salbutamol Inhalador', '2 inhalaciones', 'Cada 8 horas cuando sea necesario', 'Continuo', 7),
('Levotiroxina', '50mcg', 'Una tableta en ayunas', '30 días', 8),
('Alprazolam', '0.5mg', 'Una tableta en la noche', '30 días', 9),
('Sertralina', '50mg', 'Una tableta en la mañana', '30 días', 10);

-- Insertando triajes
INSERT INTO triajes (idconsulta, idenfermera, hora, temperatura, presionarterial, frecuenciacardiaca, saturacionoxigeno, peso, estatura) VALUES 
(1, 8, '09:00:00', 36.8, '120/80', 72, 98, 70.5, 1.75),
(2, 8, '10:00:00', 36.5, '110/70', 68, 99, 65.2, 1.65),
(3, 8, '11:00:00', 37.2, '125/85', 75, 97, 80.0, 1.78),
(4, 9, '09:00:00', 36.7, '140/90', 80, 98, 68.5, 1.60),
(5, 9, '10:00:00', 36.9, '130/85', 76, 97, 85.5, 1.72),
(6, 8, '09:00:00', 36.6, '120/80', 70, 99, 62.0, 1.58),
(7, 8, '10:00:00', 37.0, '115/75', 74, 96, 75.8, 1.80),
(8, 9, '15:00:00', 36.5, '110/70', 65, 99, 58.5, 1.62),
(9, 9, '09:00:00', 36.8, '130/85', 78, 98, 90.0, 1.85),
(10, 8, '15:00:00', 36.7, '120/75', 72, 98, 63.0, 1.67);

-- Insertando listaalergias
INSERT INTO listaalergias (idpersona, idalergia, gravedad) VALUES 
(10, 1, 'GRAVE'),    -- Miguel Flores - Alergia a Penicilina
(11, 5, 'MODERADA'), -- Lucía Castro - Alergia a Mariscos
(13, 2, 'LEVE'),     -- Claudia Romero - Alergia a Aspirina
(15, 7, 'MODERADA'), -- Mariana Paredes - Alergia a Polen
(18, 10, 'GRAVE');   -- Fernando Rojas - Alergia a Picadura de Abeja

-- Insertando serviciosrequeridos
INSERT INTO serviciosrequeridos (idconsulta, idtiposervicio, solicitud, fechaanalisis, fechaprocesamiento, fechaentrega) VALUES 
(1, 3, 'Medir nivel de glucosa en ayunas', '2023-06-02', '2023-06-02', '2023-06-02'),
(2, 2, 'Evaluación de colesterol y triglicéridos', '2023-06-02', '2023-06-02', '2023-06-03'),
(3, 5, 'Radiografía de cráneo', '2023-06-02', '2023-06-02', '2023-06-02'),
(4, 1, 'Hemograma completo', '2023-06-03', '2023-06-03', '2023-06-04'),
(5, 3, 'Control de glucosa', '2023-06-03', '2023-06-03', '2023-06-03'),
(6, 5, 'Radiografía de columna lumbar', '2023-06-06', '2023-06-06', '2023-06-06'),
(7, 9, 'Nebulización para crisis asmática', '2023-06-05', '2023-06-05', '2023-06-05'),
(8, 1, 'Hemograma completo', '2023-06-07', '2023-06-07', '2023-06-08'),
(9, 10, 'Administración de sedante', '2023-06-07', '2023-06-07', '2023-06-07'),
(10, 2, 'Perfil lipídico completo', '2023-06-04', '2023-06-04', '2023-06-05');

-- Insertando resultados
INSERT INTO resultados (idserviciorequerido, caracteristicaevaluada, condicion, rutaimagen) VALUES 
(1, 'Glucosa', 'Valor: 85 mg/dL - Normal', NULL),
(2, 'Colesterol Total', 'Valor: 210 mg/dL - Ligeramente elevado', NULL),
(2, 'Triglicéridos', 'Valor: 150 mg/dL - Normal', NULL),
(2, 'HDL', 'Valor: 45 mg/dL - Normal', NULL),
(2, 'LDL', 'Valor: 135 mg/dL - Ligeramente elevado', NULL),
(3, 'Imagen de cráneo', 'Sin hallazgos patológicos', '/imagenes/radiografias/craneo_20230602_001.jpg'),
(4, 'Hemoglobina', 'Valor: 14.5 g/dL - Normal', NULL),
(4, 'Leucocitos', 'Valor: 7500 /mm³ - Normal', NULL),
(4, 'Plaquetas', 'Valor: 250000 /mm³ - Normal', NULL),
(5, 'Glucosa', 'Valor: 130 mg/dL - Elevado', NULL),
(6, 'Columna lumbar', 'Se observa disminución del espacio intervertebral L4-L5', '/imagenes/radiografias/columna_20230606_001.jpg'),
(7, 'Evaluación post-nebulización', 'Mejora de la función respiratoria. Saturación mejorada a 98%', NULL),
(8, 'Hemoglobina', 'Valor: 12.8 g/dL - Normal', NULL),
(8, 'Leucocitos', 'Valor: 6800 /mm³ - Normal', NULL),
(8, 'Plaquetas', 'Valor: 230000 /mm³ - Normal', NULL),
(9, 'Evaluación post-medicación', 'Paciente muestra reducción de síntomas de ansiedad', NULL),
(10, 'Colesterol Total', 'Valor: 190 mg/dL - Normal', NULL),
(10, 'Triglicéridos', 'Valor: 130 mg/dL - Normal', NULL),
(10, 'HDL', 'Valor: 50 mg/dL - Normal', NULL),
(10, 'LDL', 'Valor: 114 mg/dL - Normal', NULL);

-- Insertando ventas (facturas por atención)
INSERT INTO ventas (idcliente, tipodoc, nrodocumento, fechaemision, fecharegistro, tipopago, idusuariocaja) VALUES 
(1, 'BOLETA', 'B001-00001', '2023-06-01 13:30:00', '2023-06-01 13:30:00', 'EFECTIVO', 1),
(2, 'BOLETA', 'B001-00002', '2023-06-01 14:00:00', '2023-06-01 14:00:00', 'TARJETA', 1),
(3, 'BOLETA', 'B001-00003', '2023-06-01 14:30:00', '2023-06-01 14:30:00', 'EFECTIVO', 1),
(4, 'BOLETA', 'B001-00004', '2023-06-02 13:30:00', '2023-06-02 13:30:00', 'TARJETA', 2),
(5, 'FACTURA', 'F001-00001', '2023-06-02 14:00:00', '2023-06-02 14:00:00', 'TRANSFERENCIA', 2),
(1, 'BOLETA', 'B001-00005', '2023-06-03 20:30:00', '2023-06-03 20:30:00', 'EFECTIVO', 1),
(2, 'BOLETA', 'B001-00006', '2023-06-05 13:30:00', '2023-06-05 13:30:00', 'TARJETA', 2),
(3, 'BOLETA', 'B001-00007', '2023-06-05 14:00:00', '2023-06-05 14:00:00', 'EFECTIVO', 2),
(4, 'BOLETA', 'B001-00008', '2023-06-06 20:30:00', '2023-06-06 20:30:00', 'TARJETA', 1),
(5, 'FACTURA', 'F001-00002', '2023-06-07 13:30:00', '2023-06-07 13:30:00', 'TRANSFERENCIA', 2);

-- Insertando detalleventas
INSERT INTO detalleventas (idventa, idconsulta, idserviciorequerido, precio) VALUES 
(1, 1, NULL, 50.00),  -- Consulta de Miguel Flores con Juan Sánchez
(1, NULL, 1, 20.00),  -- Servicio de glucosa para Miguel Flores
(2, 2, NULL, 50.00),  -- Consulta de Lucía Castro con Juan Sánchez
(2, NULL, 2, 45.00),  -- Servicio de perfil lipídico para Lucía Castro
(3, 3, NULL, 50.00),  -- Consulta de José Díaz con Juan Sánchez
(3, NULL, 3, 80.00),  -- Servicio de radiografía para José Díaz
(4, 4, NULL, 100.00), -- Consulta de Claudia Romero con Ana Pérez
(4, NULL, 4, 35.00),  -- Servicio de hemograma para Claudia Romero
(5, 5, NULL, 100.00), -- Consulta de Ricardo Silva con Ana Pérez
(5, NULL, 5, 20.00),  -- Servicio de glucosa para Ricardo Silva
(6, 10, NULL, 150.00), -- Consulta de Patricia Medina con Roberto Fernández
(6, NULL, 10, 45.00),  -- Servicio de perfil lipídico para Patricia Medina
(7, 6, NULL, 90.00),  -- Consulta de Mariana Paredes con Pedro García
(7, NULL, 6, 80.00),  -- Servicio de radiografía para Mariana Paredes
(8, 7, NULL, 90.00),  -- Consulta de Daniel Ríos con Pedro García
(8, NULL, 7, 30.00),  -- Servicio de nebulización para Daniel Ríos
(9, 8, NULL, 80.00),  -- Consulta de Gabriela Vargas con Carmen López
(9, NULL, 8, 35.00),  -- Servicio de hemograma para Gabriela Vargas
(10, 9, NULL, 120.00), -- Consulta de Fernando Rojas con Roberto Fernández
(10, NULL, 9, 15.00);  -- Servicio de inyectable para Fernando Rojas

-- Creando historias clínicas para algunos pacientes
INSERT INTO historiaclinica (antecedentepersonales, enfermedadactual, examenfisico, evolucion, altamedica, iddiagnostico, idtratamiento) VALUES 
('Paciente sin antecedentes médicos relevantes. No alergias conocidas excepto a penicilina. No cirugías previas. No hipertensión, no diabetes.', 
 'Paciente acude por cuadro de rinitis de 3 días de evolución con obstrucción nasal, rinorrea y estornudos frecuentes.', 
 'Signos vitales: PA 120/80, FC 72, FR 18, T 36.8°C. Mucosa nasal eritematosa, con rinorrea. Resto del examen físico sin alteraciones.', 
 'Paciente mejora con tratamiento antihistamínico. Se recomienda evitar alérgenos conocidos.', 
 FALSE, 6, 1),  -- Miguel Flores - Rinitis Alérgica
 
('Paciente con antecedentes de gastritis crónica diagnosticada hace 2 años. No alergias medicamentosas. No cirugías previas. No hipertensión, no diabetes.', 
 'Paciente acude por exacerbación de dolor epigástrico de 5 días de evolución, de intensidad moderada, que aumenta con el ayuno y mejora parcialmente con alimentos.', 
 'Signos vitales: PA 110/70, FC 68, FR 16, T 36.5°C. Abdomen blando, depresible, doloroso a la palpación en epigastrio. No signos de irritación peritoneal.', 
 'Paciente mejora con tratamiento con inhibidor de bomba de protones. Se recomienda dieta blanda y fraccionada.', 
 FALSE, 4, 2),  -- Lucía Castro - Gastritis Crónica
 
('Paciente con historia de migraña desde hace 5 años. No alergias medicamentosas. Apendicectomía hace 10 años. No hipertensión, no diabetes.', 
 'Paciente acude por cefalea intensa de 2 días de evolución, pulsátil, de localización hemicraneal derecha, asociada a fotofobia, fonofobia y náuseas.', 
 'Signos vitales: PA 125/85, FC 75, FR 18, T 37.2°C. Examen neurológico sin alteraciones. No rigidez de nuca.', 
 'Paciente mejora parcialmente con analgésicos. Se recomienda identificar y evitar factores desencadenantes.', 
 FALSE, 5, 3),  -- José Díaz - Migraña
 
('Paciente con hipertensión arterial diagnosticada hace 3 años, en tratamiento con enalapril. Alergia a aspirina. No cirugías previas. No diabetes.', 
 'Paciente acude para control de hipertensión arterial. Refiere buena adherencia al tratamiento, pero últimamente ha presentado valores elevados de presión arterial.', 
 'Signos vitales: PA 140/90, FC 80, FR 18, T 36.7°C. Examen cardiovascular: ruidos cardíacos rítmicos, no soplos. Resto del examen físico normal.', 
 'Se ajusta dosis de medicación antihipertensiva. Se recomienda dieta hiposódica y ejercicio regular.', 
 FALSE, 1, 4),  -- Claudia Romero - Hipertensión Arterial
 
('Paciente con diabetes mellitus tipo 2 diagnosticada hace 5 años, en tratamiento con metformina. No alergias medicamentosas. Colecistectomía hace 2 años. No hipertensión.', 
 'Paciente acude para control de diabetes. Refiere poliuria, polidipsia y fatiga en las últimas semanas.', 
 'Signos vitales: PA 130/85, FC 76, FR 18, T 36.9°C. Peso 85.5 kg, talla 1.72 m, IMC 28.9 (sobrepeso). Resto del examen físico normal.', 
 'Se ajusta dosis de medicación hipoglucemiante. Se recomienda dieta para diabéticos y ejercicio regular.', 
 FALSE, 2, 5);  -- Ricardo Silva - Diabetes Mellitus Tipo 2