<?php
/**
 * @package    redSHOP
 * @subpackage Models
 *
 * @copyright  Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

/**
 * Account group detail model.
 *
 * @package		redSHOP
 * @subpackage	Models
 * @since		1.2
 */
class RedshopModelAccountgroup_detail extends JModelAdmin
{
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
   /*protected function populateState()
    {
        parent::populateState();

        $user = JFactory::getUser();
        $this->setState('user.id', $user->get('id'));*:
    }

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     */
    public function getTable($name = 'economic_accountgroup', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    /**
     * Abstract method for getting the form from the model.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed  A JForm object on success, false on failure
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_redshop.accountgroup_detail', 'accountgroup_detail',
            array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_redshop.edit.accountgroup_detail.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }
        return $data;
    }
}
