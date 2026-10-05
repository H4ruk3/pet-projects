create database if not exists barbershop;

use barbershop;

create table if not exists barbershop.position (
	id_position int not null auto_increment,
    name varchar(45) not null unique,
	primary key (id_position)
);

create table if not exists barbershop.employee (
	id_employee int not null auto_increment,
    full_name varchar(200) not null,
    phone_number varchar(20) null default null,
    id_position int not null,
    primary key (id_employee),
    index employee_position (id_position),
    foreign key (id_position) references barbershop.position(id_position)
);

create table if not exists barbershop.schedule (
	date date not null,
    id_employee int not null,
    start_time time not null,
    end_time time not null,
    primary key (date, id_employee),
    index schedule_employee (id_employee),
    foreign key (id_employee) references barbershop.employee(id_employee)
);

create table if not exists barbershop.service (
	id_service int not null auto_increment,
    name varchar(45) not null,
    description varchar(1000) null default null,
    service_duration int not null,
    primary key (id_service)
);

create table if not exists barbershop.service_position (
	id_service int not null,
    id_position int not null,
    price decimal(10,2) not null,
    primary key(id_service, id_position),
    index sp_service (id_service),
    index sp_position (id_position),
    foreign key (id_service) references barbershop.service(id_service),
    foreign key (id_position) references barbershop.position(id_position)
);

create table if not exists barbershop.client (
	id_client int not null auto_increment,
    full_name varchar(200) not null,
    phone_number varchar(20) not null,
    primary key (id_client)
);

create table if not exists barbershop.record (
	id_record int not null auto_increment,
    date date not null,
    time time not null,
    service_rendered tinyint(1) null default null,
    id_client int not null,
    id_employee int not null,
    primary key (id_record),
    index record_client (id_client),
    index record_employee (id_employee),
    index record_date (date),
    foreign key (id_client) references barbershop.client(id_client),
    foreign key (id_employee) references barbershop.employee(id_employee)
);

create table if not exists barbershop.record_service (
	id_record int not null,
    id_service int not null,
    record_duration int not null default 0,
    primary key (id_record, id_service),
    index record_service (id_service),
    foreign key (id_service) references barbershop.service(id_service),
    foreign key (id_record) references barbershop.record(id_record)
);
