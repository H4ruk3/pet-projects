/*Запрос - основа для первого представления*/
select c.name, t.name, t.proizv, t.country, t.cost
from tovar t, category c
where c.id_category=t.id_category;

/*Запрос на основе первого представления*/
select * from kattov where made != "Россия";

/*Запрос - основа для второго представления*/
select o.ord_date, t.name, t.proizv, t.cost, ot.kolvo, s.name
from orders o, order_tovar ot, tovar t, status s 
where t.artikul=ot.artikul and o.id_order=ot.id_order and o.id_status=s.id_status;

/*Запрос на основе второго представления*/
select * from shop_orders where status = 'Заказан';

/*Запрос - основа для третьего представления*/
select c.surname, c.name, so.ord_date, so.tovar, so.proizv, so.cost, so.kolvo, so.status
from client c, shop_orders so
where c.id_client=so.client;

/*Запрос на основе третьего представления*/
select * from client_orders where ord_date like '2022-10%';

/*Запрос - основа для четвертого представления*/
select t.artikul, t.name, t.ost, c.name
from tovar t
join category c on t.id_category = c.id_category
where t.ost > 0;

/*Запрос на основе четвертого представления*/
select * from view_tovar_stock where ost > 10;