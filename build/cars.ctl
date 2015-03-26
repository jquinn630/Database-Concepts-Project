load data infile 'cars.csv'
insert into table CAR
fields terminated by "," optionally enclosed by '"'
(vin_num,make,model,year,color,mileage)
