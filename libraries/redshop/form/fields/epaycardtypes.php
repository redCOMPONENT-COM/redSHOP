<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Renders a Productfinder Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.7
 */
class JFormFieldepaycardtypes extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'epaycardtypes';

	protected function getInput()
	{
		?>
    <script language="JavaScript">
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
    </script>
	<?php // This might get a conflict with the dynamic translation - TODO: search for better solution


		//$selected_cc = explode(",",$this->detail->accepted_credict_card);
		$cc_list = array();

		$cc_list['ALL'] = 'All cards';
		$cc_list['VD'] = 'Dankort/VISA Dankort';
		$cc_list['ED'] = 'eDankort';
		$cc_list['VE'] = 'VISA / VISA Electron';
		$cc_list['MC'] = 'MASTERCARD';
		$cc_list['JCB'] = 'JCB';
		$cc_list['DINERS'] = 'DINERS';
		$cc_list['MAESTRO'] = 'MAESTRO';
		$cc_list['AE'] = 'AMERICAN EXPRESS';
		$cc_list['FORBRUGSFORENINGEN'] = 'FORBRUGSFORENINGEN';
		$cc_list['NORDEA'] = 'NORDEA';
		$cc_list['DANSKE'] = 'Danske Netbetalinger';
		$cc_list['PAYPAL'] = 'PAYPAL';
		$cc_list['MOBILPENGE'] = 'MOBILPENGE';

		$html = '';
		foreach ($cc_list as $key => $valuechk)
		{

			if (count($this->value) != 0 || $this->value != 0)
			{
				if (is_array($this->value))
					$checked = in_array($key, $this->value) ? "checked" : "";
				else
					$checked = ($key == $this->value) ? "checked" : "";
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
				$html .= "<label><input type='checkbox' id='" . $key . "'[]' name='" . $this->name . "[]'  value='" . $key . "' " . $checked . " onclick='javascript:enableDisableAll();'  />" . $valuechk . "&nbsp;<br /></label>";
			}
			else
			{
				$html .= "<label><input type='checkbox' id='" . $key . "'[]' name='" . $this->name . "[]'  value='" . $key . "' " . $checked . "   />" . $valuechk . "&nbsp;<br /></label>";

			}
		}
		return $html;
	}
}
