<?php
/**
 * @package     redSHOP
 * @subpackage  Core.Model
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Base Model for admin Models.
 * Provides some JModelAdmin functionalities.
 * Copy is not provided atm.
 *
 * @package     redSHOP
 * @subpackage  Core.Model
 */
class RedshopCoreModelAdmin extends JModelLegacy
{
    /**
     * @var  string  The component name for access checking.
     */
    protected $option = 'com_redshop';

    /**
     * Method to check-in a record or an array of records.
     *
     * @param   mixed  $pks  The ID of the primary key or an array of IDs
     *
     * @return  mixed  Boolean false if there is an error, otherwise the count of records checked in.
     */
    public function checkin($pks = array())
    {
        // Initialise variables.
        $pks = (array) $pks;
        $table = $this->getTable();
        $user = JFactory::getUser();
        $count = 0;

        if (empty($pks))
        {
            $pks = array((int) $this->getState($this->getName() . '.id'));
        }

        // Check in all items.
        foreach ($pks as $pk)
        {
            if ($table->load($pk))
            {
                // Check if this is the user having previously checked out the row.
                if ($table->checked_out > 0 && $table->checked_out != $user->get('id') && !$user->authorise('core.admin', 'com_redshop'))
                {
                    $this->setError(JText::_('JLIB_APPLICATION_ERROR_CHECKIN_USER_MISMATCH'));
                    return false;
                }

                // Attempt to check the row in.
                if (!$table->checkin($pk))
                {
                    $this->setError($table->getError());
                    return false;
                }

                $count++;
            }

            else
            {
                $this->setError($table->getError());
                return false;
            }
        }

        return $count;
    }

    /**
     * Method to check-out a record.
     *
     * @param   integer  $pk  The ID of the primary key.
     *
     * @return  boolean  True if successful, false if an error occurs.
     */
    public function checkout($pk = null)
    {
        // Initialise variables.
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');

        // Only attempt to check the row in if it exists.
        if ($pk)
        {
            $user = JFactory::getUser();

            // Get an instance of the row to checkout.
            $table = $this->getTable();

            if (!$table->load($pk))
            {
                $this->setError($table->getError());
                return false;
            }

            // Check if this is the user having previously checked out the row.
            if ($table->checked_out > 0 && $table->checked_out != $user->get('id'))
            {
                $this->setError(JText::_('JLIB_APPLICATION_ERROR_CHECKOUT_USER_MISMATCH'));
                return false;
            }

            // Attempt to check the row out.
            if (!$table->checkout($user->get('id'), $pk))
            {
                $this->setError($table->getError());
                return false;
            }
        }

        return true;
    }

    /**
     * Method to change the published state of one or more records.
     *
     * @param   array    $pks   A list of the primary keys to change.
     * @param   integer  $value  The value of the published state.
     *
     * @return  boolean  True on success.
     */
    public function publish(array $pks, $value = 1)
    {
        $user = JFactory::getUser();
        $table = $this->getTable();

        // Access checks.
        foreach ($pks as $i => $pk)
        {
            $table->reset();

            if ($table->load($pk))
            {
                if (!$this->canEditState($table))
                {
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
                    return false;
                }
            }
        }

        // Attempt to change the state of the records.
        if (!$table->publish($pks, $value, $user->get('id')))
        {
            $this->setError($table->getError());
            return false;
        }

        // Clear the component's cache
        $this->cleanCache();

        return true;
    }

    /**
     * Method to adjust the ordering of a row.
     *
     * Returns NULL if the user did not have edit
     * privileges for any of the selected primary keys.
     *
     * @param   integer  $pks    The ID of the primary key to move.
     * @param   integer  $delta  Increment, usually +1 or -1
     *
     * @return  mixed  False on failure or error, true on success, null if the $pk is empty (no items selected).
     */
    public function reorder($pks, $delta = 0)
    {
        // Initialise variables.
        $table = $this->getTable();
        $pks = (array) $pks;
        $result = true;

        $allowed = true;

        foreach ($pks as $i => $pk)
        {
            $table->reset();

            if ($table->load($pk) && $this->checkout($pk))
            {
                // Access checks.
                if (!$this->canEditState($table))
                {
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    $this->checkin($pk);
                    JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
                    $allowed = false;
                    continue;
                }

                $where = array();
                $where = $this->getReorderConditions($table);

                if (!$table->move($delta, $where))
                {
                    $this->setError($table->getError());
                    unset($pks[$i]);
                    $result = false;
                }

                $this->checkin($pk);
            }
            else
            {
                $this->setError($table->getError());
                unset($pks[$i]);
                $result = false;
            }
        }

        if ($allowed === false && empty($pks))
        {
            $result = null;
        }

        // Clear the component's cache
        if ($result == true)
        {
            $this->cleanCache();
        }

        return $result;
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success, False on error.
     */
    public function save($data)
    {
        // Initialise variables;
        $table = $this->getTable();
        $key = $table->getKeyName();
        $pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
        $isNew = true;

        // Allow an exception to be thrown.
        try
        {
            // Load the row if saving an existing record.
            if ($pk > 0)
            {
                $table->load($pk);
                $isNew = false;
            }

            // Bind the data.
            if (!$table->bind($data))
            {
                $this->setError($table->getError());
                return false;
            }

            // Prepare the row for saving
            $this->prepareTable($table);

            // Check the data.
            if (!$table->check())
            {
                $this->setError($table->getError());
                return false;
            }

            // Store the data.
            if (!$table->store())
            {
                $this->setError($table->getError());
                return false;
            }

            // Clean the cache.
            $this->cleanCache();
        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());

            return false;
        }

        $pkName = $table->getKeyName();

        if (isset($table->$pkName))
        {
            $this->setState($this->getName() . '.id', $table->$pkName);
        }

        $this->setState($this->getName() . '.new', $isNew);

        return true;
    }

    /**
     * Saves the manually set order of records.
     *
     * @param   array    $pks    An array of primary key ids.
     * @param   integer  $order  +1 or -1
     *
     * @return  mixed
     *
     * @since   11.1
     */
    public function saveorder($pks = null, $order = null)
    {
        // Initialise variables.
        $table = $this->getTable();
        $conditions = array();

        if (empty($pks))
        {
            return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
        }

        // Update ordering values
        foreach ($pks as $i => $pk)
        {
            $table->load((int) $pk);

            // Access checks.
            if (!$this->canEditState($table))
            {
                // Prune items that you can't change.
                unset($pks[$i]);
                JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
            }
            elseif ($table->ordering != $order[$i])
            {
                $table->ordering = $order[$i];

                if (!$table->store())
                {
                    $this->setError($table->getError());
                    return false;
                }

                // Remember to reorder within position and client_id
                $condition = $this->getReorderConditions($table);
                $found = false;

                foreach ($conditions as $cond)
                {
                    if ($cond[1] == $condition)
                    {
                        $found = true;
                        break;
                    }
                }

                if (!$found)
                {
                    $key = $table->getKeyName();
                    $conditions[] = array($table->$key, $condition);
                }
            }
        }

        // Execute reorder for each category.
        foreach ($conditions as $cond)
        {
            $table->load($cond[0]);
            $table->reorder($cond[1]);
        }

        // Clear the component's cache
        $this->cleanCache();

        return true;
    }

    /**
     * Prepare and sanitise the table data prior to saving.
     *
     * @param   JTable  $table  The Table object.
     *
     * @return  void
     */
    protected function prepareTable($table)
    {
        // Derived class will provide its own implementation if required.
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
     */
    protected function canEditState($record)
    {
        $user = JFactory::getUser();
        return $user->authorise('core.edit.state', $this->option);
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
     */
    protected function canDelete($record)
    {
        $user = JFactory::getUser();
        return $user->authorise('core.delete', $this->option);
    }

    /**
     * A protected method to get a set of ordering conditions.
     *
     * @param   JTable  $table  A JTable object.
     *
     * @return  array  An array of conditions to add to ordering queries.
     */
    protected function getReorderConditions($table)
    {
        return array();
    }
}
