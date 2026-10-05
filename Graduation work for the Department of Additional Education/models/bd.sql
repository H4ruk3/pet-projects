drop database if exists jira;

create database if not exists jira;

use jira;

CREATE table if not exists user(
	user_id INT auto_increment NOT NULL ,
	login VARCHAR(255) NOT NULL unique,
	name VARCHAR(255) not NULL,
    password varchar(255) not null,
	PRIMARY KEY (`user_id`)
);

Create table if not exists access_type(
	access_type_id tinyint not null auto_increment,
	name varchar(255) not null,
	PRIMARY KEY(access_type_id)
);

create table  if not exists team(
	team_id int not null auto_increment,
	name varchar(255) not null,
	PRIMARY KEY(team_id)
);

create table  if not exists user_in_team(
	user_id int not null,
	team_id int not null,
	access_type_id tinyint not null,
	FOREIGN KEY(user_id) REFERENCES user(user_id),
	FOREIGN KEY(team_id) REFERENCES team(team_id),
	FOREIGN KEY(access_type_id) REFERENCES access_type(access_type_id),
	unique(user_id, team_id),
	PRIMARY KEY(user_id, team_id)
);

create table  if not exists team_task(
	team_task_id int not null auto_increment,
	user_id INT not null,
	team_id int not null,
	name varchar(255) not null,
	description VARCHAR(255) not null,
	report VARCHAR(255),
	completed boolean,
	start_date datetime not null,
	end_date datetime,
	FOREIGN KEY(team_id) REFERENCES team(team_id),
	FOREIGN KEY(user_id) REFERENCES user(user_id),
	PRIMARY KEY(team_task_id)
);

insert into access_type(name) values
	('OWNER'), -- Может просматривать все задачи, создавать задачи, назначать эти задачи пользователю DEVELOPER, добавлять пользователей в команду и давать им права доступа
	('DEVELOPER'), -- может просматривать все задачи, отмечать задачи на которые он назначен как выполненные, писать к ним отчет. 
	('VIEWER'), -- может просматривать все задачи
	('GUEST'); -- может просто посмотреть названия задач но даже конкретное описание глянуть не способен



-- при удалении пользователя из таблицы user_in_team переназначить все задачи на владельца команды
DELIMITER //

CREATE TRIGGER before_delete_user_in_team
BEFORE DELETE ON user_in_team
FOR EACH ROW
BEGIN
    DECLARE owner_id INT;
    
    -- Найти владельца команды
    SELECT user_id INTO owner_id
    FROM user_in_team
    WHERE team_id = OLD.team_id AND access_type_id = (SELECT access_type_id FROM access_type WHERE name = 'OWNER')
    LIMIT 1;

    -- Переназначить все задачи на владельца команды
    UPDATE team_task
    SET user_id = owner_id
    WHERE user_id = OLD.user_id AND team_id = OLD.team_id;
END;
//

DELIMITER ;

-- при обновлении поля access_type_id в таблице user_in_team переназначить все задачи на владельца команды
DELIMITER //

CREATE TRIGGER before_update_user_in_team
BEFORE UPDATE ON user_in_team
FOR EACH ROW
BEGIN
    DECLARE owner_id INT;

    -- Если тип доступа изменяется с DEVELOPER (допустим, access_type_id = 2) на любой другой
    IF OLD.access_type_id = 2 AND NEW.access_type_id != 2 THEN
        -- Найти владельца команды
        SELECT user_id INTO owner_id
        FROM user_in_team
        WHERE team_id = OLD.team_id AND access_type_id = 1
        LIMIT 1;

        -- Переназначить все задачи на владельца команды
        UPDATE team_task
        SET user_id = owner_id
        WHERE user_id = OLD.user_id AND team_id = OLD.team_id;
    END IF;
END;
//

DELIMITER ;

