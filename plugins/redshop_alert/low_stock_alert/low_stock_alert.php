<?php
defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
class PlgRedshop_AlertLow_Stock_Alert extends JPlugin
{
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

	public function storeLowStockAlert(&$cart)
	{
		// get ID Custom Field Min Stock:
		$id_custom_field_min_stock = $this->params->get('id_low_stock_alert');

		// get ID template min stock :
		$id_min_stock_template = $this->params->get('id_low_stock_alert_template');

		$template_mail = RedshopHelperTemplate::getTemplate('low_stock_alert_mail_template', $id_min_stock_template);

		// get list ID Product
		$list_id = array();

		foreach ($cart as $key => $value )
		{
			if(!is_numeric ($key))
			{
				continue;
			}

			$list_id[] = $value['product_id'];
		}

		//get ID Custom Field Min Stock , Defaul :
		$section = 1;
		$type = 1;

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
						'PLG_REDSHOP_ALERT_LOW_STOCK_ALERT_MESSAGE',
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

	public function sendMail( $message= null , $mail = null )
	{
		if( empty($mail) || empty($message) )
		{
			return false;
		}

		$mailer = \JFactory::getMailer();
		$name= \JText::_('PLG_REDSHOP_ALERT_LOW_STOCK_ALERT_MESSAGE_FROM_NAME');
		$subject = \JText::_('PLG_REDSHOP_ALERT_LOW_STOCK_ALERT_MESSAGE_SUBJECT');

		if ( $mailer->sendMail($mail,$name , $mail,$subject, $message, 1,null, null,null,null,null) )
		{
			return true;
		}

		return false;
	}

}
