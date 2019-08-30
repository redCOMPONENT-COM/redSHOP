<?php

use Joomla\Registry\Registry;

defined('_JEXEC') or die();

class PlgRedshop_AlertLow_Stock_AlertInstallerScript
{
	public function preflight()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');
		$low_stock_alert = JTable::getInstance('Field', 'RedshopTable');

		if (!$low_stock_alert->load(array('name' => 'rs_low_stock_alert')))
		{
			$low_stock_alert->set ('title','Low Stock Alert');
			$low_stock_alert->set ('name','rs_low_stock_alert') ;
			$low_stock_alert->set('type',1) ;
			$low_stock_alert->set ('section',1);
			$low_stock_alert->set ('maxlength',1000);
			$low_stock_alert->set ('cols', 0 );
			$low_stock_alert->set ('rows' , 0 )  ;
			$low_stock_alert->set ('size', 100 );
			$low_stock_alert->set ('show_in_front', 1) ;
			$low_stock_alert->set ('required',0);
			$low_stock_alert->set ('published',1);
			$low_stock_alert->set ('publish_up' , '0000-00-00 00:00:00');
			$low_stock_alert->set ('publish_down', '0000-00-00 00:00:00') ;
			$low_stock_alert->set ('display_in_product' ,1);
			$low_stock_alert->set ('display_in_checkout', 1);
			$low_stock_alert->set ('checked_out', 0 );
			$low_stock_alert->set ('checked_out_time','0000-00-00 00:00:00');
			$low_stock_alert->set ('created_date','0000-00-00 00:00:00' );
			$low_stock_alert->set ('modified_date','0000-00-00 00:00:00');
			$low_stock_alert->store();
		}
		
		$mailTemplateDesc = '<h1>Low stock message.</h1><p>Produc : <b> {product_name} - {product_number} </b> the quality in stock <b>{quantity_min_stock}</b>. The low stock for product is  -  <b>{value_min_stock} </b>.</p>';
		$path = JPATH_ROOT . '/media/com_redshop/templates/low_stock_alert_mail_template';
		
		if (!is_dir($path))
		{
			mkdir($path);
		}
		
		file_put_contents( $path.'/low_stock_alert_mail_template.php' , $mailTemplateDesc);
	}
}