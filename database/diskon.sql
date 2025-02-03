--adding discount
DELIMITER //
CREATE FUNCTION HitungTotalSetelahDiskon(totalPembayaran DECIMAL(15,2)) 
RETURNS DECIMAL(15,2)
DETERMINISTIC
BEGIN
--deklarasi variable
    DECLARE totalSetelahDiskon DECIMAL(15,2);
    -- membuat range untuk diskon dan diskonnya 
    IF totalPembayaran > 5000000 THEN
        SET totalSetelahDiskon = totalPembayaran * 0.90;
    ELSEIF totalPembayaran >= 3000000 THEN
        SET totalSetelahDiskon = totalPembayaran * 0.95;
    ELSE
        SET totalSetelahDiskon = totalPembayaran;
    END IF;
    --mengembalikan nilai diskon
    RETURN totalSetelahDiskon;
END //
DELIMITER ;