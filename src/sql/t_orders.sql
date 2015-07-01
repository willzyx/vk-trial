CREATE TABLE t_orders (
   order_id int PRIMARY KEY AUTO_INCREMENT,
   perform_uid varchar(30),
   perform_timestamp bigint,
   data_order blob,
   data_perform blob
);

CREATE INDEX idx_t_orders_perform ON t_orders (perform_uid, perform_timestamp);
