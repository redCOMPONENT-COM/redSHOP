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
 * View newsletters
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewPromotions extends RedshopViewList
{
    /**
     * @var  boolean
     *
     * @since  __DEPLOY_VERSION__
     */
    public $hasOrdering = true;

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
     * @since   __DEPLOY_VERSION__
     */
    public function onRenderColumn($config, $index, $row)
    {
        switch ($config['dataCol']) {
            default:
                return parent::onRenderColumn($config, $index, $row);
        }
    }

    /**
     * Method for add toolbar.
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    public function addToolbar()
    {
        JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);

        parent::addToolbar();
    }
}