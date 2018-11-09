START TRANSACTION;

delete from purchase_order;
delete from issual;
delete from request;
delete from student;
delete from staff;
delete from material;
delete from faculty;

ALTER SEQUENCE faculty_id_seq RESTART WITH 1;

ALTER SEQUENCE student_id_seq RESTART WITH 1;

ALTER SEQUENCE staff_id_seq RESTART WITH 1;

ALTER SEQUENCE material_id_seq RESTART WITH 1;

ALTER SEQUENCE request_id_seq RESTART WITH 1;



INSERT INTO faculty (name, email, password, dept, phone_no) VALUES
('Ameer Mulla', 'ameer@iitdh.ac.in', 'password', 'electrical', '9283746382'),
('Bharath B.N.', 'bharath@iitdh.ac.in', 'password1', 'electrical', '9182746301'),
('Ruma Ghosh', 'ruma@iitdh.ac.in', 'password2', 'electrical', '9182746311');


INSERT INTO material ( type, name, quantity, cost, comment) VALUES
( 'component', 'Arduino Uno', 100, 400, ''),
( 'component', '1Ohm Resistors', 200, 2, ''),
( 'equipment', 'Multimeter', 5, 1700, '');


INSERT INTO staff ( name, email, password, phone_no, designation) VALUES
('Parmeshwar', 'parmeshwar.m@iitdh.ac.in', 'password3', '8273640291', 'jts'),
('Ramoji', 'ramoji@iitdh.ac.in', 'password3', '8273640291', 'jts'),
('Parasuram', 'parasuram@iitdh.ac.in', 'password3', '8273640291', 'jts');


INSERT INTO student ( name, roll_no, password, dept, phone_no) VALUES
( 'Harshal Gajjar', '160010003', 'password4', 'CSE', '8372610394'),
( 'Pranay Raj', '160010030', 'password6', 'CSE', '7283940296'),
( 'Niteesh Kumar', '160010029', 'password7', 'CSE', '8274019683');


INSERT INTO request ( type, name, quantity, cost, cause, date, status, student_id, faculty_id) VALUES
( 'equipment', 'Multimeter', 1, 1600, 'To measure earthing', now(), 'Approval Pending', 1, 1),
( 'component', 'ArduPilot', 2, 1400, 'Project', now(), 'Approval Pending', 2, 2),
( 'component', 'Male to Male jumper', 100, 300, 'Course Project', now(), 'Approval Pending', 1, 2),
( 'component', 'Breadboard Mini', 2, 120, 'Course Project', now(), 'Approval Pending', 3, 1);

INSERT INTO issual ( material_id, student_id, staff_id, quantity, issual_instance, comment) VALUES
 ( 3, 1, 1, 1, now(), ''),
 ( 2, 3, 1, 1, now(), '');

INSERT INTO purchase_order (request_id, staff_id, faculty_id, date, status, comment) VALUES
( 2, 1, 1, now(), '', ''),
( 4, 1, 2, now(), '', '');

INSERT INTO material_type (name) VALUES
( 'Component'),
( 'Equipment'),
( 'Consumable');

commit;
