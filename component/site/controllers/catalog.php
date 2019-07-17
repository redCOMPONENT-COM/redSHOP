<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @throws  Exception
	 *
	 * @return  void
	 */
	public function catalog_send()
	{
		$input  = JFactory::getApplication()->input;
		$post   = $input->post->getArray();
		$itemId = $input->get('Itemid');

		/** @var RedshopModelCatalog $model */
		$model = $this->getModel('catalog');

		$post["registerDate"] = time();
		$post["email"]        = $post["email_address"];
		$post["name"]         = $post["name_2"];

		$row = $model->catalogStore($post);

		if ($row)
		{
			Redshop\Mail\Catalog::sendRequest($row);
			$msg = JText::_('COM_REDSHOP_CATALOG_SEND_SUCCSEEFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_CATALOG_SEND_SUCCSEEFULLY');
		}

		$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=catalog&Itemid=' . $itemId, false), $msg);
	}

	/**
	 * Method to send catalog sample
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function catalogsample_send()
	{
		$input  = JFactory::getApplication()->input;
		$post   = $input->post->getArray();
		$itemId = $input->get('Itemid');

		/** @var RedshopModelCatalog $model */
		$model = $this->getModel('catalog');

		if (isset($post["sample_code"]))
		{
			$colourId           = implode(",", $post["sample_code"]);
			$post ['colour_id'] = $colourId;
		}

		$post["registerdate"] = time();
		$post["email"]        = $post["email_address"];
		$post["name"]         = $post["name_2"];
		$row                  = $model->catalogSampleStore($post);

		if ($row)
		{
			RedshopHelperExtrafields::extraFieldSave($post, RedshopHelperExtrafields::SECTION_COLOR_SAMPLE, $post['request_id']);
			$msg = JText::_('COM_REDSHOP_SAMPLE_SEND_SUCCSEEFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAMPLE_SEND_SUCCSEEFULLY');
		}

		$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=catalog&layout=sample&Itemid=' . $itemId, false), $msg);
	}
}
