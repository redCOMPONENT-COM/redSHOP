<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity;

defined('_JEXEC') or die;

/**
 * Field Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class Field extends Entity
{
    /**
     * @var    array
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $fieldValues;

    /**
     * Method for get field values
     *
     * @return  array
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getFieldValues()
    {
        if (null == $this->fieldValues) {
            $this->loadFieldValues();
        }

        return $this->fieldValues;
    }

    /**
     * Method for load field values
     *
     * @return  self
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function loadFieldValues()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $db = \Joomla\CMS\Factory::getDbo();

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_fields_value'))
            ->where($db->qn('field_id') . ' = ' . (int)$this->getId())
            ->order($db->qn('value_id') . ' ASC');

        $this->fieldValues = $db->setQuery($query)->loadObjectList();

        return $this;
    }

    /**
     * Method for get group of this field
     *
     * @return  null|RedshopEntityField_Group
     *
     * @since   2.1.0
     */
    public function getGroup()
    {
        if (!$this->hasId() || $this->get('groupId') === null) {
            return null;
        }

        return \RedshopEntityField_Group::getInstance((int)$this->get('groupId'));
    }
}
