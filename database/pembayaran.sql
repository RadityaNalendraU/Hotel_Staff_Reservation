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


DELIMITER //

CREATE TRIGGER before_delete_reservasi
BEFORE DELETE ON reservasi
FOR EACH ROW
BEGIN
    DECLARE v_id_pembayaran INT DEFAULT NULL;
    DECLARE v_total_pembayaran DECIMAL(10,2) DEFAULT NULL;

    -- Mengambil nilai dari tabel pembayaran
    SELECT id_pembayaran, total_pembayaran 
    INTO v_id_pembayaran, v_total_pembayaran
    FROM pembayaran
    WHERE id_reservasi = OLD.id_reservasi
    LIMIT 1;

    -- Jika data ditemukan, simpan ke log_reservasi
    IF v_id_pembayaran IS NOT NULL THEN
        INSERT INTO log_reservasi (id_reservasi, id_pembayaran, tanggal_dihapus, total_pembayaran)
        VALUES (OLD.id_reservasi, v_id_pembayaran, NOW(), v_total_pembayaran);
    ELSE
        -- Jika tidak ditemukan, tetap simpan dengan NULL
        INSERT INTO log_reservasi (id_reservasi, id_pembayaran, tanggal_dihapus, total_pembayaran)
        VALUES (OLD.id_reservasi, NULL, NOW(), NULL);
    END IF;

END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER after_insert_reservasi
AFTER INSERT ON reservasi
FOR EACH ROW
BEGIN
    -- Insert data ke tabel pembayaran dengan nilai default
    INSERT INTO pembayaran (id_reservasi,no_telepon, tanggal_pembayaran, total_pembayaran)
    VALUES (NEW.id_reservasi,NEW.no_telepon , NOW(), NEW.total_pembayaran);
END //

DELIMITER ;
