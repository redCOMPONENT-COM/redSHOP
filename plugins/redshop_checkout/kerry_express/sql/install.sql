
INSERT IGNORE INTO `#__redshop_fields` (`title`, `name`, `type`, `desc`, `class`, `section`, `maxlength`, `cols`, `rows`, `size`, `show_in_front`, `required`, `published`, `publish_up`, `publish_down`, `display_in_product`, `ordering`, `display_in_checkout`, `checked_out`, `checked_out_time`, `created_date`, `created_by`, `modified_date`, `modified_by`)
VALUES
	('City', 'rs_kerry_city', '5', '', '', '14', 0, 0, 0, 0, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 7, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', NULL),
	('District', 'rs_kerry_district', '5', '', '', '14', 0, 0, 0, 0, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 8, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', NULL),
	('Ward', 'rs_kerry_ward', '5', '', '', '14', 0, 0, 0, 0, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 9, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', NULL),
	('City', 'rs_kerry_billing_city', '5', '', '', '7', 0, 0, 0, 0, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 7, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', NULL),
	('District', 'rs_kerry_billing_district', '5', '', '', '7', 0, 0, 0, 0, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 8, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', NULL),
	('Ward', 'rs_kerry_billing_ward', '5', '', '', '7', 0, 0, 0, 0, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 9, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', NULL);

INSERT IGNORE INTO `#__redshop_order_status` (`order_status_code`, `order_status_name`, `published`)
VALUES
	('PUX', 'Lấy hàng không thành công', 1),
	('PUP', 'Lấy hàng thành công', 1),
	('SIP', 'Đã đến bưu cục gửi', 1),
	('SIP-L', 'Đã đến trạm trung chuyển', 1),
	('SOP-D', 'Xuất kho đi trả hàng', 1),
	('POD', 'Giao hàng thành công', 1),
	('PODEX', 'Giao hàng không thành công', 1),
	('Cancel', 'Hủy', 1),
	('PODR', 'Chuyển hoàn thành công', 1),
	('SOPR', 'Đang chuyển hoàn', 1);
