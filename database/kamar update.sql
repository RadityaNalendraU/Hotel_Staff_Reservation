DELIMITER //

CREATE TRIGGER after_update_reservasi
AFTER UPDATE ON reservasi
FOR EACH ROW
BEGIN
    -- Check if the status has changed to 'Lunas'
    IF NEW.status_reservasi = 'Lunas' THEN
        -- Update the status_kamar in the kamar table
        UPDATE kamar
        SET status_kamar = 'Tersedia'
        WHERE no_kamar = NEW.no_kamar;  -- Use no_kamar instead of id_kamar
    END IF;
END;

//

DELIMITER ;