use cities_data;

explain select *
from countries c
join states s using(countryID);
show STATUS like 'Last_query_cost';

explain select *
from states s
join countries c using(countryID);
show STATUS like 'Last_query_cost';

explain analyze select *
from countries c
straight_join states s using(countryID);
show STATUS like 'Last_query_cost';

explain analyze select *
from states
straight_join countries using(countryID);
show STATUS like 'Last_query_cost';

-- запрос 5
explain select countryName, cityName
from countries co
join states s using(countryID)
join cities c using(stateID);

explain select countryName, cityName
from cities c
join states s using(stateID)
join countries co on s.countryID = co.countryID;

explain analyze select countryName, cityName
from cities
join countries using(countryID)
join states using(stateID);

explain analyze select countryName, cityName
from states s
join cities using(stateID)
join countries co on s.countryID = co.countryID;
show STATUS like 'Last_query_cost';

explain select *
from cities c
straight_join states s using(stateID)
straight_join countries co on s.countryID = co.countryID;
show STATUS like 'Last_query_cost';

explain select cityName
from countries
join states using(countryID)
join cities using(stateID);

explain select cityName, CountryName
from countries
straight_join states using(countryID)
straight_join cities using(stateID)
order by cityName;

create index city_id on cities(cityID);
create index state_id on states(stateID);
create index country_id on countries(countryID);
create index country_name on countries(countryName);
create index city_name on cities(cityName);
drop index city_id on cities;
drop index state_id on states;
drop index country_id on countries;
drop index country_name on countries;
drop index city_name on cities;

explain select cityName
from countries
join cities using (countryID)
where countryName = "China";
show STATUS like 'Last_query_cost';

explain select cityName
from countries
join cities using (countryID)
where countryID = (select countryID from countries where countryName = "China");
show STATUS like 'Last_query_cost';
