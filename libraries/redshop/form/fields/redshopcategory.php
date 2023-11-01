<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormHelper;

JLoader::import('redshop.library');

FormHelper::loadFieldClass('list');

// Load library language
$lang = JFactory::getLanguage();
$lang->load('com_redshop', JPATH_ADMINISTRATOR);

/**
 * element for default product layout
 *
 * @since  1.5.0.1
 */
class JFormFieldRedshopCategory extends JFormFieldList
{
    /**
     * A static cache.
     *
     * @var array|null
     */
    protected static $cache = null;
    /**
     * Element name
     *
     * @var  string
     */
    public $type = 'redshopcategory';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     */
    protected function getOptions()
    {
        // Process value
        if (!empty($this->value) && $this->multiple && !is_array($this->value)) {
            $this->value = explode(',', $this->value);
        }

        $options = array();

        if (!$this->multiple) {
            $options[] = JHtml::_('select.option', '', Text::_('COM_REDSHOP_SELECT_CATEGORY'), 'value', 'text');
        }

        if (!self::$cache) {
            // Get the categories.
            self::$cache = RedshopHelperCategory::getCategoryListArray();
        }

        // Build the field options.
        if (!empty(self::$cache)) {
            if ($this->multiple) {
                $obj        = new stdClass;
                $obj->value = '<OPTGROUP>';
                $obj->text  = Text::_('COM_REDSHOP_SELECT_CATEGORY');

                $options[] = $obj;
            }

            foreach (self::$cache as $item) {
                $options[] = JHtml::_('select.option', $item->id, $item->name, 'value', 'text');
            }

            if ($this->multiple) {
                $obj        = new stdClass;
                $obj->value = '</OPTGROUP>';
                $obj->text  = Text::_('COM_REDSHOP_SELECT_CATEGORY');

                $options[] = $obj;
            }
        }

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
