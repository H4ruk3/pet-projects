CREATE DATABASE IF NOT EXISTS shop;

USE shop;

CREATE TABLE IF NOT EXISTS category (
	id_category INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    CONSTRAINT pk_category PRIMARY KEY (id_category),
    CONSTRAINT unique_name UNIQUE (name)
);

CREATE TABLE IF NOT EXISTS tovar (
	artikul int NOT NULL,
    name VARCHAR(200) NOT NULL,
    proizv VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    cost FLOAT NOT NULL,
    ost INT NOT NULL,
    id_category INT,
    CONSTRAINT pk_tovar PRIMARY KEY (artikul),
    CONSTRAINT fk_tovar_category FOREIGN KEY (id_category)
		REFERENCES category (id_category)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS status (
	id_status INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(45) NOT NULL,
    CONSTRAINT pk_status PRIMARY KEY (id_status)
);

CREATE TABLE IF NOT EXISTS client (
	id_client INT NOT NULL AUTO_INCREMENT,
    surname VARCHAR(45) NOT NULL,
    name VARCHAR(45) NOT NULL,
    otch VARCHAR(45) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    CONSTRAINT pk_client PRIMARY KEY (id_client)
);

CREATE TABLE IF NOT EXISTS orders (
	id_order INT NOT NULL AUTO_INCREMENT,
    ord_date DATETIME NOT NULL,
    id_client INT,
    id_status INT NOT NULL,
    CONSTRAINT pk_orders PRIMARY KEY (id_order),
    CONSTRAINT fk_orders_client FOREIGN KEY (id_client)
		REFERENCES client (id_client)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
	CONSTRAINT fk_orders_status FOREIGN KEY (id_status)
		REFERENCES status (id_status)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS order_tovar (
	artikul int NOT NULL,
    id_order INT NOT NULL,
    kolvo INT,
    CONSTRAINT pk_order_tovar PRIMARY KEY (artikul, id_order),
    CONSTRAINT fk_order_tovar_tovar FOREIGN KEY (id_order)
		REFERENCES orders (id_order)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
	CONSTRAINT fk_order_tovar_order FOREIGN KEY (artikul)
		REFERENCES tovar (artikul)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

insert category(Id_category, name)
values (1,'Письменные принадлежности'),
(2,'Папки и системы архивации'),
(3,'Канцелярские мелочи'),
(4,'Конторское оборудование ');

insert tovar(artikul,name,proizv,country,cost,ost,Id_category)
values (143562, 'Ручка шариковая BRAUBERG «ULTRA ORANGE», СИНЯЯ',
'BRAUBERG', 'Россия', 7.44, 10, 1),
(152115, 'Маркер стираемый для белой доски СИНИЙ, BRAUBERG «CLASSIC»',
'BRAUBERG', 'Россия', 35.09, 5, 1),
(227188, 'Папка-регистратор BRAUBERG, усиленный корешок, мраморное покрытие, 80 мм',
'BRAUBERG', 'Россия', 184.46, 20, 2),
(224610, 'Зажимы для бумаг большие STAFF «EVERYDAY», КОМПЛЕКТ 12 шт., 51 мм, на 230',
'STAFF', 'Россия', 110.39, 100, 3),
(229083, 'Степлер №10 BRAUBERG «Extra», до 20 листов',
'BRAUBERG', 'Россия', 93.56, 3, 4),
(220949, 'Скобы для степлера №10, 1000 штук',
'BRAUBERG', 'Россия', 16.94, 12, 4),
(224342, 'Дырокол металлический BRAUBERG «Metallic», до 40 листов',
'BRAUBERG', 'Россия', 872.31, 7, 4);

insert client
values (1,'Агафонов','Иван','Федорович','+7(953)634-11-20','agafonov@mail.ru'),
(2,'Куницына','Вероника','Игоревна','+7(922)356-23-98','kunizina@yandex.ru'),
(3,'Логвинов','Андрей','Артёмович','+7(933)234-24-18','logvinov1988@gmail.com'),
(4,'Плахов','Дмитрий','Васильевич','+7(999)856-11-25','plahov@inbox.ru'),
(5,'Карлов','Виктор','Петрович','+7(910)311-23-15','karlov2022@gmail.com');

insert status
values (1, 'Заказан'),
(2, 'Собран'),
(3, 'Выдан');

insert orders
values (1001,'2022-08-15',1,3),
(1002,'2022-10-17',2,3),
(1003,'2022-10-25',3,3),
(1004,'2022-12-07',4,2),
(1005,'2022-12-08',5,1);

insert order_tovar
values (143562,1001,10),
(152115,1002,50),
(152115,1003,30),
(224342,1004,10),
(220949,1005,10),
(227188,1005,20),
(224610,1005,15);

/*по заданию добавляем несколько записей в таблицу товар*/
insert tovar (artikul, name, proizv, country, cost, ost, id_category)
values (1234567, 'карандаш brauber, красный, мягкий', 'brauberg', 'Германия', 26.44, 10, 1),
(1234568, 'карандаш brauber, зеленый, мягкий', 'brauberg', 'Германия', 30.21, 7, 1),
(7654321, 'ручка PARKER, гелевая', 'PARKER', 'США', 15.00, 20, 1);