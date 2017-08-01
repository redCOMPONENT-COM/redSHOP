<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Renders a ePay card Types Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.7
 */
class JFormFieldEpayCardTypes extends JFormField
{
	/**
	 * Element name
	 *
	 * @var   string
	 */
	public $type = 'epaycardtypes';

	/**
	 * Get input HTML
	 *
	 * @return  string
	 *
	 * @since  1.7
	 */
	protected function getInput()
	{
		JFactory::getDocument()->addScriptDeclaration('
	        function enableDisableAll() {
                if (document.getElementById("ALL").checked == true) {
                    document.getElementById("VD").checked = true;
                    document.getElementById("ED").checked = true;
                    document.getElementById("VE").checked = true;
                    document.getElementById("MC").checked = true;
                    document.getElementById("JCB").checked = true;
                    document.getElementById("DINERS").checked = true;
                    document.getElementById("AE").checked = true;
                    document.getElementById("MAESTRO").checked = true;
                    document.getElementById("FORBRUGSFORENINGEN").checked = true;
                    document.getElementById("NORDEA").checked = true;
                    document.getElementById("DANSKE").checked = true;
                    document.getElementById("PAYPAL").checked = true;
                    document.getElementById("MOBILPENGE").checked = true;
                } else {
                    document.getElementById("VD").checked = false;
                    document.getElementById("ED").checked = false;
                    document.getElementById("VE").checked = false;
                    document.getElementById("MC").checked = false;
                    document.getElementById("JCB").checked = false;
                    document.getElementById("DINERS").checked = false;
                    document.getElementById("AE").checked = false;
                    document.getElementById("MAESTRO").checked = false;
                    document.getElementById("FORBRUGSFORENINGEN").checked = false;
                    document.getElementById("NORDEA").checked = false;
                    document.getElementById("DANSKE").checked = false;
                    document.getElementById("PAYPAL").checked = false;
                    document.getElementById("MOBILPENGE").checked = false;
                }
            }
	    ');

		// This might get a conflict with the dynamic translation - TODO: search for better solution

		//$selected_cc = explode(",",$this->detail->accepted_credict_card);
		$creditCards = array();

		$creditCards['ALL']                = 'All cards';
		$creditCards['VD']                 = 'Dankort/VISA Dankort';
		$creditCards['ED']                 = 'eDankort';
		$creditCards['VE']                 = 'VISA / VISA Electron';
		$creditCards['MC']                 = 'MASTERCARD';
		$creditCards['JCB']                = 'JCB';
		$creditCards['DINERS']             = 'DINERS';
		$creditCards['MAESTRO']            = 'MAESTRO';
		$creditCards['AE']                 = 'AMERICAN EXPRESS';
		$creditCards['FORBRUGSFORENINGEN'] = 'FORBRUGSFORENINGEN';
		$creditCards['NORDEA']             = 'NORDEA';
		$creditCards['DANSKE']             = 'Danske Netbetalinger';
		$creditCards['PAYPAL']             = 'PAYPAL';
		$creditCards['MOBILPENGE']         = 'MOBILPENGE';

		$html = '';

		foreach ($creditCards as $key => $value)
		{
			if (count($this->value) != 0 || $this->value != 0)
			{
				if (is_array($this->value))
				{
					$checked = in_array($key, $this->value) ? "checked" : "";
				}
				else
				{
					$checked = ($key == $this->value) ? "checked" : "";
				}
			}
			else
			{
				$checked = '';
			}


			if ($key == 'ALL')
			{
				if ($key == $this->value)
				{
					$checked = 'checked';
				}

				$html .= "<label><input type='checkbox' id='" . $key . "'[]' name='" . $this->name . "[]'  value='" . $key . "' "
					. $checked . " onclick='javascript:enableDisableAll();'  />" . $value . "&nbsp;<br /></label>";
			}
			else
			{
				$html .= "<label><input type='checkbox' id='" . $key . "'[]' name='" . $this->name . "[]'  value='" . $key . "' "
					. $checked . "   />" . $value . "&nbsp;<br /></label>";
			}
		}

		return $html;
	}
}
