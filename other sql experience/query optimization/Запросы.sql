use cities_data;

/*Задание номер 1. Обычное соединение states и countries*/
explain analyze select *
from countries c
join states s using(countryID);
show status like 'Last_query_cost';

/*Задание номер 2. Обычное соединение countries и states*/
explain select *
from states s
join countries c using(countryID);
show status like 'Last_query_cost';

/*Задание номер 4. По сути запрос номер один и номер два, но указываем как объединять таблицы (straight_join)*/
explain select *
from countries c
straight_join states s using(countryID);
show STATUS like 'Last_query_cost';

explain select *
from states s
straight_join countries c using(countryID);
show STATUS like 'Last_query_cost';

/*Задание номер 5. Выводим название городов и стран, в которых эти города находятся*/
/*Комбинация countries, states, cities*/
explain select countryName, cityName
from countries co
join states s using(countryID)
join cities c using(stateID);
show STATUS like 'Last_query_cost';

/*Задание 6. Рассматривает другие варианты сочетания таблиц*/
/*Комбинация cities, states, countries*/
explain select countryName, cityName
from cities c
join states s using(stateID)
join countries co on s.countryID = co.countryID;
show STATUS like 'Last_query_cost';

/*Комбинация cities, countries, states*/
explain select countryName, cityName
from cities с
join countries co using(countryID)
join states s using(stateID);
show STATUS like 'Last_query_cost';

/*Комбинация states, cities, countries*/
explain select countryName, cityName
from states s
join cities c using(stateID)
join countries co on s.countryID = co.countryID;
show STATUS like 'Last_query_cost';

/*Задание 8. Выполнить запрос 4 с указанным порядком соединения таблиц cities, states, countries*/
explain select *
from cities c
straight_join states s using(stateID)
straight_join countries co on s.countryID = co.countryID;
show STATUS like 'Last_query_cost';

/*Задание 9. Выполнить запрос 5, но вывести только название названия городов*/
explain select cityName
from countries co
join states s using(countryID)
join cities c using(stateID);
show STATUS like 'Last_query_cost';

/*Задание 10. Выполнить запрос 5, но с сортировкой по названию городов*/
explain select cityName, CountryName
from countries
straight_join states using(countryID)
straight_join cities using(stateID)
order by cityName;
show STATUS like 'Last_query_cost';

/*Задание 11. Создать индексы для таблиц countries, states, cities*/
create index city_id on cities(cityID);
create index state_id on states(stateID);
create index country_id on countries(countryID);
drop index city_id on cities;
drop index state_id on states;
drop index country_id on countries;

/*Задание 13. Добавить индекс для таблицы counties по полю countryName...*/
create index country_name on countries(countryName);
create index city_name on cities(cityName);
drop index country_name on countries;
drop index city_name on cities;