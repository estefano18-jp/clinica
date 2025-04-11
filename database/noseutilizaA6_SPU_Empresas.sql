DELIMITER $$
CREATE PROCEDURE spu_registrar_empresa(
    IN p_razonsocial VARCHAR(200),
    IN p_ruc VARCHAR(11),
    IN p_direccion VARCHAR(250),
    IN p_nombrecomercial VARCHAR(200),
    IN p_telefono VARCHAR(20),
    IN p_email VARCHAR(100),
    OUT p_idempresa INT,
    OUT p_mensaje VARCHAR(100)
)
BEGIN
    DECLARE v_existe_empresa INT DEFAULT 0;
    
    DECLARE exit handler for SQLEXCEPTION
    BEGIN
        SET p_mensaje = 'Error al registrar la empresa.';
    END;
    
    -- Check if company with the same RUC already exists
    SELECT COUNT(*) INTO v_existe_empresa
    FROM empresas 
    WHERE ruc = p_ruc;
    
    IF v_existe_empresa > 0 THEN
        -- Company already exists
        SELECT idempresa INTO p_idempresa 
        FROM empresas 
        WHERE ruc = p_ruc 
        LIMIT 1;
        
        SET p_mensaje = CONCAT('La empresa con RUC ', p_ruc, ' ya está registrada.');
    ELSE
        -- Create company
        INSERT INTO empresas (
            razonsocial,
            ruc,
            direccion,
            nombrecomercial,
            telefono,
            email
        ) VALUES (
            p_razonsocial,
            p_ruc,
            p_direccion,
            p_nombrecomercial,
            p_telefono,
            p_email
        );
        
        SET p_idempresa = LAST_INSERT_ID();
        SET p_mensaje = CONCAT('Empresa ', p_razonsocial, ' registrada correctamente.');
    END IF;
END $$
DELIMITER ;