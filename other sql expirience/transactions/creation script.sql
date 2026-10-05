create database if not exists busstop;

use busstop;

-- Создаем таблицу Way
CREATE TABLE Way (
    num_way INT PRIMARY KEY,
    title_way VARCHAR(255) NOT NULL
);

-- Создаем таблицу Station
CREATE TABLE Station (
    num_st INT PRIMARY KEY,
    title_st VARCHAR(255) NOT NULL,
    distance FLOAT
);

-- Создаем таблицу Bus
CREATE TABLE Bus (
    num_bus INT PRIMARY KEY,
    model VARCHAR(255),
    count_places INT
);

-- Создаем таблицу Station_Way для связи many-to-many между Way и Station
CREATE TABLE Station_Way (
    num_way INT,
    num_st INT,
    PRIMARY KEY (num_way, num_st),
    FOREIGN KEY (num_way) REFERENCES Way(num_way),
    FOREIGN KEY (num_st) REFERENCES Station(num_st)
);

-- Создаем таблицу Rays
CREATE TABLE Rays (
    num_way INT,
    time_hour INT,
    time_min INT,
    num_bus INT,
    PRIMARY KEY (num_way, time_hour, time_min),
    FOREIGN KEY (num_way) REFERENCES Way(num_way),
    FOREIGN KEY (num_bus) REFERENCES Bus(num_bus)
);

-- Создаем таблицу Ticket
CREATE TABLE Ticket (
    place INT,
    num_way INT,
    time_hour INT,
    time_min INT,
    num_st INT,
    PRIMARY KEY (place, num_way, time_hour, time_min, num_st),
    FOREIGN KEY (num_way, time_hour, time_min) REFERENCES Rays(num_way, time_hour, time_min),
    FOREIGN KEY (num_st) REFERENCES Station(num_st)
);
