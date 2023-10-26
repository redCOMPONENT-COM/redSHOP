<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

JFormHelper::loadFieldClass('list');

/**
 * Renders a Productfinder Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldmanufacturer extends JFormFieldList
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
    public $type = 'manufacturer';


    protected function getInput()
    {
        $db    = JFactory::getDbo();
        $name  = $this->name;
        $class = array();
        $attr  = '';

        // This might get a conflict with the dynamic translation - TODO: search for better solution
        $query = 'SELECT id,name ' .
            ' FROM #__redshop_manufacturer WHERE published=1';
        $db->setQuery($query);
        $options = $db->loadObjectList();
        array_unshift(
            $options,
            HTMLHelper::_(
                'select.option',
                '',
                '- ' . Text::_('COM_REDSHOP_SELECT_MANUFACTURER') . ' -',
                'id',
                'name'
            )
        );

        // Initialize some field attributes.
        $class[] = !empty($this->class) ? $this->class : '';

        $attr .= $this->required ? ' required aria-required="true"' : '';

        if ($class) {
            $attr .= 'class="' . implode(' ', $class) . '"';
        }

        return HTMLHelper::_('
            select.genericlist',
            $options,
            $name,
            trim($attr),
            'id',
            'name',
            $this->value,
            $this->id
        );
    }
}