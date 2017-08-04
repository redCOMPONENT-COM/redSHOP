<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Catalog Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerCatalog extends RedshopController
{
	/**
	 * Method to send catalog
	 *
	 * @return void
	 */
	public function catalog_send()
	{
		$post   = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');

		$model  = $this->getModel('catalog');
		$post["registerDate"] = time();
		$post["email"]        = $post["email_address"];
		$post["name"]         = $post["name_2"];

		if ($row = $model->catalogStore($post))
		{
			$redshopMail = redshopMail::getInstance();
			$redshopMail->sendCatalogRequest($row);
			$msg = JText::_('COM_REDSHOP_CATALOG_SEND_SUCCSEEFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_CATALOG_SEND_SUCCSEEFULLY');
		}

		$this->setRedirect('index.php?option=com_redshop&view=catalog&Itemid=' . $Itemid, $msg);
	}

	/**
	 * Method to send catalog sample
	 *
	 * @return void
	 */
	public function catalogsample_send()
	{
		$post   = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');

		$model  = $this->getModel('catalog');

		if (isset($post["sample_code"]))
		{
			$colour_id = implode(",", $post["sample_code"]);
			$post ['colour_id'] = $colour_id;
		}

		$post["registerdate"] = time();
		$post["email"]        = $post["email_address"];
		$post["name"]         = $post["name_2"];

		if ($row = $model->catalogSampleStore($post))
		{
			$extra_field = extra_field::getInstance();
			$extra_field->extra_field_save($post, 9, $row->request_id);
			$msg = JText::_('COM_REDSHOP_SAMPLE_SEND_SUCCSEEFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAMPLE_SEND_SUCCSEEFULLY');
		}

		$this->setRedirect('index.php?option=com_redshop&view=catalog&layout=sample&Itemid=' . $Itemid, $msg);
	}
}
