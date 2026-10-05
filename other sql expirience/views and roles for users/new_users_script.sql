USE shop;

CREATE USER 'shop_root'@'localhost' IDENTIFIED BY '123456_Root';
GRANT ALL ON shop.* TO 'shop_root'@'localhost' WITH GRANT OPTION;
GRANT ALL ON . TO 'shop_root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'shop_root'@'localhost';

CREATE USER 'shop_manager'@'localhost' IDENTIFIED BY '123456_Manager';
GRANT SELECT ON shop.* TO 'shop_manager'@'localhost';
GRANT INSERT ON shop.client TO 'shop_manager'@'localhost';
GRANT INSERT ON shop.orders TO 'shop_manager'@'localhost';
GRANT INSERT ON shop.order_tovar TO 'shop_manager'@'localhost';
GRANT UPDATE ON shop.orders TO 'shop_manager'@'localhost';
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'shop_manager'@'localhost';

CREATE USER 'shop_controll'@'localhost' IDENTIFIED BY '123456_Controll';
GRANT SELECT ON shop.* TO 'shop_controll'@'localhost';
GRANT INSERT ON shop.client TO 'shop_controll'@'localhost';
GRANT INSERT ON shop.orders TO 'shop_controll'@'localhost';
GRANT INSERT ON shop.order_tovar TO 'shop_controll'@'localhost';
GRANT UPDATE ON shop.orders TO 'shop_controll'@'localhost';
GRANT DELETE ON shop.orders TO 'shop_controll'@'localhost';
GRANT UPDATE ON shop.order_tovar TO 'shop_controll'@'localhost';
GRANT DELETE ON shop.order_tovar TO 'shop_controll'@'localhost';
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'shop_controll'@'localhost';

CREATE USER 'shop_sclad'@'%' IDENTIFIED BY '123456_Sclad';
GRANT SELECT ON shop.tovar TO 'shop_sclad'@'%';
GRANT SELECT ON shop.category TO 'shop_sclad'@'%';
CREATE USER 'shop_sclad'@'localhost' IDENTIFIED BY '123456_Sclad';
GRANT SELECT, INSERT, UPDATE ON shop.tovar TO 'shop_sclad'@'localhost';
GRANT SELECT, INSERT, UPDATE ON shop.category TO 'shop_sclad'@'localhost';
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'shop_sclad'@'%';
SHOW GRANTS FOR 'shop_sclad'@'localhost';

--Процедура
DELIMITER //

CREATE PROCEDURE insert_tovar (
    IN id_insertorder INT,
    IN insertartikul VARCHAR(10),
    IN cnt INT
)
BEGIN
    INSERT INTO order_tovar 
    VALUES (insertartikul, id_insertorder, cnt);
    
    UPDATE tovar 
    SET ost = ost - cnt 
    WHERE artikul = insertartikul;
END //

DELIMITER ;

GRANT EXECUTE ON PROCEDURE insert_tovar TO 'shop_manager'@'localhost';

REVOKE UPDATE ON shop.orders FROM 'shop_manager'@'localhost';

