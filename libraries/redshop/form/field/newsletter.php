<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Form\Field\ListField;

FormHelper::loadFieldClass('list');

/**
 * Redshop Newsletter field.
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopFormFieldNewsletter extends ListField
{
    /**
     * The form field type.
     *
     * @var    string
     */
    public $type = 'Newsletter';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     */
    protected function getOptions()
    {
        $db      = JFactory::getDbo();
        $query   = $db->getQuery(true)
            ->select($db->qn('id', 'value'))
            ->select($db->qn('name', 'text'))
            ->from($db->qn('#__redshop_newsletter'));
        $options = $db->setQuery($query)->loadObjectList();

        $parentOptions = parent::getOptions();
        $options       = array_merge($parentOptions, $options);

        return $options;
    }
}