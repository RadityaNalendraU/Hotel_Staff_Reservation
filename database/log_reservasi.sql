--memasukan data reservasi yang lama ke dalam log_reservasi

DELIMITER //

CREATE TRIGGER before_delete_reservasi
BEFORE DELETE ON reservasi
FOR EACH ROW
BEGIN
    DECLARE v_id_pembayaran INT;
    DECLARE v_total_pembayaran DECIMAL(10,2);

    -- Mengambil nilai dari tabel pembayaran
    SELECT id_pembayaran, total_pembayaran INTO v_id_pembayaran, v_total_pembayaran
    FROM pembayaran
    WHERE id_reservasi = OLD.id_reservasi
    LIMIT 1;

    -- Jika tidak ditemukan, atur nilai default (menghindari NULL)
    SET v_id_pembayaran = IFNULL(v_id_pembayaran, 0);
    SET v_total_pembayaran = IFNULL(v_total_pembayaran, 0);

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

