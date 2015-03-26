load data infile 'sales.csv'
insert into table SALES
fields terminated by "," optionally enclosed by '"'
(cust_id, sp_id, vin_num, part_num, price, sale_date, pay_method)
