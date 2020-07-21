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
 * Redshop DiscountPlugins field.
 *
 * @since  1.0
 */
class RedshopFormFieldDiscountPlugins extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.0
     */
    public $type = 'DiscountPlugins';

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
            ->select($db->qn('extension_id', 'value'))
            ->select($db->qn('element', 'text'))
            ->from($db->qn('#__extensions'))
            ->where($db->qn('type') . ' = ' . $db->q('plugin'))
            ->where($db->qn('folder') . ' = ' . $db->q('redshop_promotion'));
        $options = $db->setQuery($query)->loadObjectList();

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
