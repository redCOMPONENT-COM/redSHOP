<?php
/**
 * @package     redSHOP
 * @subpackage  Core.Controller
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

/**
 * Base Controller for Detail Controllers.
 * ATM only used for detail controllers in Backend.
 *
 * @package     redSHOP
 * @subpackage  Core.Controller
 */
class RedshopCoreControllerDetail extends RedshopCoreController
{
    /**
     * @var  string  The name of the model used with that controller.
     */
    public $modelName = '';

    /**
     * Default save method for detail controllers.
     */
    public function save($apply = 0)
    {

    }

    /**
     * Default apply method.
     *
     * @return  void
     */
    public function apply()
    {
        $this->save(1);
    }

    /**
     * Default send method.
     *
     * @return  void
     */
    public function send()
    {
        $this->save(1);
    }
}

