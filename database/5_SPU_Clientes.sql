DELIMITER $$
CREATE PROCEDURE spu_registrar_cliente(
    IN p_tipocliente ENUM('NATURAL', 'EMPRESA'),
    IN p_idempresa INT,
    IN p_idpersona INT,
    OUT p_idcliente INT,
    OUT p_mensaje VARCHAR(100)
)
BEGIN
    DECLARE v_existe_cliente INT DEFAULT 0;
    
    DECLARE exit handler for SQLEXCEPTION
    BEGIN
        SET p_mensaje = 'Error al registrar el cliente.';
    END;
    
    -- Check if client already exists
    SELECT COUNT(*) INTO v_existe_cliente 
    FROM clientes 
    WHERE idpersona = p_idpersona;
    
    IF v_existe_cliente > 0 THEN
        -- Client already exists
        SELECT idcliente INTO p_idcliente 
        FROM clientes 
        WHERE idpersona = p_idpersona 
        LIMIT 1;
        
        SET p_mensaje = 'El cliente ya está registrado.';
    ELSE
        -- Create client
        INSERT INTO clientes (
            tipocliente,
            idempresa,
            idpersona
        ) VALUES (
            p_tipocliente,
            p_idempresa,
            p_idpersona
        );
        
        SET p_idcliente = LAST_INSERT_ID();
        SET p_mensaje = 'Cliente registrado correctamente.';
    END IF;
END $$
DELIMITER ;