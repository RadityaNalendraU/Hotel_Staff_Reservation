DELIMITER //
CREATE TRIGGER after_reservasi_insert
AFTER INSERT ON Reservasi
FOR EACH ROW
BEGIN
    UPDATE Kamar
    SET status_kamar = 'Penuh'
    WHERE no_kamar = NEW.no_kamar;
END //
DELIMITER ;

DELIMITER //

CREATE TRIGGER after_reservasi_delete
AFTER DELETE ON Reservasi
FOR EACH ROW
BEGIN
    UPDATE Kamar
    SET status_kamar = 'Tersedia'
    WHERE no_kamar = OLD.no_kamar;
END //

DELIMITER ;