-- function unntuk melihat loyalitas tamu
DELIMITER //
CREATE FUNCTION KategoriLoyalitasTamu(noTelepon VARCHAR(15)) 
RETURNS VARCHAR(10)
DETERMINISTIC
BEGIN
    DECLARE totalPembayaran DECIMAL(15,2);
    DECLARE kategori VARCHAR(10);

    -- Ambil total pembayaran tamu berdasarkan nomor telepon
    SELECT SUM(total_pembayaran) INTO totalPembayaran 
    FROM Pembayaran 
    WHERE no_telepon = noTelepon;
    
    -- Beri kategori loyalitas berdasarkan total pembayaran
    IF totalPembayaran > 10000000 THEN
        SET kategori = 'Platinum';
    ELSEIF totalPembayaran >= 5000000 THEN
        SET kategori = 'Gold';
    ELSEIF totalPembayaran >= 2000000 THEN
        SET kategori = 'Silver';
    ELSE
        SET kategori = 'Bronze';
    END IF;
    
    RETURN kategori;
END //
DELIMITER ;

-- trigger untuk meng update loyalitas tamu
DELIMITER //

CREATE TRIGGER UpdateLoyalitasTamu
AFTER INSERT ON Pembayaran
FOR EACH ROW
BEGIN
    DECLARE kategori VARCHAR(10);

    -- Panggil Stored Function untuk menentukan kategori loyalitas
    SET kategori = KategoriLoyalitasTamu(NEW.no_telepon);

    -- Update kategori loyalitas pada tabel Tamu
    UPDATE Tamu 
    SET loyalitas = kategori
    WHERE no_telepon = NEW.no_telepon;
END;
//
DELIMITER ;


--trigger untuk meng update total pengeluaran tamu
DELIMITER //
CREATE TRIGGER UpdateTotalPengeluaranTamu
AFTER INSERT ON Pembayaran
FOR EACH ROW
BEGIN
    -- Update total_pengeluaran pada tabel Tamu
    UPDATE Tamu 
    SET total_pengeluaran = total_pengeluaran + NEW.total_pembayaran
    WHERE no_telepon = NEW.no_telepon;
END;
//
DELIMITER ;

DELIMITER //

CREATE TRIGGER after_insert_log_reservasi
AFTER BEFORE ON log_reservasi
FOR EACH ROW
BEGIN
    DELETE FROM log_reservasi WHERE id_pembayaran = 0;
END //

DELIMITER ;
