use barbershop;

-- Вывести названия и длительность всех услуг в барбершопе
SELECT name, service_duration FROM barbershop.service;

-- Вывести записи, которые не были проведены
SELECT * FROM barbershop.record WHERE service_rendered = 0;

-- Показать, как в зависимости от должности изменяется цена услуг:
SELECT 
    p.name AS position_name,
    s.name AS service_name,
    sp.price AS service_price
FROM 
    barbershop.position p
JOIN 
    barbershop.service_position sp ON p.id_position = sp.id_position
JOIN 
    barbershop.service s ON sp.id_service = s.id_service;
    
-- Показать все проведенные записи в определенный промежуток времени
SELECT * 
FROM barbershop.record 
WHERE date BETWEEN '2024-05-15' AND '2024-05-19' AND service_rendered = 1;

-- Вывести информацию о рабочем графике барберов с должностью "top-barber"
SELECT 
    e.full_name AS employee_name,
    s.date AS work_date,
    s.start_time AS start_time,
    s.end_time AS end_time
FROM 
    barbershop.employee e
JOIN 
    barbershop.schedule s ON e.id_employee = s.id_employee
JOIN 
    barbershop.position p ON e.id_position = p.id_position
WHERE 
    p.name = 'top-barber';
