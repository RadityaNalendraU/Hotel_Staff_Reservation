--procedure tambah tamu
DELIMITER //

CREATE PROCEDURE InsertTamu(
    IN p_no_telepon VARCHAR(15),
    IN p_nama VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_alamat TEXT
)
BEGIN
    INSERT INTO tamu (no_telepon, nama, email, alamat) 
    VALUES (p_no_telepon, p_nama, p_email, p_alamat);
END //

DELIMITER ;
