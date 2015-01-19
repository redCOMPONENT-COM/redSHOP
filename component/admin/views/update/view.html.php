<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Class RedshopViewUpdate
 *
 * @since  1.4
 */
class RedshopViewUpdate extends RedshopView
{
	/**
	 * Display method
	 *
	 * @param   string  $tpl  The template name
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_UPDATE_TITLE'), 'importexport48 icon-refresh');
		JToolBarHelper::custom('update.update', 'refresh', '', JText::_('COM_REDSHOP_UPDATE_START'), false);
		RedshopToolbarHelper::link('index.php?option=com_redshop', 'cancel', JText::_('COM_REDSHOP_UPDATE_BACK_TO_REDSHOP'));
		parent::display($tpl);
	}
}
