USE clinicaDB;

-- Procedimiento para login de administrador
DELIMITER $$
CREATE PROCEDURE spu_usuario_login_administrador(
    IN p_nomuser VARCHAR(100),
    IN p_passuser VARCHAR(100)
)
BEGIN
    SELECT 
        u.idusuario, 
        p.nombres, 
        p.apellidos, 
        u.passuser,
        'ADMINISTRADOR' AS rol
    FROM usuarios u
    INNER JOIN contratos c ON u.idcontrato = c.idcontrato
    INNER JOIN colaboradores col ON c.idcolaborador = col.idcolaborador
    INNER JOIN personas p ON col.idpersona = p.idpersona
    WHERE 
        u.nomuser = p_nomuser
        AND col.idespecialidad IS NULL -- Solo administradores (sin especialidad)
    LIMIT 1;
END $$ 
DELIMITER ;

-- Procedimiento para registrar un administrador
DELIMITER $$
CREATE PROCEDURE spu_usuario_registrar_administrador(
    IN _idpersona INT,
    IN _nomuser VARCHAR(50),
    IN _passuser VARCHAR(255),
    IN _estado BOOLEAN
)
BEGIN
    -- Insertar usuario (administrador)
    INSERT INTO usuarios (idcontrato, nomuser, passuser, estado)
    SELECT c.idcontrato, _nomuser, _passuser, _estado
    FROM contratos c
    INNER JOIN colaboradores col ON c.idcolaborador = col.idcolaborador
    WHERE col.idpersona = _idpersona;

    -- Retornar el ID del usuario insertado
    SELECT @@last_insert_id AS idusuario;
END $$
DELIMITER ;


-- Procedimiento para login de doctores
DELIMITER $$
CREATE PROCEDURE spu_usuario_login_doctor(
    IN p_nomuser VARCHAR(100),
    IN p_passuser VARCHAR(100)
)
BEGIN
    SELECT 
        u.idusuario, 
        p.nombres, 
        p.apellidos, 
        u.passuser,
        e.especialidad,
        'DOCTOR' AS rol
    FROM usuarios u
    INNER JOIN contratos c ON u.idcontrato = c.idcontrato
    INNER JOIN colaboradores col ON c.idcolaborador = col.idcolaborador
    INNER JOIN personas p ON col.idpersona = p.idpersona
    INNER JOIN especialidades e ON col.idespecialidad = e.idespecialidad
    WHERE 
        u.nomuser = p_nomuser
        AND col.idespecialidad IS NOT NULL -- Solo doctores (con especialidad)
    LIMIT 1;
END $$ 
DELIMITER ;

-- Procedimiento para registrar un doctor
DELIMITER $$
CREATE PROCEDURE spu_usuario_registrar_doctor(
    IN p_idpersona INT,
    IN p_idespecialidad INT,
    IN p_nomuser VARCHAR(50),
    IN p_passuser VARCHAR(255)
)
BEGIN
    DECLARE v_idcolaborador INT;
    DECLARE v_idcontrato INT;
    
    -- Insertar colaborador (como doctor, con especialidad)
    INSERT INTO colaboradores (idpersona, idespecialidad)
    VALUES (p_idpersona, p_idespecialidad);
    
    SET v_idcolaborador = LAST_INSERT_ID();
    
    -- Insertar contrato
    INSERT INTO contratos (idcolaborador, fechainicio, fechafin, tipocontrato)
    VALUES (v_idcolaborador, CURDATE(), NULL, 'INDEFINIDO');
    
    SET v_idcontrato = LAST_INSERT_ID();
    
    -- Insertar usuario con contraseña encriptada
    INSERT INTO usuarios (idcontrato, nomuser, passuser, estado, rol)
    VALUES (v_idcontrato, p_nomuser, p_passuser, TRUE, 'DOCTOR');
    
    -- Retornar el ID del usuario insertado
    SELECT LAST_INSERT_ID() AS idusuario;
    
END $$ 
DELIMITER ;

-- ** NUEVOS PROCEDIMIENTOS APARTE **--

-- Procedimiento para obtener datos de un usuario por su ID
DELIMITER $$
CREATE PROCEDURE spu_usuarios_obtener_datos(
    IN p_idusuario INT
)
BEGIN
    SELECT 
        p.idpersona, 
        p.nombres, 
        p.apellidos, 
        p.tipodoc,
        p.nrodoc, 
        p.telefono, 
        p.fechanacimiento,
        p.genero,
        p.direccion,
        p.email
    FROM personas p 
    INNER JOIN colaboradores c ON p.idpersona = c.idpersona
    INNER JOIN contratos ct ON c.idcolaborador = ct.idcolaborador
    INNER JOIN usuarios u ON ct.idcontrato = u.idcontrato
    WHERE u.idusuario = p_idusuario;
END $$
DELIMITER ;

-- Procedimiento para verificar si existe un nombre de usuario
DELIMITER $$
CREATE PROCEDURE spu_usuarios_verificar_nombre(
    IN p_nomuser VARCHAR(50)
)
BEGIN
    SELECT COUNT(*) as existe
    FROM usuarios
    WHERE nomuser = p_nomuser;
END $$
DELIMITER ;

-- Procedimiento para verificar si existe una persona por su número de documento
DELIMITER $$
CREATE PROCEDURE spu_personas_verificar_documento(
    IN p_nrodoc VARCHAR(20)
)
BEGIN
    SELECT idpersona
    FROM personas
    WHERE nrodoc = p_nrodoc;
END $$
DELIMITER ;

-- Procedimiento para verificar si existe un usuario asociado a un número de documento
DELIMITER $$
CREATE PROCEDURE spu_usuarios_verificar_documento(
    IN p_nrodoc VARCHAR(20)
)
BEGIN
    SELECT u.idusuario 
    FROM usuarios u
    INNER JOIN contratos ct ON u.idcontrato = ct.idcontrato
    INNER JOIN colaboradores c ON ct.idcolaborador = c.idcolaborador
    INNER JOIN personas p ON c.idpersona = p.idpersona
    WHERE p.nrodoc = p_nrodoc;
END $$
DELIMITER ;

-- Insertando personas
INSERT INTO personas (apellidos, nombres, tipodoc, nrodoc, telefono, fechanacimiento, genero, direccion, email) 
VALUES ('Alvarez', 'Dyer', 'DNI', '47583565', '968540455', '2004-10-11', 'M', 'Av. Siempre Viva 123', 'dyer@email.com'),
       ('Olivos', 'Edu', 'DNI', '49853642', '990568456', '2004-02-15', 'M', 'Calle Ficticia 456', 'edu@email.com'),
       ('Sánchez', 'Guilio', 'DNI', '54321678', '909576462', '1990-05-20', 'M', 'Calle Ejemplo 789', 'guilio@email.com');
       
-- Insertando colaboradores (como administradores, sin especialidad)
INSERT INTO colaboradores (idpersona, idespecialidad) 
VALUES (20, NULL), 
       (21, NULL), 
       (22, NULL);
              
-- Insertando contratos
INSERT INTO contratos (idcolaborador, fechainicio, fechafin, tipocontrato) 
VALUES (10, CURDATE(), NULL, 'INDEFINIDO'),
       (11, CURDATE(), NULL, 'INDEFINIDO'),
       (12, CURDATE(), NULL, 'INDEFINIDO');
       
-- Insertando usuarios con contraseñas encriptadas
INSERT INTO usuarios (idcontrato, nomuser, passuser, estado) 
VALUES (10, 'dayer', '', TRUE),
       (11, 'edu', '', TRUE),
       (12, 'guilio', '', TRUE);

-- Contraseñas encriptadas

-- Administradores:
UPDATE usuarios SET passuser = '' WHERE idusuario = 1;  -- admin.carlos
UPDATE usuarios SET passuser = '' WHERE idusuario = 2;  -- admin.maria

-- Doctores:
UPDATE usuarios SET passuser = '' WHERE idusuario = 3;  -- doctor.juan
UPDATE usuarios SET passuser = '' WHERE idusuario = 4;  -- doctora.ana
UPDATE usuarios SET passuser = '' WHERE idusuario = 5;  -- doctor.roberto
UPDATE usuarios SET passuser = '' WHERE idusuario = 6;  -- doctora.carmen
UPDATE usuarios SET passuser = '' WHERE idusuario = 7;  -- doctor.pedro

-- Enfermeras:
UPDATE usuarios SET passuser = '' WHERE idusuario = 8;  -- enfermera.luisa
UPDATE usuarios SET passuser = '' WHERE idusuario = 9;  -- enfermera.juana

-- Nuevo Administrador (recien insertados):
UPDATE usuarios SET passuser = '$2y$10$KAvzdeZhgX4zpKf.Vj97YOpUmCoXBLnNRZx1ZZ9Vyqsx.Qc7Q0AJ2' WHERE idusuario = 10; -- dayer (Dyer Alvarez)
UPDATE usuarios SET passuser = '$2y$10$qgoWoXqgNMILNy01xmzWa.iJDRp9UdksJ7WfLA0tk9/kL9lGm7HJW' WHERE idusuario = 11; -- edu (Edu Olivos)
UPDATE usuarios SET passuser = '$2y$10$b6PcVFsSTXJBlI2uQKzNGO9YLgEFsyezYQuhXZ9NWzgrVW3qUkcjm' WHERE idusuario = 12; -- guilio (Guilio Sánchez)

SELECT * FROM pacientes;
SELECT * FROM personas;
SELECT * FROM colaboradores;
SELECT * FROM contratos;
SELECT * FROM usuarios;




