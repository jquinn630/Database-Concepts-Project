load data infile 'customer.csv'
insert into table CUSTOMERS
fields terminated by "," optionally enclosed by '"'
(cust_id, first, last, street_addr, city, state, zip, phone)
