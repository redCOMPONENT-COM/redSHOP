<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopViewFields_detail extends RedshopViewAdmin
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
		$extra_field = extra_field::getInstance();

		JToolBarHelper::title(JText::_('COM_REDSHOP_FIELDS_MANAGEMENT_DETAIL'), 'redshop_fields48');

		$document = JFactory::getDocument();
		$document->addScript('components/com_redshop/assets/js/fields.js');

		$uri = JFactory::getURI();
		$this->setLayout('default');
		$lists = array();

		$detail = $this->get('data');

		$filed_data = $extra_field->getFieldValue($detail->field_id);

		$isNew = ($detail->field_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_FIELDS') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_fields48');
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

		$redtemplate = Redtemplate::getInstance();
		$optiontype = $redtemplate->getFieldTypeSections();
		$optionsection = $redtemplate->getFieldSections();

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$lists['show_in_front'] = JHTML::_('select.booleanlist', 'field_show_in_front', 'class="inputbox"', $detail->field_show_in_front);

		$lists['display_in_product'] = JHTML::_('select.booleanlist', 'display_in_product', 'class="inputbox"', $detail->display_in_product);
		$lists['display_in_checkout'] = JHTML::_('select.booleanlist', 'display_in_checkout', 'class="inputbox"', $detail->display_in_checkout);

		$lists['required'] = JHTML::_('select.booleanlist', 'required', 'class="inputbox"', $detail->required);

		$lists['type'] = JHTML::_('select.genericlist', $optiontype, 'field_type', '', 'value', 'text', $detail->field_type);

		$disable = "";

		if ($detail->field_type == 15)
		{
			$disable = "disabled='disabled' ";
		}

		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'field_section',
			'class="inputbox" size="1" ' . $disable . ' onchange="sectionValidation(this.value)"',
			'value', 'text', $detail->field_section
		);
		$lists['extra_data'] = $filed_data;

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
