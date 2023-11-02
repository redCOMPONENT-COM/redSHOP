<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;

/**
 * Renders a Productfinder Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldextrainfo extends FormField
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */

    public $type = 'extrainfo';

    protected function getInput()
    {
        $html = '';
        $html .= "<textarea  name='" . $this->name . "'[]'  id='" . $this->name . "'[]' rows='8' cols='20'>" . $this->value . "</textarea>";

        return $html;
    }
}
