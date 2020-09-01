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
 * Field Group Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class FieldGroup extends Entity
{
    /**
     * List of fields
     *
     * @var \RedshopEntitiesCollection
     * @since __DEPLOY_VERSION__
     */
    protected $fields;

    /**
     * Method for get fields associate with this group
     *
     * @return  \RedshopEntitiesCollection
     *
     * @since __DEPLOY_VERSION__
     */
    public function getFields()
    {
        if (null === $this->fields) {
            $this->loadFields();
        }

        return $this->fields;
    }

    /**
     * Method for load fields associate with this field group
     *
     * @return  self
     *
     * @since __DEPLOY_VERSION__
     */
    protected function loadFields()
    {
        $this->fields = new \RedshopEntitiesCollection;

        if (!$this->hasId()) {
            return $this;
        }

        $db = \Joomla\CMS\Factory::getDbo();

        $query = $db->getQuery(true)
            ->select($db->qn('id'))
            ->from($db->qn('#__redshop_fields'))
            ->where($db->qn('groupId') . ' = ' . $this->getId());

        $result = $db->setQuery($query)->loadColumn();

        if (empty($result)) {
            return $this;
        }

        foreach ($result as $fieldId) {
            $this->fields->add(\Redshop\Entity\Field::getInstance($fieldId));
        }

        return $this;
    }
}
