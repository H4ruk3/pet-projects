SELECT * FROM mysql.user;

use shop;

create user 'shop_root'@'localhost' identified by '123456_Root';
grant all on shop.* to 'shop_root'@'localhost';
flush privileges;
SELECT * FROM mysql.user WHERE user = 'shop_root';

create user 'shop_manager'@'localhost' identified by '123456_Manager';
grant select on shop.* to 'shop_manager'@'localhost';
grant insert on shop.client to 'shop_manager'@'localhost';
grant insert on shop.orders to 'shop_manager'@'localhost';
grant insert on shop.order_tovar to 'shop_manager'@'localhost';
grant update on shop.orders to 'shop_manager'@'localhost';
flush privileges;

create user 'shop_controll'@'localhost' identified by '123456_Manager';
grant select on shop.* to 'shop_controll'@'localhost';
grant insert on shop.client to 'shop_controll'@'localhost';
grant insert on shop.orders to 'shop_controll'@'localhost';
grant insert on shop.order_tovar to 'shop_controll'@'localhost';
grant update on shop.orders to 'shop_controll'@'localhost';
grant update on shop.order_tovar to 'shop_controll'@'localhost';
grant delete on shop.orders to 'shop_controll'@'localhost';
grant delete on shop.order_tovar to 'shop_controll'@'localhost';
flush privileges;

create user 'shop_sclad'@'%' identified by '123456_Sclad';
grant select on shop.tovar to 'shop_sclad'@'%';
grant select on shop.category to 'shop_sclad'@'%';
create user 'shop_sclad'@'localhost' identified by '123456_Sclad';
grant select on shop.tovar to 'shop_sclad'@'localhost';
grant select on shop.category to 'shop_sclad'@'localhost';
grant insert on shop.tovar to 'shop_sclad'@'localhost'; 
grant insert on shop.category to 'shop_sclad'@'localhost'; 
grant update on shop.tovar to 'shop_sclad'@'localhost'; 
grant update on shop.category to 'shop_sclad'@'localhost'; 
flush privileges;

delimiter //
create procedure insert_tovar(IN id_insertorder INT, insertartikul varchar(10), cnt int)
begin
	insert into order_tovar (artikul, id_order, kolvo) values (insertartikul, id_insertorder, cnt);
    update tovar set ost = ost-cnt where artikul = insertartikul;
end//

grant execute on procedure insert_tovar to 'shop_manager'@'localhost';
revoke update on shop.orders from 'shop_manager'@'localhost';

