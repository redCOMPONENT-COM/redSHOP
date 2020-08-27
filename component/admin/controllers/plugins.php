<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Categories list controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Plugins
 * @since       __DEPLOY_VERSION__
 */
class RedshopControllerPlugins extends RedshopControllerAdmin
{
    /**
     * Proxy for getModel.
     *
     * @param string $name   The model name. Optional.
     * @param string $prefix The class prefix. Optional.
     * @param array  $config Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getModel($name = 'plugins', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}

