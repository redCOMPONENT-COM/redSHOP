<?php
defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
class PlgRedshop_AlertLow_Stock_Alert extends JPlugin
{
	public function preflight()
	{
		$path = \JPath::clean(JPATH_ROOT . '/media/com_redshop/templates/low_stock_alert_mail_template');

		$templateDesc = '<h1>Low stock message.</h1>
		<p>Produc : <b> {product_name} - {product_number} </b> the quality in stock <b>{quantity_min_stock}</b>. The low stock for product is  -  <b>{value_min_stock} </b>.</p>';

		if (!is_dir($path))
		{
			mkdir($path);
		}

		file_put_contents($path . '/low_stock_alert_mail_template.php', $templateDesc);

		//connect database to create custome Field
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$values  = array(
							$db->q('Low Stock Alert'), $db->q('rs_low_stock_alert'), $db->q('1'), $db->q(''), $db->q(''),
							$db->q('1'), null, $db->q('1000'), $db->q('0'), $db->q('0'),
							$db->q('100'), $db->q('1'), $db->q('0'), $db->q('1'), $db->q('0000-00-00 00:00:00'),
							$db->q('0000-00-00 00:00:00'), $db->q('1'), $db->q('7'), $db->q('1'), $db->q('0'),
							$db->q('0000-00-00 00:00:00'), $db->q('0000-00-00 00:00:00'), $db->q('249' ), $db->q('0000-00-00 00:00:00'), $db->q('249' )
						);

		// Insert custom field
		$query->clear ()
			->insert($db->quoteName('#__redshop_fields'))
			->columns($db->quoteName( array(
											'title', 'name', 'type', 'desc', 'class',
											'section', 'groupId', 'maxlength', 'cols', 'rows',
											'size', 'show_in_front', 'required', 'published', 'publish_up',
											'publish_down', 'display_in_product', 'ordering', 'display_in_checkout', 'checked_out',
											'checked_out_time', 'created_date', 'created_by', 'modified_date', 'modified_by'
											)))
			->values(implode(',', $values));

		$db->setQuery($query)->execute();

	}

	public function __construct( &$subject , $config )
	{
		parent::__construct($subject, $config );
		$this->loadLanguage();
	}

	public function  onAfterProductDeleteAlertMinStock($list_product_deleted)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		foreach ( explode(',',$list_product_deleted) as $k => $v )
		{
			$message="'%cid[]=$v%'";

			$query->clear()
				->delete($db->qn('#__redshop_alerts'))
				->where($db->qn('message') . ' LIKE ' . $message);

			$db->setQuery($query)->execute();
		}
	}

	public function storeAlertMinStock($cart)
	{
		// get ID Custom Field Min Stock:
		$id_custom_field_min_stock = $this->params->get('id_min_stock');

		// get ID template min stock :
		$id_min_stock_template = $this->params->get('id_alert_min_stock_template');

		$template_mail = RedshopHelperTemplate::getTemplate('product', $id_min_stock_template);

		// get list ID Product
		$list_id = array();
		for ( $i = 0 ; $i <= $cart['idx'] ;  $i++ )
		{
			if ( !empty( $cart[$i]['product_id'] ) )
			{
				$list_id[] = $cart[$i]['product_id'];
			}
		}

		//get ID Custom Field Min Stock , Defaul :
		$section = 1;
		$type = 1;

		$custom_field_min_stock = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('section') . ' = ' . (int)$section)
			->where($db->qn('type') . ' = ' . (int)$type)
			->where($db->qn('id') . ' = ' . (int)$id_custom_field_min_stock);

		$custom_field_min_stock = $db->setQuery($query)->loadObjectList('id');

		// Get Value Min Stock of Product
		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_fields_data'))
			->where($db->qn('fieldid') . ' = ' . (int)$id_custom_field_min_stock)
			->where($db->qn('section') . ' = ' . (int)$section)
			->where($db->qn('itemid') . ' in (' . implode(',', $list_id) . ')');

		$min_value_product_in_stock = (array)$db->setQuery($query)->loadObjectList('itemid');

		// get infor Product -> add to message
		$query->clear()
			->select([$db->qn('product_id'), $db->qn('product_name'), $db->qn('product_number')])
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_id') . ' in (' . implode(',', $list_id) . ')');

		$info_product = (array)$db->setQuery($query)->loadObjectList('product_id');

		// get value product in stock
		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_product_stockroom_xref'))
			->where($db->qn('product_id') . ' in (' . implode(',', $list_id) . ')');

		$value_product_in_stock = $db->setQuery($query)->loadObjectList('product_id');

		for ( $i = 0 ; $i <= $cart['idx'] ;  $i++ )
		{
			foreach ( $min_value_product_in_stock as $k => $v )
			{
				if ( $cart[$i]['product_id'] == $k  && $value_product_in_stock[$k]->quantity <= $v->data_txt )
				{
					$message='<a href="index.php?option=com_redshop&view=product_detail&task=edit&cid[]='.$info_product[$k]->product_id.'">';

					$message .= JText::sprintf(
						'PLG_REDSHOP_ALERT_ALERT_MIN_STOCK_MESSAGE',
						$info_product[$k]->product_name,
						$info_product[$k]->product_number,
						$value_product_in_stock[$k]->quantity, // = quantity in stock  - quantity in cart
						$custom_field_min_stock[$id_custom_field_min_stock]->title,
						$v->data_txt
					);

					$template_mail['0']->template_desc = str_replace("{product_name}", $info_product[$k]->product_name, $template_mail['0']->template_desc);
					$template_mail['0']->template_desc = str_replace("{product_number}", $info_product[$k]->product_number, $template_mail['0']->template_desc);
					$template_mail['0']->template_desc = str_replace("{title_min_stock}", $custom_field_min_stock[$id_custom_field_min_stock]->title, $template_mail['0']->template_desc);
					$template_mail['0']->template_desc = str_replace("{quantity_min_stock}", $value_product_in_stock[$k]->quantity, $template_mail['0']->template_desc);
					$template_mail['0']->template_desc = str_replace("{value_min_stock}", $v->data_txt, $template_mail['0']->template_desc);

					$query->clear()
						->insert($db->qn('#__redshop_alerts'))
						->columns($db->qn(['message', 'sent_date', 'read']))
						->values($db->q($message) . ',' . $db->q(date('Y-m-d H:i:s')) . ',' . $db->q('0'));

					if( $db->setQuery($query)->execute() )
					{
						$mail = Redshop::getConfig()->get('ADMINISTRATOR_EMAIL');
						$mail = explode(',',$mail);
						
						if( !is_array($mail) && !empty($mail) )
						{
							$this->sendMail($template_mail['0']->template_desc,$mail);
						}
						else
						{
							foreach ( $mail as  $value )
							{
								$this->sendMail($template_mail['0']->template_desc,$value);
							}
						}
					}
				}
			}
		}
	}

	function sendMail( $message , $mail )
	{
		$mailer = \JFactory::getMailer();
		$name= \JText::_('PLG_REDSHOP_ALERT_ALERT_MIN_STOCK_MESSAGE_FROM_NAME');
		$subject = \JText::_('PLG_REDSHOP_ALERT_ALERT_MIN_STOCK_MESSAGE_SUBJECT');
		
		if ( $mailer->sendMail($mail,$name , $mail,$subject, $message, 1,null, null,null,null,null) )
		{
			return true;
		}
		
		return false;
	}

}
