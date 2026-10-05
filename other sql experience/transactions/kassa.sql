delimiter //
use busstop//
create procedure kassa()
begin
set TRANSACTION ISOLATION LEVEL read COMMITTED;
START TRANSACTION;
SELECT *
FROM ticket
WHERE num_way = 1
  AND time_hour = 11
  AND time_min = 11
FOR UPDATE;

SELECT COUNT(*)
INTO @place_available
FROM ticket
WHERE num_way = 1
  AND time_hour = 11
  AND time_min = 11
  AND num_st IS NULL;

IF @place_available > 0 THEN
  UPDATE ticket
  SET num_st = 1
  WHERE num_way = 1
    AND time_hour = 11
    AND time_min = 11
    AND num_st IS NULL
	limit 1;

  IF ROW_COUNT() > 0 THEN
    COMMIT;
    SELECT 'Ticket sold successfully.' AS message;
  ELSE
    ROLLBACK;
    SELECT 'The place is not available.' AS message;
  END IF;
ELSE
  ROLLBACK;
  SELECT 'The place is not available.' AS message;
END IF;
end//