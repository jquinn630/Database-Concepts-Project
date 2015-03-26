load data infile 'salesperson.csv'
insert into table SALESPERSON
fields terminated by "," optionally enclosed by '"'
(sp_id, first, last, year_started)

