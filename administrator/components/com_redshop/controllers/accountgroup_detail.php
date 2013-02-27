<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

/**
 * Account group detail controller class.
 *
 * @package		redSHOP
 * @subpackage	Controllers
 * @since		1.2
 */
class RedshopControllerAccountgroup_detail extends JControllerForm
{
    /**
     * The URL view list variable.
     *
     * @var  string
     */
    protected $view_list = 'accountgroup';

    /**
     * Method (override) to check if you can save a new or existing record.
     *
     * Adjusts for the primary key name and hands off to the parent class.
     *
     * @param	array	$data  An array of input data.
     * @param	string	$key   The name of the key for the primary key.
     *
     * @return	boolean
     */
    protected function allowSave($data, $key = 'accountgroup_id')
    {
        return parent::allowSave($data, $key);
    }
}
