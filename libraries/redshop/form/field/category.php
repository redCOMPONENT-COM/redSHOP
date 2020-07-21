<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Manufacturer field.
 *
 * @since  1.0
 */
class RedshopFormFieldCategory extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.0
     */
    public $type = 'category';

    /**
     * Method to get the field input markup.
     *
     * @return  array  The field input markup.
     */
    protected function getOptions()
    {
        // Process value
        if (!empty($this->value) && $this->multiple && !is_array($this->value)) {
            $this->value = explode(',', $this->value);
        }

        $db      = JFactory::getDbo();
        $query   = $db->getQuery(true)
            ->select($db->qn('c.id', 'value'))
            ->select($db->qn('c.name', 'text'))
            ->from($db->qn('#__redshop_category', 'c'))
            ->where($db->qn('c.id') . ' > ' . $db->q('1'));
        $options = $db->setQuery($query)->loadObjectList();

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
