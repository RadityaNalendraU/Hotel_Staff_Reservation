--delete tamu 
DELIMITER //
CREATE TRIGGER before_delete_tamu 
BEFORE DELETE ON Tamu
FOR EACH ROW
BEGIN
--mencari yang berelasi dengan tamu yang ada di pembayaran
    DELETE FROM Pembayaran 
    WHERE id_reservasi IN (
        SELECT id_reservasi 
        FROM Reservasi 
        WHERE no_telepon = OLD.no_telepon
    );
    -- mencari yang berelasi dengan tamu di reservasi
    DELETE FROM Reservasi 
    WHERE no_telepon = OLD.no_telepon;
END //
DELIMITER ;