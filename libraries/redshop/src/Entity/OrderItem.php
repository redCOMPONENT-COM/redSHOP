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
 * Order Item Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class OrderItem extends Entity
{
    /**
     * @var   \Redshop\Entities\Collection
     *
     * @since __DEPLOY_VERSION__
     */
    protected $accessoryItems;

    /**
     * Get the associated table
     *
     * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
     *
     * @return  \RedshopTable|bool
     * @since   __DEPLOY_VERSION__
     */
    public function getTable($name = null)
    {
        return \Joomla\CMS\Table\Table::getInstance('Order_Item_Detail', 'Table');
    }

    /**
     * Method for get accessory items for this order item
     *
     * @return   \Redshop\Entities\Collection   Redshop\Entities\Collection if success. Null otherwise.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getAccessoryItems()
    {
        if (!$this->hasId()) {
            return null;
        }

        if (null === $this->accessoryItems) {
            $this->loadAccessoryItems();
        }

        return $this->accessoryItems;
    }

    /**
     * Method for load accessory items for this order item
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadAccessoryItems()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->accessoryItems = new \Redshop\Entities\Collection;

        $db    = \Joomla\CMS\Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_order_acc_item'))
            ->where($db->qn('order_item_id') . ' = ' . $this->getId());
        $items = $db->setQuery($query)->loadObjectList();

        if (empty($items)) {
            return $this;
        }

        foreach ($items as $item) {
            $entity = \Redshop\Entity\OrderItemAccessory::getInstance($item->order_item_acc_id);

            $entity->bind($item);

            $this->accessoryItems->add($entity);
        }

        return $this;
    }
}
