/*Представление, содержащее данные о товарах и и их принадлежности к категории*/
create view kattov as (
select c.name as category, t.name as tovar, t.proizv as proizv, t.country as made, t.cost as cost
from tovar t, category c
where c.id_category=t.id_category);

create view tovari as (
select t.name tovar, t.proizv proizv
from tovar t);
insert tovari (tovar) values ('aaaa');
    
/*Представление, содержащее данные о заказах и товарах. Включает в себя статусы заказов*/
create view shop_orders as (
select o.id_client client, o.id_order 'order', o.ord_date ord_date, t.name tovar, t.proizv proizv, t.cost cost, ot.kolvo kolvo, 
s.name status
from orders o, order_tovar ot, tovar t, status s 
where t.artikul=ot.artikul and o.id_order=ot.id_order and o.id_status=s.id_status
);

/*Представление, содержащее данные о клиентах и из заказах. На основе таблицы клиентов и представления shop_order*/
create view client_orders as (
select c.surname surname, c.name name, so.ord_date, so.tovar, so.proizv, so.cost, so.kolvo, so.status
from client c, shop_orders so
where c.id_client=so.client
);

/*Представление, содержащее данные о остатках товара в различных категориях*/
create view view_tovar_stock as (
select t.artikul, t.name tovar_name, t.ost, c.name category_name
from tovar t
join category c on t.id_category = c.id_category
where t.ost > 0);
