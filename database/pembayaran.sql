DELIMITER //
CREATE TRIGGER after_insert_pembayaran 
AFTER INSERT ON Pembayaran
FOR EACH ROW
BEGIN
    DECLARE harga_kamar INT;
    DECLARE lama_menginap INT;

    SELECT harga_per_malam INTO harga_kamar 
    FROM Kamar 
    WHERE no_kamar = (SELECT no_kamar FROM Reservasi WHERE id_reservasi = NEW.id_reservasi);

    SELECT DATEDIFF(tanggal_check_out, tanggal_check_in) INTO lama_menginap 
    FROM Reservasi 
    WHERE id_reservasi = NEW.id_reservasi;

    IF lama_menginap < 1 THEN
        SET lama_menginap = 1;
    END IF;

    IF NEW.total_pembayaran < (harga_kamar * lama_menginap) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Uang Pembayaran Kurang';
    ELSE
        IF NEW.total_pembayaran >= (harga_kamar * lama_menginap) THEN
            UPDATE Reservasi
            SET status_reservasi = 'Lunas'
            WHERE id_reservasi = NEW.id_reservasi;
        END IF;
    END IF;
END //
DELIMITER ;