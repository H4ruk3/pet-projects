use busstop;

-- Заполнение таблицы Way
INSERT INTO Way (num_way, title_way) VALUES
(1, 'Route 1'),
(2, 'Route 2'),
(3, 'Route 3'),
(4, 'Route 4'),
(5, 'Route 5'),
(6, 'Route 6'),
(7, 'Route 7');

-- Заполнение таблицы Station
INSERT INTO Station (num_st, title_st, distance) VALUES
(1, 'Station A', 10.5),
(2, 'Station B', 20.0),
(3, 'Station C', 30.2),
(4, 'Station D', 40.3),
(5, 'Station E', 50.5),
(6, 'Station F', 60.1),
(7, 'Station G', 70.0);

-- Заполнение таблицы Bus
INSERT INTO Bus (num_bus, model, count_places) VALUES
(1, 'Model X', 50),
(2, 'Model Y', 45),
(3, 'Model Z', 60),
(4, 'Model A', 55),
(5, 'Model B', 65),
(6, 'Model C', 70),
(7, 'Model D', 40);

-- Заполнение таблицы Station_Way
INSERT INTO Station_Way (num_way, num_st) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(4, 7);

-- Заполнение таблицы Rays
INSERT INTO Rays (num_way, time_hour, time_min, num_bus) VALUES
(1, 8, 0, 1),
(2, 9, 30, 2),
(3, 10, 45, 3),
(4, 11, 15, 4),
(5, 12, 50, 5),
(6, 13, 20, 6),
(7, 14, 10, 7);

-- Заполнение таблицы Ticket
INSERT INTO Ticket (place, num_way, time_hour, time_min, num_st) VALUES
(1, 1, 8, 0, 1),
(2, 1, 8, 0, 2),
(3, 2, 9, 30, 3),
(4, 2, 9, 30, 4),
(5, 3, 10, 45, 5),
(6, 3, 10, 45, 6),
(7, 4, 11, 15, 7);
