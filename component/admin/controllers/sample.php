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
 * Controller Sample Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.7
 */
class RedshopControllerSample extends RedshopControllerForm
{
    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getModel($name = 'sample', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}
