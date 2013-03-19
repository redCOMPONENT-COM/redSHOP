<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
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
class JFormFieldcardtypes extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'cardtypes';

	protected function getInput()
	{
		?>
    <script language="JavaScript">
        function enableDisableAll() {
            if (document.getElementById("ALL").checked == true) {
                document.getElementById("DANKORT").checked = true;
                document.getElementById("VD").checked = true;
                document.getElementById("VE").checked = true;
                document.getElementById("MCDK").checked = true;
                document.getElementById("MC").checked = true;
                document.getElementById("VEDK").checked = true;
                document.getElementById("JCB").checked = true;
                document.getElementById("DDK").checked = true;
                document.getElementById("MDK").checked = true;
                document.getElementById("AEDK").checked = true;
                document.getElementById("DINERS").checked = true;
                document.getElementById("JCBS").checked = true;
                document.getElementById("AE").checked = true;
                document.getElementById("MAESTRO").checked = true;
                document.getElementById("FORBRUGSFORENINGEN").checked = true;
                document.getElementById("EWIRE").checked = true;
                document.getElementById("VISA").checked = true;
                document.getElementById("IKANO").checked = true;
                document.getElementById("NORDEA").checked = true;
                document.getElementById("DB").checked = true;
                document.getElementById("IKANO").checked = true;
                document.getElementById("MASTERCARDDEBETCARD").checked = true;
            } else {
                document.getElementById("DANKORT").checked = false;
                document.getElementById("VD").checked = false;
                document.getElementById("VE").checked = false;
                document.getElementById("MCDK").checked = false;
                document.getElementById("MC").checked = false;
                document.getElementById("VEDK").checked = false;
                document.getElementById("JCB").checked = false;
                document.getElementById("DDK").checked = false;
                document.getElementById("MDK").checked = false;
                document.getElementById("AEDK").checked = false;
                document.getElementById("DINERS").checked = false;
                document.getElementById("JCBS").checked = false;
                document.getElementById("AE").checked = false;
                document.getElementById("MAESTRO").checked = false;
                document.getElementById("FORBRUGSFORENINGEN").checked = false;
                document.getElementById("EWIRE").checked = false;
                document.getElementById("VISA").checked = false;
                document.getElementById("IKANO").checked = false;
                document.getElementById("NORDEA").checked = false;
                document.getElementById("DB").checked = false;
                document.getElementById("IKANO").checked = false;
                document.getElementById("MASTERCARDDEBETCARD").checked = false;

            }

        }
    </script>
	<?php // This might get a conflict with the dynamic translation - TODO: search for better solution


		//$selected_cc = explode(",",$this->detail->accepted_credict_card);
		$cc_list = array();

		$cc_list['ALL'] = 'All cards';
		$cc_list['DANKORT'] = 'DANKORT';
		$cc_list['VD'] = 'VISA DANKORT';
		$cc_list['VE'] = 'VISA ELECTRON';
		$cc_list['MCDK'] = 'MASTERCARD (DK)';
		$cc_list['MC'] = 'MASTERCARD';
		$cc_list['VEDK'] = 'VISA ELECTRON (DK)';
		$cc_list['JCB'] = 'JCB';
		$cc_list['DDK'] = 'DINERS (DK)';
		$cc_list['MDK'] = 'MAESTRO (DK)';
		$cc_list['AEDK'] = 'AMERICAN EXPRESS (DK)';
		$cc_list['DINERS'] = 'DINERS';
		$cc_list['JCBS'] = 'JCB Secure (3D-Secure)';
		$cc_list['AE'] = 'AMERICAN EXPRESS';
		$cc_list['MAESTRO'] = 'MAESTRO';
		$cc_list['FORBRUGSFORENINGEN'] = 'FORBRUGSFORENINGEN';
		$cc_list['EWIRE'] = 'EWIRE';
		$cc_list['VISA'] = 'VISA';
		$cc_list['IKANO'] = 'IKANO';
		$cc_list['NORDEA'] = 'NORDEA';
		$cc_list['DB'] = 'DANSKE BANK';
		$cc_list['IKANO'] = 'IKANO';
		$cc_list['MASTERCARDDEBETCARD'] = 'MASTERCARD DEBET CARD';

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
