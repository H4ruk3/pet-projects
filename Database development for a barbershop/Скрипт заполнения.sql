use barbershop;

-- Заполнение таблицы position данными
INSERT INTO barbershop.position (name)
VALUES 
    ('barber'),
    ('top-barber'),
    ('pro-barber');

-- Заполнение таблицы service данными
INSERT INTO barbershop.service (name, description, service_duration)
VALUES
    ('Стрижка', 'Консультация, классическая стрижка, две мойки головы, укладка', 60),
    ('Дизайн бороды', 'Консультация, работа машинкой, работа триммером, работа шейвером, работа бритвой, уход', 30),
    ('Комплекс', 'Консультация, классическая стрижка, две мойки головы, укладка, работа машинкой, работа триммером, работа шейвером, работа бритвой, уход', 90),
    ('Стрижка ножницами', 'Консультация, удлиненная стрижка без использования машинки, две мойки, укладка, рекомендации по уходу за волосами и кожей головы', 60),
    ('Тонировка бороды', 'Консультация, тонировка бороды, мойка, рекомендации по уходу, подбор средств для ухода', 90),
    ('Тонировка волос', 'Консультация, тонировка волос, мойка, рекомендации по уходу, подбор средств для ухода', 120);

-- Получение id должностей барберов
SET @id_barber = (SELECT id_position FROM barbershop.position WHERE name = 'barber');
SET @id_top_barber = (SELECT id_position FROM barbershop.position WHERE name = 'top-barber');
SET @id_pro_barber = (SELECT id_position FROM barbershop.position WHERE name = 'pro-barber');

-- Заполнение таблицы service_position данными для должностей
INSERT INTO barbershop.service_position (id_service, id_position, price)
VALUES
    ((SELECT id_service FROM barbershop.service WHERE name = 'Стрижка'), @id_barber, 600),
    ((SELECT id_service FROM barbershop.service WHERE name = 'Дизайн бороды'), @id_barber, 600),
    ((SELECT id_service FROM barbershop.service WHERE name = 'Комплекс'), @id_barber, 1200),
    ((SELECT id_service FROM barbershop.service WHERE name = 'Стрижка ножницами'), @id_top_barber, 1000),
    ((SELECT id_service FROM barbershop.service WHERE name = 'Тонировка бороды'), @id_pro_barber, 1100),
    ((SELECT id_service FROM barbershop.service WHERE name = 'Тонировка волос'), @id_pro_barber, 1100);
    
-- Вставка услуг "Стрижка" и "Дизайн бороды" для top-barber с наценкой 100
INSERT INTO barbershop.service_position (id_service, id_position, price)
SELECT sp.id_service, @id_top_barber, sp.price + 100
FROM barbershop.service_position AS sp
JOIN barbershop.service AS s ON sp.id_service = s.id_service
WHERE sp.id_position = @id_barber AND s.name IN ('Стрижка', 'Дизайн бороды');

-- Вставка услуги "Комплекс" для top-barber с наценкой 200
INSERT INTO barbershop.service_position (id_service, id_position, price)
SELECT sp.id_service, @id_top_barber, sp.price + 200
FROM barbershop.service_position AS sp
JOIN barbershop.service AS s ON sp.id_service = s.id_service
WHERE sp.id_position = @id_barber AND s.name = 'Комплекс';

-- Вставка услуг для pro-barber с наценкой 100 относительно top-barber
INSERT INTO barbershop.service_position (id_service, id_position, price)
SELECT sp.id_service, @id_pro_barber, sp.price + 100
FROM barbershop.service_position AS sp
JOIN barbershop.service AS s ON sp.id_service = s.id_service
WHERE sp.id_position = @id_top_barber AND s.name IN ('Стрижка', 'Дизайн бороды', 'Стрижка ножницами');

-- Вставка услуги "Комплекс" для pro-barber с наценкой 200
INSERT INTO barbershop.service_position (id_service, id_position, price)
SELECT sp.id_service, @id_pro_barber, sp.price + 200
FROM barbershop.service_position AS sp
JOIN barbershop.service AS s ON sp.id_service = s.id_service
WHERE sp.id_position = @id_top_barber AND s.name = 'Комплекс';

-- Вставка 7 сотрудников в таблицу employee
INSERT INTO barbershop.employee (full_name, phone_number, id_position)
VALUES
    ('Иванов Алексей Антонович', '+7(123)456-78-90', @id_barber),
    ('Смирнов Дмитрий Сергеевич', '+7(234)567-89-01', @id_barber),
    ('Миронов Венеамин Кириллович', '+7(345)678-90-12', @id_top_barber),
    ('Попов Александр Андреевич', '+7(456)789-01-23', @id_top_barber),
    ('Васильев Сергей Вадимович', '+7(567)890-12-34', @id_top_barber),
    ('Соколов Владимир Иванович', '+7(678)901-23-45', @id_pro_barber),
    ('Морозов Павел Константинович', '+7(789)012-34-56', @id_pro_barber);
    
-- Вставка 15 клиентов в таблицу client
INSERT INTO barbershop.client (full_name, phone_number)
VALUES
    ('Иванов Иван Иванович', '+7(111)222-33-33'),
    ('Петров Петр Петрович', '+7(222)333-44-44'),
    ('Сидоров Сидор Сидорович', '+7(333)444-55-55'),
    ('Смирнов Алексей Алексеевич', '+7(444)555-66-66'),
    ('Кузнецов Дмитрий Дмитриевич', '+7(555)666-77-77'),
    ('Попов Сергей Сергеевич', '+7(666)777-88-88'),
    ('Васильев Александр Александрович', '+7(777)888-99-99'),
    ('Соколов Михаил Михайлович', '+7(888)999-00-00'),
    ('Морозов Виктор Викторович', '+7(999)000-11-11'),
    ('Новиков Николай Николаевич', '+7(000)111-22-22'),
    ('Федоров Павел Павлович', '+7(111)222-44-44'),
    ('Михайлов Артем Артемович', '+7(222)333-55-55'),
    ('Романов Роман Романович', '+7(333)444-66-66'),
    ('Егоров Егор Егорович', '+7(444)555-77-77'),
    ('Алексеев Алексей Алексеевич', '+7(555)666-88-88');

DELIMITER //

CREATE PROCEDURE FillSchedule(start_date DATE, end_date DATE)
BEGIN
    SET @current_date = start_date;

    -- Заполнение для barber
    WHILE @current_date <= end_date DO
        IF DAYOFWEEK(@current_date) NOT IN (1) THEN -- исключаем воскресенье
            INSERT INTO barbershop.schedule (date, id_employee, start_time, end_time)
            SELECT @current_date, id_employee, '12:00:00', '20:00:00'
            FROM barbershop.employee
            WHERE id_position = @id_barber;
        END IF;
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Заполнение для top-barber
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        IF DAYOFWEEK(@current_date) NOT IN (1) THEN -- исключаем воскресенье
            INSERT INTO barbershop.schedule (date, id_employee, start_time, end_time)
            SELECT @current_date, id_employee, '11:00:00', '20:00:00'
            FROM barbershop.employee
            WHERE id_position = @id_top_barber;
        END IF;
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Заполнение для pro-barber
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        IF DAYOFWEEK(@current_date) NOT IN (1, 7) THEN -- исключаем субботу и воскресенье
            INSERT INTO barbershop.schedule (date, id_employee, start_time, end_time)
            SELECT @current_date, id_employee, '10:00:00', '20:00:00'
            FROM barbershop.employee
            WHERE id_position = @id_pro_barber;
        END IF;
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;
END //

DELIMITER ;

CALL FillSchedule('2024-05-06', '2024-06-09');

INSERT INTO barbershop.record (date, time, service_rendered, id_client, id_employee)
VALUES
	-- До 26.05.2024
	('2024-05-06', '10:00:00', 0, 1, 1),
    ('2024-05-16', '18:00:00', 1, 2, 1),
    ('2024-05-24', '13:00:00', 0, 3, 2),
    ('2024-05-10', '11:00:00', 1, 4, 2),
    ('2024-05-21', '15:00:00', 1, 5, 3),
    ('2024-05-11', '19:00:00', 0, 6, 3),
    ('2024-05-8', '12:00:00', 1, 7, 4),
    ('2024-05-13', '17:00:00', 1, 8, 4),
    ('2024-05-15', '16:00:00', 1, 9, 5),
    ('2024-05-17', '14:00:00', 1, 10, 5),
    ('2024-05-20', '13:00:00', 1, 11, 6),
    ('2024-05-9', '10:00:00', 1, 12, 6),
    ('2024-05-23', '15:00:00', 1, 13, 7),
    ('2024-05-14', '11:00:00', 1, 14, 7),
    ('2024-05-10', '19:00:00', 1, 15, 7),
    -- После 26.05.2024
    ('2024-05-27', '12:00:00', default, 1, 1),
    ('2024-06-05', '14:00:00', default, 2, 1),
    ('2024-05-28', '16:00:00', default, 3, 2),
    ('2024-06-04', '19:00:00', default, 4, 2),
    ('2024-05-29', '11:00:00', default, 5, 3),
    ('2024-06-03', '17:00:00', default, 6, 3),
    ('2024-05-30', '10:00:00', default, 7, 4),
    ('2024-06-1', '13:00:00', default, 8, 4),
    ('2024-05-31', '11:00:00', default, 9, 5),
    ('2024-05-31', '18:00:00', default, 10, 5),
    ('2024-06-5', '15:00:00', default, 11, 5),
    ('2024-05-27', '15:00:00', default, 12, 6),
    ('2024-05-29', '17:00:00', default, 13, 6),
    ('2024-06-5', '13:00:00', default, 14, 7),
    ('2024-06-4', '16:00:00', default, 15, 7);
    
-- Получение id услуг
SET @id_service1 = (SELECT id_service FROM barbershop.service WHERE name = 'Стрижка');
SET @id_service2 = (SELECT id_service FROM barbershop.service WHERE name = 'Дизайн бороды');
SET @id_service3 = (SELECT id_service FROM barbershop.service WHERE name = 'Комплекс');
SET @id_service4 = (SELECT id_service FROM barbershop.service WHERE name = 'Стрижка ножницами');
SET @id_service5 = (SELECT id_service FROM barbershop.service WHERE name = 'Тонировка бороды');
SET @id_service6 = (SELECT id_service FROM barbershop.service WHERE name = 'Тонировка волос');

INSERT INTO barbershop.record_service (id_record, id_service)
VALUES
	-- Добавляем услуги к записям до 26.05.2024
    -- Записи с id_record от 1 до 15
    (1, @id_service1), -- Стрижка для записи 1
    (2, @id_service1), 
    (3, @id_service3), 
    (4, @id_service1),
    
    (5, @id_service3), 
    (6, @id_service4),
    (6, @id_service2),
    (7, @id_service2), 
    (8, @id_service1), 
    (9, @id_service4), 
    (10, @id_service3),
    
    (11, @id_service3), 
    (12, @id_service4),
    (12, @id_service2),
    (13, @id_service5),
    (13, @id_service4),
    (14, @id_service4), 
    (15, @id_service6),
    (15, @id_service2),
    
    -- Добавляем услуги к записям после 26.05.2024
    -- Записи с id_record от 16 до 30
    (16, @id_service3), -- комплекс для записи 16
    (17, @id_service2), -- Дизайн бороды для записи 17
    (18, @id_service1), -- Стрижка для записи 18
    (18, @id_service2), -- Дизайн бороды для записи 18
    (19, @id_service3),
    
    (20, @id_service4), -- Стрижка для записи 20
    (20, @id_service2), -- Дизайн бороды для записи 20
    (21, @id_service3), -- Стрижка ножницами для записи 21
    (22, @id_service1), -- Стрижка для записи 22
    (23, @id_service3), -- Стрижка ножницами для записи 23
    (24, @id_service4), -- Стрижка для записи 24
    (24, @id_service2), -- Дизайн бороды для записи 24
    (25, @id_service3), -- Стрижка ножницами для записи 25
    (26, @id_service2), -- Дизайн бороды для записи 26
    
    (27, @id_service6), -- Стрижка ножницами для записи 27
    (27, @id_service2),
    (28, @id_service4), -- Стрижка для записи 28
    (28, @id_service2), -- Дизайн бороды для записи 28
    (29, @id_service3), 
    (30, @id_service1), -- Стрижка для записи 30
    (30, @id_service5); -- Дизайн бороды для записи 30
    
-- Обновление record_duration в таблице record_service
UPDATE barbershop.record_service AS rs
JOIN (
    SELECT rs.id_record, SUM(s.service_duration) AS total_duration
    FROM barbershop.record_service AS rs
    JOIN barbershop.service AS s ON rs.id_service = s.id_service
    GROUP BY rs.id_record
) AS duration_sum ON rs.id_record = duration_sum.id_record
SET rs.record_duration = duration_sum.total_duration;
