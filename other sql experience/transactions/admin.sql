delimiter //
use busstop//
create procedure administrator(n_way int, t_hour int, t_min int)
begin

START TRANSACTION;


SELECT count(*)
FROM rays, bus, ticket
FOR UPDATE;

SELECT b.num_bus
INTO @new_bus
FROM bus b
WHERE count_places >= (
  SELECT COUNT(*)
  FROM ticket
 WHERE num_way = n_way
	and time_hour = t_hour
	and time_min = t_min
    AND num_st IS NOT NULL
)
and b.num_bus != (
		select num_bus 
		from rays 
		where num_way = n_way
			and time_hour = t_hour
			and time_min = t_min
	)
LIMIT 1;


IF @new_bus IS NOT NULL 
THEN
  UPDATE rays
  SET num_bus = @new_bus
  WHERE num_way = n_way
	and time_hour = t_hour
	and time_min = t_min;


  IF ROW_COUNT() > 0 THEN
    COMMIT;
    SELECT 'Bus replacement successful.' AS message;
  ELSE
    ROLLBACK;
    SELECT 'No suitable bus found.' AS message, @new_bus;
  END IF;
ELSE

  ROLLBACK;
  SELECT 'No suitable bus found.' AS message, @new_bus;
END IF;
end//
