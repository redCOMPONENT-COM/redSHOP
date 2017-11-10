<?php

/**
 *Page for order status
 */
class OrderStatusJ3Page extends AdminJ3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=order_statuses';

	public static $statusCode = ['id' => 'jform_order_status_code'];

	public static $statusName = ['id' => 'jform_order_status_name'];

	public static $statusPublish = ['id' => 'jform_published'];

	public static $statusUnpublish = ['id' => 'jform_published1'];

	public static $namePage = "Order Status Management";




}