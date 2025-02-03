--memasukan data reservasi yang lama ke dalam log_reservasi

CREATE TRIGGER before_delete_reservasi 
BEFORE DELETE ON reservasi
FOR EACH ROW
BEGIN
    DECLARE v_id_pembayaran INT;
    DECLARE v_total_pembayaran DECIMAL(10,2);

    -- Ambil id_pembayaran dan total_pembayaran dari tabel pembayaran
    SELECT IFNULL(id_pembayaran, 0), IFNULL(total_pembayaran, 0) 
    INTO v_id_pembayaran, v_total_pembayaran
    FROM pembayaran 
    WHERE id_reservasi = OLD.id_reservasi
    LIMIT 1;
    
    -- Masukkan data ke log_reservasi
    INSERT INTO log_reservasi (id_reservasi, id_pembayaran, tanggal_dihapus, total_pembayaran)
    VALUES (OLD.id_reservasi, v_id_pembayaran, NOW(), v_total_pembayaran);
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE GetReservations(IN startDate DATETIME, IN endDate DATETIME)
BEGIN
    IF endDate IS NOT NULL THEN
        SELECT * FROM log_reservasi
        WHERE tanggal_dihapus >= startDate AND tanggal_dihapus <= endDate;
    ELSE
        SELECT * FROM log_reservasi
        WHERE tanggal_dihapus = startDate;
    END IF;
END //

DELIMITER ;

