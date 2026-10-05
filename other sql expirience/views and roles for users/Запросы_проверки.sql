--общие запросы
USE shop;
SELECT * FROM category;
INSERT INTO category(name) VALUES("Подарочные карты");
DELETE FROM category WHERE id_category=5;

--Тестим менеджера
USE shop;
INSERT INTO shop.client VALUES (1234, "aaa", "bbb", "ccc", "phone", "mail");
INSERT INTO orders VALUES (5555, NOW(), 1234, 1);
INSERT INTO order_tovar (artikul, id_order) VALUES (143562, 5555);
DELETE FROM order_tovar WHERE id_order=5555;
UPDATE orders SET id_status=2 WHERE id_order=5555;
DELETE FROM orders WHERE id_order=5555;

--Тестим контроллера
USE shop;
DELETE FROM order_tovar WHERE id_order=5555;
UPDATE orders SET id_status=3 WHERE id_order=5555;

--Тестим работника склада
USE shop;
SELECT * FROM tovar;
UPDATE tovar SET ost=9 WHERE artikul=143562;
INSERT INTO tovar VALUES (111111, "naame", "proizv", "ru", 100, 50, 1);
DELETE FROM tovar WHERE artikul=111111;
SELECT * FROM orders WHERE id_status=1;
SELECT * FROM client;

--Тестим процедуру
USE shop;
CALL insert_tovar(5555, 111111, 7);
UPDATE tovar SET ost=ost-1 WHERE artikul=111111;

--Тестим отзыв привелегий
USE shop;
UPDATE orders SET id_status=2 WHERE id_order=5555;







