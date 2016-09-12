<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopViewGiftcard extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$params   = $app->getParams('com_redshop');

		$pageheadingtag = JText::_('COM_REDSHOP_REDSHOP');

		$model             = $this->getModel('giftcard');
		$giftcard_template = $model->getGiftcardTemplate();
		$detail            = $this->get('data');

		$this->detail = $detail;
		$this->template = $giftcard_template;
		$this->pageheadingtag = $pageheadingtag;
		$this->params = $params;
		parent::display($tpl);
	}
}
