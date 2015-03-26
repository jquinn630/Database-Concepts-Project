load data infile 'parts.csv'
insert into table PART
fields terminated by "," optionally enclosed by '"'
(part_num, part_desc)
