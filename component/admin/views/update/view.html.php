<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Class RedshopViewUpdate
 *
 * @since  1.4
 */
class RedshopViewUpdate extends JViewLegacy
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
		JToolBarHelper::title(JText::_('COM_REDSHOP_UPDATE_TITLE'), 'importexport48');
		JToolBarHelper::custom('update.update', 'refresh', '', JText::_('COM_REDSHOP_UPDATE_START'), false);
		parent::display($tpl);
	}
}
