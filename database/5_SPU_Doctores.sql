USE clinicaDB;

-- 1. Procedimiento para verificar si un documento ya está registrado como doctor
DELIMITER $$
CREATE PROCEDURE sp_verificar_documento_doctor(
    IN p_nrodoc VARCHAR(20),
    OUT p_existe BOOLEAN
)
BEGIN
    DECLARE v_count INT DEFAULT 0;
    
    -- Verificamos si existe en colaboradores como doctor (con una especialidad asignada)
    SELECT COUNT(*) INTO v_count
    FROM personas p
    INNER JOIN colaboradores c ON p.idpersona = c.idpersona
    WHERE p.nrodoc = p_nrodoc AND c.idespecialidad IS NOT NULL;
    
    -- Si encontramos al menos un registro, el documento ya está registrado como doctor
    SET p_existe = v_count > 0;
END $$
DELIMITER ;

-- 2. Procedimiento para registrar información personal del doctor
DELIMITER //
CREATE PROCEDURE sp_registrar_doctor_personal(
    IN p_apellidos VARCHAR(100),
    IN p_nombres VARCHAR(100),
    IN p_tipodoc ENUM('DNI', 'PASAPORTE', 'CARNET DE EXTRANJERIA', 'OTRO'),
    IN p_nrodoc VARCHAR(20),
    IN p_telefono VARCHAR(20),
    IN p_fechanacimiento DATE,
    IN p_genero ENUM('M', 'F', 'OTRO'),
    IN p_direccion VARCHAR(250),
    IN p_email VARCHAR(100),
    OUT p_resultado INT,
    OUT p_mensaje VARCHAR(255),
    OUT p_idpersona INT
)
BEGIN
    DECLARE v_existe BOOLEAN;
    
    -- Verificar si el documento ya está registrado como doctor
    CALL sp_verificar_documento_doctor(p_nrodoc, v_existe);
    
    IF v_existe THEN
        SET p_resultado = 0;
        SET p_mensaje = 'Este documento ya está registrado como doctor';
        SET p_idpersona = NULL;
    ELSE
        -- Insertar datos personales
        INSERT INTO personas (apellidos, nombres, tipodoc, nrodoc, telefono, 
                             fechanacimiento, genero, direccion, email)
        VALUES (p_apellidos, p_nombres, p_tipodoc, p_nrodoc, p_telefono,
                p_fechanacimiento, p_genero, p_direccion, p_email);
        
        SET p_idpersona = LAST_INSERT_ID();
        SET p_resultado = 1;
        SET p_mensaje = 'Información personal del doctor registrada correctamente';
    END IF;
END //
DELIMITER ;

-- 3. Procedimiento para listar doctores
DELIMITER //
CREATE PROCEDURE sp_listar_doctores()
BEGIN
    SELECT 	
        p.idpersona,
        c.idcolaborador,
        p.apellidos,
        p.nombres,
        CONCAT(p.apellidos, ', ', p.nombres) AS nombre_completo,
        p.tipodoc,
        p.nrodoc,
        p.telefono,
        p.genero, -- Campo genero añadido aquí
        e.especialidad,
        e.precioatencion,
        p.email,
        CASE 
            WHEN c.estado IS NULL THEN 'ACTIVO'
            ELSE c.estado
        END AS estado
    FROM 
        colaboradores c
    INNER JOIN 
        personas p ON c.idpersona = p.idpersona
    INNER JOIN 
        especialidades e ON c.idespecialidad = e.idespecialidad
    ORDER BY 
        p.apellidos, p.nombres;
END //
DELIMITER ;

-- Procedimiento para buscar doctores por número de documento
DELIMITER //
CREATE PROCEDURE sp_buscar_doctor_por_documento(
    IN p_nrodoc VARCHAR(20)
)
BEGIN
    SELECT 
        p.idpersona,
        c.idcolaborador,
        p.apellidos,
        p.nombres,
        CONCAT(p.apellidos, ', ', p.nombres) AS nombre_completo,
        p.tipodoc,
        p.nrodoc,
        p.telefono,
        p.fechanacimiento,
        p.genero,
        p.direccion,
        p.email,
        e.especialidad,
        e.precioatencion,
        -- Incluir campo estado
        CASE 
            WHEN c.estado IS NULL THEN 'ACTIVO'
            ELSE c.estado
        END AS estado
    FROM 
        colaboradores c
    INNER JOIN 
        personas p ON c.idpersona = p.idpersona
    INNER JOIN 
        especialidades e ON c.idespecialidad = e.idespecialidad
    WHERE 
        p.nrodoc = p_nrodoc 
    LIMIT 1; -- Limitar a un resultado en caso de múltiples registros
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_cambiar_estado_doctor(
    IN p_nrodoc VARCHAR(20),
    OUT p_resultado INT,
    OUT p_mensaje VARCHAR(255)
)
BEGIN
    DECLARE v_idcolaborador INT;
    DECLARE v_estado_actual VARCHAR(10);
    
    -- Buscar el idcolaborador y estado actual
    SELECT 
        c.idcolaborador, c.estado INTO v_idcolaborador, v_estado_actual
    FROM 
        colaboradores c
    INNER JOIN 
        personas p ON c.idpersona = p.idpersona
    WHERE 
        p.nrodoc = p_nrodoc
    LIMIT 1;
    
    IF v_idcolaborador IS NULL THEN
        SET p_resultado = 0;
        SET p_mensaje = 'No se encontró un doctor con el documento especificado';
    ELSE
        -- Cambiar al estado opuesto
        IF v_estado_actual = 'ACTIVO' OR v_estado_actual IS NULL THEN
            UPDATE colaboradores SET estado = 'INACTIVO' WHERE idcolaborador = v_idcolaborador;
            SET p_mensaje = 'El estado del doctor ha sido cambiado a INACTIVO';
        ELSE
            UPDATE colaboradores SET estado = 'ACTIVO' WHERE idcolaborador = v_idcolaborador;
            SET p_mensaje = 'El estado del doctor ha sido cambiado a ACTIVO';
        END IF;
        
        SET p_resultado = 1;
    END IF;
END //
DELIMITER ;
ALTER TABLE colaboradores 
ADD COLUMN estado ENUM('ACTIVO', 'INACTIVO') NOT NULL DEFAULT 'ACTIVO';

SELECT * FROM personas;
CALL sp_listar_doctores();
SELECT * FROM personas;
SELECT * FROM pacientes;
SELECT * FROM especialidades;