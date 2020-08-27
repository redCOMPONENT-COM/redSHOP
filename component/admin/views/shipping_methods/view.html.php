<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * View Shipping methods
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewShipping_Methods extends RedshopViewList
{
    /**
     * Column for render published state.
     *
     * @var    array
     * @since  __DEPLOY_VERSION__
     */
    protected $stateColumns = array('enabled');

    /**
     * Column for get value from table
     *
     * @var    array
     * @since  __DEPLOY_VERSION__
     */
    protected $stateColumn = 'enabled';

    /**
     * @var  boolean
     *
     * @since  __DEPLOY_VERSION__
     */
    public $hasFilter = false;

    /**
     * Method for render column
     *
     * @param array  $config Row config.
     * @param int    $index  Row index.
     * @param object $row    Row data.
     *
     * @return  string
     * @throws  Exception
     *
     * @since   __DEPLOY_VERSION__
     */
    public function onRenderColumn($config, $index, $row)
    {
        $cache  = new JRegistry($row->manifest_cache);
        $params = new JRegistry($row->params);

        switch ($config['dataCol']) {
            case 'version' :
                return $cache->get('version');
            case 'support_rate' :
                if ($params->get('is_shipper', 0) == 1) {
                    return '<i class="fa fa-check text-success"></i>';
                }
            case 'support_location' :
                if ($params->get('shipper_location', 0) == 1) {
                    return '<i class="fa fa-check text-success"></i>';
                }
            default:
                return parent::onRenderColumn($config, $index, $row);
        }
    }

    /**
     * Method for generate 4 normal permission.
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function generatePermission()
    {
        if ( ! $this->useUserPermission) {
            return;
        }

        $this->canCreate = false;
        $this->canDelete = false;
        $this->canView   = \RedshopHelperAccess::canView($this->getInstanceName());
        $this->canEdit   = \RedshopHelperAccess::canEdit($this->getInstanceName());
    }
}