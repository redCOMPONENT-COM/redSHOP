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
		$db    = JFactory::getDbo();
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

	public function storeAlert()
	{

		if ( (int) Redshop::getConfig()->get('USE_STOCKROOM') === 0)
		{
			return;
		}

		//Construct:
		$id_custom_field_min_stock  = (int) $this->params->get('id_low_stock_alert');
		$id_min_stock_template      = $this->params->get('id_low_stock_alert_template');
		$template_mail              = RedshopHelperTemplate::getTemplate('low_stock_alert_mail_template', $id_min_stock_template);

		if( empty($id_custom_field_min_stock) || empty($id_min_stock_template) || empty($template_mail) )
		{
			return;
		}

		$list_id                     = $this->getListIdProduct();
		$info_product                = $this->getInfoProduct ($list_id);
		$value_product_in_stock      = $this->getValueProduct ($list_id);
		$custom_field_min_stock      = $this->getCustomFieldMinStock($id_custom_field_min_stock);
		$min_value_product_in_stock  = $this->getMinValueProduct ($id_custom_field_min_stock,$list_id);

		// check validation
		($info_product !== false)               ? $info_product                 : null ;
		($value_product_in_stock !== false)     ? $value_product_in_stock       : null ;
		($custom_field_min_stock !== false)     ? $custom_field_min_stock       : null ;
		($min_value_product_in_stock !== false) ? $min_value_product_in_stock   : null ;

		if( empty($info_product) || empty($value_product_in_stock) || empty($custom_field_min_stock) || empty($min_value_product_in_stock) )
		{
			return;
		}

		$this->ProcessLowStockMail($min_value_product_in_stock,$value_product_in_stock,$custom_field_min_stock,$id_custom_field_min_stock,$info_product,$template_mail);

	}

	public function getCustomFieldMinStock($id_custom_field_min_stock)
	{
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true);
		$section = 1;
		$type    = 1;
		
		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('section') . ' = ' . (int)$section)
			->where($db->qn('type') . ' = ' . (int)$type)
			->where($db->qn('id') . ' = ' . (int)$id_custom_field_min_stock);

		if($db->setQuery($query)->loadObjectList('id'))
		{
			return $db->setQuery($query)->loadObjectList('id');
		}

		return false;
	}

	public function getMinValueProduct($id_custom_field_min_stock,$list_id)
	{
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true);
		$section  = 1;
		
		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_fields_data'))
			->where($db->qn('fieldid') . ' = ' . (int)$id_custom_field_min_stock)
			->where($db->qn('section') . ' = ' . (int)$section)
			->where($db->qn('itemid') . ' in (' . implode(',', $list_id) . ')');

		if($db->setQuery($query)->loadObjectList('itemid'))
		{
			return (array)$db->setQuery($query)->loadObjectList('itemid');
		}

		return false;
	}

	public function getInfoProduct($list_id)
	{
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true);
		
		$query->clear()
			->select([$db->qn('product_id'), $db->qn('product_name'), $db->qn('product_number')])
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_id') . ' in (' . implode(',', $list_id) . ')');

		if($db->setQuery($query)->loadObjectList('product_id'))
		{
			return (array)$db->setQuery($query)->loadObjectList('product_id');
		}

		return false;
	}

	public function getValueProduct($list_id)
	{
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true);

		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_product_stockroom_xref'))
			->where($db->qn('product_id') . ' in (' . implode(',', $list_id) . ')');

		if($db->setQuery($query)->loadObjectList('product_id'))
		{
			return $db->setQuery($query)->loadObjectList('product_id');
		}

		return false;
	}

	public function getListIdProduct()
	{
		$cart = RedshopHelperCartSession::getCart();
		foreach ($cart as $key => $value )
		{
			if(!is_numeric ($key))
			{
				continue;
			}

			$list_id[] = $value['product_id'];
		}

		if(!empty($list_id))
		{
			return $list_id;
		}

		return false;
	}

	public function ProcessLowStockMail($min_value_product_in_stock,$value_product_in_stock,$custom_field_min_stock,$id_custom_field_min_stock,$info_product,$template_mail)
	{
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true);
		$cart      = RedshopHelperCartSession::getCart();

		foreach ($cart as $key_cart => $value_cart )
		{
			if(!is_numeric ($key_cart))
			{
				continue;
			}

			foreach ($cart as $key_cart => $value_cart )
			{
				if(!is_numeric ($key_cart))
				{
					continue;
				}

				foreach ( $min_value_product_in_stock as $k => $v )
				{
					$template_mail_tmp = $template_mail['0']->template_desc;

					if ( $value_cart['product_id'] == $k  && $value_product_in_stock[$k]->quantity <= $v->data_txt )
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

						$template_mail_tmp = str_replace("{product_name}", $info_product[$k]->product_name, $template_mail_tmp);
						$template_mail_tmp = str_replace("{product_number}", $info_product[$k]->product_number, $template_mail_tmp);
						$template_mail_tmp = str_replace("{title_min_stock}", $custom_field_min_stock[$id_custom_field_min_stock]->title, $template_mail_tmp);
						$template_mail_tmp = str_replace("{quantity_min_stock}", $value_product_in_stock[$k]->quantity, $template_mail_tmp);
						$template_mail_tmp = str_replace("{value_min_stock}", $v->data_txt, $template_mail_tmp);

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
								$this->sendMail($template_mail_tmp,$mail);
							}
							elseif(is_array($mail) && !empty($mail))
							{
								foreach ( $mail as  $value_mail )
								{
									$this->sendMail($template_mail_tmp,$value_mail);
								}
							}
						}
					}
				}
			}
		}

		return false;
	}

	public function sendMail( $message= null , $mail = null )
	{
		if( empty($mail) || empty($message) )
		{
			return;
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
