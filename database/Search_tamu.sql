DELIMITER //
CREATE PROCEDURE SearchTamu(IN p_search VARCHAR(100))
BEGIN
    SELECT * FROM tamu
    WHERE no_telepon LIKE CONCAT('%', p_search, '%')
       OR nama LIKE CONCAT('%', p_search, '%')
       OR email LIKE CONCAT('%', p_search, '%')
       OR alamat LIKE CONCAT('%', p_search, '%');
END //

DELIMITER ;
