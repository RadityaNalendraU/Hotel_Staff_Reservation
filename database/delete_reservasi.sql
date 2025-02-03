DELIMITER //
CREATE TRIGGER delete_all_on_reservasi
BEFORE DELETE ON reservasi
FOR EACH ROW
BEGIN
--mencari yang berelasi dengan tamu yang ada di pembayaran
    DELETE FROM Pembayaran 
    WHERE id_reservasi = OLD.id_reservasi
END //
DELIMITER ;