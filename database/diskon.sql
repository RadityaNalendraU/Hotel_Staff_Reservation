--adding discount
DELIMITER //
CREATE FUNCTION HitungTotalSetelahDiskon(totalPembayaran DECIMAL(15,2)) 
RETURNS DECIMAL(15,2)
DETERMINISTIC
BEGIN
    DECLARE totalSetelahDiskon DECIMAL(15,2);
    
    IF totalPembayaran > 5000000 THEN
        SET totalSetelahDiskon = totalPembayaran * 0.90;
    ELSEIF totalPembayaran >= 3000000 THEN
        SET totalSetelahDiskon = totalPembayaran * 0.95;
    ELSE
        SET totalSetelahDiskon = totalPembayaran;
    END IF;
    
    RETURN totalSetelahDiskon;
END //
DELIMITER ;