load data infile 'inventory.csv'
insert into table INVENTORY
fields terminated by "," optionally enclosed by '"'
(vin_num, part_num, location, cost, in_stock)
