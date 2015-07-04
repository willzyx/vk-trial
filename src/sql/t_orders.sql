CREATE TABLE t_orders (
   order_id varchar(32) PRIMARY KEY,
   perform_uid varchar(30),
   perform_order bigint,
   data_order blob,
   data_perform blob
);

CREATE UNIQUE INDEX idx_t_orders_performing ON t_orders (perform_uid, perform_order);
