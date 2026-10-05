use barbershop;

-- Триггер для проверки времени окончания рабочего дня сотрудника
DELIMITER //

CREATE TRIGGER CheckEndTimeBeforeInsert 
BEFORE INSERT ON barbershop.schedule
FOR EACH ROW
BEGIN
    IF NEW.end_time <= NEW.start_time THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'End time cannot be less than or equal to start time';
    END IF;
END //

CREATE TRIGGER CheckEndTimeBeforeUpdate 
BEFORE UPDATE ON barbershop.schedule
FOR EACH ROW
BEGIN
    IF NEW.end_time <= NEW.start_time THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'End time cannot be less than or equal to start time';
    END IF;
END //

DELIMITER ;

-- Триггер для проверки, что запись оформляется на начало каждого часа
DELIMITER //

CREATE TRIGGER CheckHourlyBookingBeforeInsert 
BEFORE INSERT ON barbershop.record
FOR EACH ROW
BEGIN
    IF MINUTE(NEW.time) <> 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Booking time must be on the hour';
    END IF;
END //

CREATE TRIGGER CheckHourlyBookingBeforeUpdate 
BEFORE UPDATE ON barbershop.record
FOR EACH ROW
BEGIN
    IF MINUTE(NEW.time) <> 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Booking time must be on the hour';
    END IF;
END //

DELIMITER ;

-- Триггер для проверки длительности услуг и закрепления следующего времени для записи
DELIMITER //

CREATE TRIGGER CheckAndExtendBookingBeforeInsert 
BEFORE INSERT ON barbershop.record
FOR EACH ROW
BEGIN
    DECLARE total_duration INT DEFAULT 0;
    DECLARE next_time TIME;

    -- Calculate total duration of services
    SELECT SUM(service_duration) INTO total_duration
    FROM barbershop.service s
    JOIN barbershop.record_service rs ON s.id_service = rs.id_service
    WHERE rs.id_record = NEW.id_record;

    -- Calculate the end time of the session
    SET next_time = ADDTIME(NEW.time, SEC_TO_TIME(total_duration * 60));

    -- If the end time goes beyond the current hour, reserve the next hour slot
    IF HOUR(next_time) > HOUR(NEW.time) THEN
        INSERT INTO barbershop.record (date, time, service_rendered, id_client, id_employee)
        VALUES (NEW.date, ADDTIME(NEW.time, '01:00:00'), 0, NEW.id_client, NEW.id_employee);
    END IF;
END //

DELIMITER ;

-- Триггер для проверки выхода за рамки рабочего дня сотрудника
DELIMITER //

CREATE TRIGGER CheckDurationWithinWorkdayBeforeInsert 
BEFORE INSERT ON barbershop.record
FOR EACH ROW
BEGIN
    DECLARE total_duration INT DEFAULT 0;
    DECLARE session_end TIME;
    DECLARE work_end TIME;

    -- Calculate total duration of services
    SELECT SUM(service_duration) INTO total_duration
    FROM barbershop.service s
    JOIN barbershop.record_service rs ON s.id_service = rs.id_service
    WHERE rs.id_record = NEW.id_record;

    -- Calculate the end time of the session
    SET session_end = ADDTIME(NEW.time, SEC_TO_TIME(total_duration * 60));

    -- Get the end time of the workday
    SELECT end_time INTO work_end
    FROM barbershop.schedule
    WHERE date = NEW.date AND id_employee = NEW.id_employee;

    -- If the session end time is after the work end time, raise an error
    IF session_end > work_end THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'The total duration of services exceeds the workday end time';
    END IF;
END //

DELIMITER ;