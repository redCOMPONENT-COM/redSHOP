<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

JLoader::load('RedshopHelperProduct');

class RedshopViewGiftcard extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$params   = $app->getParams('com_redshop');
		JHtml::script('com_redshop/redbox.js', false, true);
		JHtml::script('com_redshop/common.js', false, true);
		JHtml::script('com_redshop/attribute.js', false, true);
		JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');

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
