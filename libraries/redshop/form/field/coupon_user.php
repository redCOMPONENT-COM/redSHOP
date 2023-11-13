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
 * Redshop Voucher Product Search field.
 *
 * @since  1.0
 */
class RedshopFormFieldCoupon_User extends ListField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.0
     */
    public $type = 'Coupon_User';

    /**
     * Method to get the field input markup for a generic list.
     * Use the multiple attribute to enable multiselect.
     *
     * @return  string  The field input markup.
     *
     * @since   3.7.0
     */
    protected function getInput()
    {
        $selected  = array();
        $typeField = ', alert:"coupon"';

        if (!empty($this->value)) {
            $values = !$this->multiple || !is_array($this->value) ? array($this->value) : $this->value;
            $db     = JFactory::getDbo();

            $query = $db->getQuery(true)
                ->select($db->qn(array('user_id', 'user_email', 'firstname')))
                ->from($db->qn('#__redshop_users_info'))
                ->where($db->qn('user_id') . ' = ' . $db->q($values[0]))
                ->where($db->qn('address_type') . ' = ' . $db->q('ST'));

            $users = $db->setQuery($query)->loadObjectList();

            foreach ($users as $user) {
                if (isset($selected[$user->user_id])) {
                    continue;
                }

                $data        = new stdClass;
                $data->value = $user->user_id;
                $data->text  = '(' . $user->firstname . ')' . ' ' . $user->user_email;

                $selected = $data;
            }
        }

        return JHtml::_(
            'redshopselect.search',
            $selected,
            'jform[' . $this->fieldname . ']',
            array(
                'select2.ajaxOptions' => array(
                    'typeField' => $typeField
                )
            )
        );
    }
}
