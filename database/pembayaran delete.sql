DELIMITER //

CREATE TRIGGER after_update_reservasi_delete_payments
AFTER UPDATE ON reservasi
FOR EACH ROW
BEGIN
    -- Check if the status has changed to 'Lunas'
    IF NEW.status_reservasi = 'Lunas' THEN
        -- Delete corresponding payment records
        DELETE FROM pembayaran
        WHERE id_reservasi = NEW.id_reservasi; -- Adjust this condition if needed
    END IF;
END;

//

DELIMITER ;