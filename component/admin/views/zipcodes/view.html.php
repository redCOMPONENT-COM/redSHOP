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
 * View Zipcodes
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.1.3
 */
class RedshopViewZipcodes extends RedshopViewList
{
    /**
     * @var  array
     */
    public $filterFormOptions = array('filtersHidden' => false);

    /**
     * Column for render published state.
     *
     * @var    array
     * @since  2.0.6
     */
    protected $stateColumns = array();

    /**
     * Method for render column
     *
     * @param   array   $config  Row config.
     * @param   int     $index   Row index.
     * @param   object  $row     Row data.
     *
     * @return  string
     * @throws  Exception
     *
     * @since   2.1.3
     */
    public function onRenderColumn($config, $index, $row)
    {
        switch ($config['dataCol']) {
            case 'country_code' :
                return $row->country_name;
            case 'state_code' :
                return $row->state_name;
            default:
                return parent::onRenderColumn($config, $index, $row);
        }
    }
}