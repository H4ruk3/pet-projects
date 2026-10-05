use barbershop;

-- Процедура для получения информации о клиентах и их последних записях
DELIMITER //

CREATE PROCEDURE GetClientsLastRecords()
BEGIN
    SELECT 
        c.full_name AS client_name,
        c.phone_number AS client_phone,
        lr.date AS last_record_date,
        lr.time AS last_record_time
    FROM 
        barbershop.client c
        JOIN (
            SELECT 
                id_client, 
                MAX(date) AS date, 
                MAX(time) AS time
            FROM 
                barbershop.record
            GROUP BY 
                id_client
        ) AS lr ON c.id_client = lr.id_client;
END //

DELIMITER ;

-- Процедура для получения информации о сотрудниках и их первой записи
DELIMITER //

CREATE PROCEDURE GetEmployeesFirstRecords()
BEGIN
    SELECT 
        e.full_name AS employee_name,
        e.phone_number AS employee_phone,
        fr.date AS first_record_date,
        fr.time AS first_record_time
    FROM 
        barbershop.employee e
        JOIN (
            SELECT 
                id_employee, 
                MIN(date) AS date, 
                MIN(time) AS time
            FROM 
                barbershop.record
            GROUP BY 
                id_employee
        ) AS fr ON e.id_employee = fr.id_employee;
END //

DELIMITER ;

-- Процедура для получения изменения цены услуг в зависимости от должности
DELIMITER //

CREATE PROCEDURE GetServicePricesByPosition()
BEGIN
    SELECT 
        s.name AS service_name,
        p.name AS position_name,
        sp.price
    FROM 
        barbershop.service_position sp
        JOIN barbershop.service s ON sp.id_service = s.id_service
        JOIN barbershop.position p ON sp.id_position = p.id_position;
END //

DELIMITER ;

-- Процедура для получения всех проведенных записей в определенный промежуток времени
DELIMITER //

CREATE PROCEDURE GetRenderedRecordsInDateRange(start_date DATE, end_date DATE)
BEGIN
    SELECT 
        r.id_record,
        r.date,
        r.time,
        r.service_rendered,
        c.full_name AS client_name,
        e.full_name AS employee_name
    FROM 
        barbershop.record r
        JOIN barbershop.client c ON r.id_client = c.id_client
        JOIN barbershop.employee e ON r.id_employee = e.id_employee
    WHERE 
        r.date BETWEEN start_date AND end_date
        AND r.service_rendered = 1;
END //

DELIMITER ;

CALL GetClientsLastRecords();
CALL GetEmployeesFirstRecords();
CALL GetServicePricesByPosition();
CALL GetRenderedRecordsInDateRange('2024-05-11', '2024-05-26');