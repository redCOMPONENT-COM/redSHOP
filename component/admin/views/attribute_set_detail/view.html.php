<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewAttribute_set_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$cfg = JFactory::getConfig();
		$lists = array();

		$model = $this->getModel('attribute_set_detail');

		$attributes = $model->getattributes();

		JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_SET_DETAIL'), 'redshop_attribute_bank48');
		$document = JFactory::getDocument();

		$document->addScriptDeclaration("
			var WANT_TO_DELETE = '" . JText::_('COM_REDSHOP_DO_WANT_TO_DELETE') . "';
		");

		if (version_compare(JVERSION, '3.0', '<'))
		{
			$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/update.css');
		}

		$document->addScript(JURI::root() . 'administrator/components/com_redshop/assets/js/attribute_manipulation.js');

		$document->addScript('components/com_redshop/assets/js/fields.js');
		$document->addScript('components/com_redshop/assets/js/select_sort.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');

		$uri = JFactory::getURI();

		$detail = $this->get('data');

		$isNew = ($detail->attribute_set_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_SET') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_attribute_bank48');

		JToolBarHelper::apply();

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
		$lists['attributes'] = $attributes;

		$this->model = $model;
		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
