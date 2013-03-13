<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

//require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'category.php');

require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');

class giftcardViewgiftcard extends JView
{

	function display($tpl = null)
	{
		global $mainframe, $context;

		// Request variables
		$option   = JRequest::getVar('option');
		$params   = & $mainframe->getParams($option);
		$document =& JFactory::getDocument();
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
		JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');

		$pageheadingtag = JText::_('COM_REDSHOP_REDSHOP');

		$model             = $this->getModel('giftcard');
		$giftcard_template = $model->getGiftcardTemplate();
		$detail            = $this->get('data');

		$this->assignRef('detail', $detail);
		$this->assignRef('lists', $lists);
		$this->assignRef('template', $giftcard_template);
		$this->assignRef('pageheadingtag', $pageheadingtag);
		$this->assignRef('params', $params);
		parent::display($tpl);
	}
}

?>