<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewCatalog_detail extends RedshopViewAdmin
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

	public function display($tpl = null)
	{

		JToolBarHelper::title(JText::_('COM_REDSHOP_CATALOG_MANAGEMENT_DETAIL'), 'redshop_catalogmanagement48');
		$document = JFactory::getDocument();

		$document->addStyleSheet('components/com_redshop/assets/css/colorpicker.css');
		$document->addStyleSheet('components/com_redshop/assets/css/layout.css');
		JHtml::script('com_redshop/admin/validation.min.js', false, true);
		JHtml::script('com_redshop/admin/colorpicker.min.js', false, true);
		JHtml::script('com_redshop/admin/eye.min.js', false, true);
		JHtml::script('com_redshop/admin/utils.min.js', false, true);
		JHtml::script('com_redshop/admin/layout.min.js', false, true);

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$layout = JFactory::getApplication()->input->getCmd('layout', 'default');

		$this->setLayout($layout);

		$isNew = ($detail->catalog_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_CATALOG') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_catalogmanagement48');

		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->lists       = $lists;
		$this->detail      = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
