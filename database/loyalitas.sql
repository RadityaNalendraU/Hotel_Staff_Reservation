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


DELIMITER //
CREATE TRIGGER UpdateLoyalitasTamu
AFTER INSERT ON Pembayaran
FOR EACH ROW
BEGIN
    DECLARE totalPembayaran DECIMAL(15,2);
    DECLARE kategori VARCHAR(10);

    -- Hitung total pembayaran tamu berdasarkan nomor telepon
    SELECT SUM(total_pembayaran) INTO totalPembayaran 
    FROM Pembayaran 
    WHERE no_telepon = NEW.no_telepon;

    -- Tentukan kategori loyalitas berdasarkan total pembayaran
    IF totalPembayaran > 10000000 THEN
        SET kategori = 'Platinum';
    ELSEIF totalPembayaran >= 5000000 THEN
        SET kategori = 'Gold';
    ELSEIF totalPembayaran >= 2000000 THEN
        SET kategori = 'Silver';
    ELSE
        SET kategori = 'Bronze';
    END IF;

    -- Update kategori loyalitas pada tabel Tamu
    UPDATE Tamu 
    SET loyalitas = kategori
    WHERE no_telepon = NEW.no_telepon;
END;
//
DELIMITER ;

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
