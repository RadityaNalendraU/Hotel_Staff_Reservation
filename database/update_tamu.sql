DELIMITER //
CREATE PROCEDURE UpdateTamu(
    IN p_no_telepon VARCHAR(15),
    IN p_nama VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_alamat TEXT
)
BEGIN
    UPDATE tamu 
    SET nama = p_nama, 
        email = p_email, 
        alamat = p_alamat
    WHERE no_telepon = p_no_telepon;
END //
DELIMITER ;
