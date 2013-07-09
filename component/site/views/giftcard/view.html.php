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

require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

class giftcardViewgiftcard extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$option   = JRequest::getVar('option');
		$params   = $app->getParams($option);
		$document = JFactory::getDocument();
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
		JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');

		$pageheadingtag = JText::_('COM_REDSHOP_REDSHOP');

		$model             = $this->getModel('giftcard');
		$giftcard_template = $model->getGiftcardTemplate();
		$detail            = $this->get('data');

		$this->detail = $detail;
		$this->lists = $lists;
		$this->template = $giftcard_template;
		$this->pageheadingtag = $pageheadingtag;
		$this->params = $params;
		parent::display($tpl);
	}
}
