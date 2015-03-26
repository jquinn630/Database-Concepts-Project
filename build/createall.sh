#!/bin/bash

sqlplus -S jquinn11/jquinn11 @BUILD.sql
sqlldr jquinn11/jquinn11 control=inventory.ctl
sqlldr jquinn11/jquinn11 control=customer.ctl
sqlldr jquinn11/jquinn11 control=parts.ctl
sqlldr jquinn11/jquinn11 control=sales.ctl
sqlldr jquinn11/jquinn11 control=cars.ctl
sqlldr jquinn11/jquinn11 control=salesperson.ctl
