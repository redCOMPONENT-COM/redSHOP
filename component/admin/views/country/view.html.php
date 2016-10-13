<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       [version> [<description>]
 */

class RedshopViewCountry extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 * 
	 * @return  void
	 * 
	 * @since   1.x
	 */

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT'), 'redshop_country_48');

		$uri = JFactory::getURI();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		$lists = array();
		$detail = $this->get('data');
		$isNew = ($detail->id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_country_48');

		$this->detail = $detail;
		$this->lists = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
