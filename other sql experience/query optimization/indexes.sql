/*Инструкция из методички, создаем индекс, используем оператор explain для анализа информации вывода*/
select * from cities where countryID = 'FRA';

#create index c_id on cities(countryID);

#drop index c_id on cities;

explain select * from cities where countryID = 'FRA';

#create index c_id on cities(countryID);

explain select * from cities where countryID = 'FRA';

/*Напишем запрос на поиск города 300267, проведем анализ через explain, построим индекс, проведем анализ через explain*/
select * from cities where cityID = '300267';

explain select * from cities where cityID = '300267';

#create index idx_cityID on cities(cityID);

#drop index idx_cityID on cities;

explain select * from cities where cityID = '300267';

/*Индексация по нескольким столбцам*/
explain select * from cities where countryID="chn" and stateID="48";

create index s_id on cities(countryID);

drop index s_id on cities;

explain select * from cities where countryID="chn" and stateID="48";

create index ss_id on cities(stateID);

drop index ss_id on cities;

explain select * from cities where stateID="48" and countryID="chn";

select * from cities where countryID="chn" and stateID="48";

create index sss_id on cities(stateID, countryID);

drop index sss_id on cities;

explain select * from cities where stateID="48" and countryID="chn";