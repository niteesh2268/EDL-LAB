START TRANSACTION;

drop table purchase_order;
drop table issual;
drop table request;
drop table student;
drop table staff;
drop table material;
drop table faculty;

CREATE TABLE faculty (
  id serial NOT NULL primary key ,
  name varchar(50) NOT NULL,
  email varchar(50) NOT NULL,
  password varchar(64),
  dept varchar(50) NOT NULL,
  phone_no varchar(10) NOT NULL,
  flag varchar(32) ,
  verifed boolean,
  reset_flag varchar(64)
);

CREATE TABLE material (
  id serial NOT NULL primary key ,
  type varchar(25) NOT NULL,
  name text NOT NULL,
  quantity int  NOT NULL,
  cost int NOT NULL,
  comment text NOT NULL
  
);

CREATE TABLE staff (
  id serial NOT NULL primary key ,
  name varchar(50) NOT NULL,
  email varchar(50) NOT NULL,
  password varchar(64),
  phone_no varchar(10) NOT NULL,
  designation varchar(15) NOT NULL,
  flag varchar(32) ,
  verifed boolean,
  reset_flag varchar(64)
);

CREATE TABLE student (
  id serial NOT NULL primary key ,
  name varchar(50) NOT NULL,
  roll_no varchar(9) NOT NULL,
  password varchar(64),
  dept varchar(50) NOT NULL,
  phone_no varchar(10) NOT NULL,
  flag varchar(32) ,
  verifed boolean,
  reset_flag varchar(64)
);

CREATE TABLE request (
  id serial NOT NULL primary key ,
  type varchar(25) NOT NULL,
  name text NOT NULL,
  quantity int NOT NULL,
  cost int NOT NULL,
  cause text NOT NULL,
  date timestamp NOT NULL DEFAULT now() ,
  status varchar(20) NOT NULL,
  student_id int NOT NULL,
  faculty_id int NOT NULL,
  foreign key (faculty_id) references faculty,
  foreign key (student_id) references student
);

CREATE TABLE issual (
  id serial NOT NULL primary key ,
  material_id int NOT NULL,
  student_id int NOT NULL,
  staff_id int NOT NULL,
  quantity int NOT NULL,
  issual_instance timestamp NOT NULL DEFAULT now(),
  expected_return timestamp NOT NULL DEFAULT '0001-01-01 00:00:00.000000',
  actual_return timestamp NOT NULL DEFAULT '0001-01-01 00:00:00.000000',
  return_flag boolean DEFAULT '0',
  comment text NOT NULL,
  foreign key (material_id) references material,
  foreign key (staff_id) references staff,
  foreign key (student_id) references student
);

CREATE TABLE purchase_order (
  id serial NOT NULL primary key ,
  request_id int NOT NULL,
  staff_id int NOT NULL,
  faculty_id int NOT NULL,
  date timestamp NOT NULL DEFAULT now(),
  status varchar(15) NOT NULL,
  comment text NOT NULL,
  foreign key (request_id) references request,
  foreign key (staff_id) references staff,
  foreign key (faculty_id) references faculty
);



COMMIT;
