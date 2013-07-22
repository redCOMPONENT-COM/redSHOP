<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class tax_detailVIEWtax_detail extends JView
{
	public function display($tpl = null)
	{
		$db = JFactory::getDBO();

		JToolBarHelper::title(JText::_('COM_REDSHOP_TAX_MANAGEMENT_DETAIL'), 'redshop_vat48');

		$option = JRequest::getVar('option', '', 'request', 'string');

		$document = JFactory::getDocument();

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->tax_rate_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_TAX') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_vat48');

		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$model = $this->getModel('tax_detail');

		$q = "SELECT  country_3_code as value,country_name as text from #__" . TABLE_PREFIX . "_country ORDER BY country_name ASC";
		$db->setQuery($q);
		$countries = $db->loadObjectList();

		$lists['tax_country'] = JHTML::_('select.genericlist', $countries, 'tax_country',
			'class="inputbox" size="1" onchange="changeStateList();"', 'value', 'text', $detail->tax_country
		);
		$lists['is_eu_country'] = JHTML::_('select.booleanlist', 'is_eu_country', 'class="inputbox"', $detail->is_eu_country);

		$country_list_name = 'tax_country';
		$state_list_name = 'tax_state';
		$selected_country_code = $detail->tax_country;
		$selected_state_code = $detail->tax_state;

		if (empty($selected_state_code))
		{
			$selected_state_code = "originalPos";
		}
		else
		{
			$selected_state_code = "'" . $selected_state_code . "'";
		}

		$db->setQuery("SELECT c.country_id, c.country_3_code, s.state_name, s.state_2_code
						FROM #__" . TABLE_PREFIX . "_country c
						LEFT JOIN #__" . TABLE_PREFIX . "_state s
						ON c.country_id=s.country_id OR s.country_id IS NULL
						ORDER BY c.country_id, s.state_name");

		$states = $db->loadObjectList();

		// Build the State lists for each Country
		$script = "<script language=\"javascript\" type=\"text/javascript\">//<![CDATA[\n";
		$script .= "<!--\n";
		$script .= "var originalOrder = '1';\n";
		$script .= "var originalPos = '$selected_country_code';\n";
		$script .= "var states = new Array();	// array in the format [key,value,text]\n";
		$i = 0;
		$prev_country = '';

		for ($j = 0; $j < count($states); $j++)
		{
			$state = $states[$j];

			$country_3_code = $state->country_3_code;

			if ($state->state_name)
			{
				if ($prev_country != $country_3_code)
				{
					$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','',' -= " . JText::_("COM_REDSHOP_SELECT") . " =-' );\n";
				}

				$prev_country = $country_3_code;

				// Array in the format [key,value,text]
				$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','" . $state->state_2_code . "','" . addslashes($state->state_name) . "' );\n";
			}
			else
			{
				$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','','" . JText::_("COM_REDSHOP_NONE") . "' );\n";
			}
		}

		$script .= "
			function changeStateList() {
			  var selected_country = null;
			  for (var i=0; i<document.adminForm.tax_country.length; i++)
				 if (document.adminForm." . $country_list_name . "[i].selected)
					selected_country = document.adminForm." . $country_list_name . "[i].value;

			  changeDynaList('" . $state_list_name . "',states,selected_country, originalPos, originalOrder);
		 	}
			writeDynaList( 'class=\"inputbox\" name=\"tax_state\" size=\"1\" id=\"state\"', states, originalPos, originalPos, $selected_state_code );
			//-->
			//]]></script>";
		$lists['tax_state'] = $script;

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
