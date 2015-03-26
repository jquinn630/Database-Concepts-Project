
drop table CAR;
drop table PART;
drop table INVENTORY;
drop table CUSTOMERS;
drop table SALES;
drop table SALESPERSON;

create table CAR
(
vin_num number(30),
make varchar(30),
model varchar(30),
year number(32),
color varchar(30),
mileage number(32)
);

create table PART
(
part_num number(32),
part_desc varchar(256)
);

create table INVENTORY
(
vin_num number(30),
part_num number(32),
location varchar(30),
cost float,
in_stock number(1)
);

create table CUSTOMERS
(
cust_id number(32),
first varchar(256),
last varchar(256),
street_addr varchar(256),
city varchar(256),
state varchar(256),
zip number(32),
phone number(32)
);

create table SALES
(
cust_id number(32),
sp_id number(30),
vin_num number(30),
part_num number(32),
price float,
sale_date date,
pay_method varchar(30)
);

create table SALESPERSON
(
sp_id number(30),
first varchar(60),
last varchar(60),
year_started number(30)
);

exit;
