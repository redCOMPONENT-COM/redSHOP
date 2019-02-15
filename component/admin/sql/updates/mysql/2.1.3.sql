ALTER TABLE `#__redshop_order_status`;
  DELETE FROM `#__redshop_order_status`
  WHERE `order_status_code` IN ('RD1', 'RD2', 'PRC');