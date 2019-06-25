<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Rules
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

use Joomla\Registry\Registry;
use Joomla\CMS\Form\Form;

/**
 * Alphanumeric rule.
 *
 * @package     RedSHOP.Backend
 * @subpackage  Rules
 * @since       __DEPLOY_VERSION__
 */
class JFormRuleAlphanumeric extends JFormRule
{
    /**
     * The regular expression to use in testing a form field value.
     *
     * @var    string
     * @since  __DEPLOY_VERSION__
     */
    protected $regex = '/^[a-z0-9]*$/i';

    /**
     * Method to test the value.
     *
     * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
     * @param   mixed              $value    The form field value to validate.
     * @param   string             $group    The field name group control value. This acts as as an array container for the field.
     *                                       For example if the field has name="foo" and the group value is set to "bar" then the
     *                                       full field name would end up being "bar[foo]".
     * @param   Registry           $input    An optional Registry object with the entire data set to validate against the entire form.
     * @param   Form               $form     The form object for which the field is being tested.
     *
     * @return  boolean  True if the value is valid, false otherwise.
     *
     * @since   __DEPLOY_VERSION__
     * @throws  \UnexpectedValueException if rule is invalid.
     */
    public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
    {
        if (is_null($input))
        {
            return false;
        }

        $code = $input->get('code');

        if (!preg_match($this->regex, $code))
        {
            $element->addAttribute('message', JText::_('COM_REDSHOP_DISCOUNT_CODE_CAN_ONLY_BE_ALPHANUMERIC'));

            return false;
        }

        return true;
    }
}
