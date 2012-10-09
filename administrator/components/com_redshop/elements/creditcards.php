<?php
/**
 * @package     redSHOP
 * @subpackage  Elements
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Renders a Productfinder Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldcreditcards extends JFormField
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
    public $type = 'creditcards';

    protected function getInput()
    {
        // This might get a conflict with the dynamic translation - TODO: search for better solution
        $cc_list             = array();
        $cc_list['VISA']     = 'Visa';
        $cc_list['MC']       = 'MasterCard';
        $cc_list['amex']     = 'American Express';
        $cc_list['maestro']  = 'Maestro';
        $cc_list['jcb']      = 'JCB';
        $cc_list['diners']   = 'Diners Club';
        $cc_list['discover'] = 'Discover';

        $html = '';
        foreach ($cc_list as $key => $valuechk)
        {
            $checked = '';
            if (count($this->value) > 1)
            {
                $checked = in_array($key, $this->value) ? "checked=\"checked\"" : "";
            }
            else if ($this->value != "")
            {
                $checked = ($key == $this->value) ? "checked=\"checked\"" : "";
            }

            $html .= "<label><input type='checkbox' id='" . $key . "'[]' name='" . $this->name . "[]'  value='" . $key . "' " . $checked . "  />" . $valuechk . "&nbsp;<br /></label>";
        }

        return $html;
    }
}
