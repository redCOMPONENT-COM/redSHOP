<?php
/**
 * @package     redSHOP
 * @subpackage  Core.Controller
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Base Controller for admin Controllers.
 * Provides JControllerAdmin functionalities + copy task + new JController methods.
 *
 * @package     redSHOP
 * @subpackage  Core.Controller
 */
class RedshopCoreControllerAdmin extends JControllerLegacy
{
    /**
     * The application object.
     *
     * @var  JApplication
     */
    protected $app;

    /**
     * The input object.
     *
     * @var  JInput
     */
    protected $input;

    /**
     * The component name.
     *
     * @var  string
     */
    protected $option = 'com_redshop';

    /**
     * Constructor.
     * Backward and forward compatibility.
     *
     * @param   array         $config  An array of optional constructor options.
     * @param   JInput        $input   The input object.
     * @param   JApplication  $app     The application object.
     *
     * @throws  RuntimeException
     */
    public function __construct($config = array(), JInput $input = null, JApplication $app = null)
    {
        parent::__construct($config);

        $this->app   = isset($app) ? $app : $this->loadApplication();
        $this->input = isset($input) ? $input : $this->loadInput();

        // Define standard task mappings.

        // Value = 0
        $this->registerTask('unpublish', 'publish');

        // Value = 2
        $this->registerTask('archive', 'publish');

        // Value = -2
        $this->registerTask('trash', 'publish');

        // Value = -3
        $this->registerTask('report', 'publish');

        $this->registerTask('orderup', 'reorder');
        $this->registerTask('orderdown', 'reorder');

        // Guess the JText message prefix. Defaults to the option.
        if (empty($this->text_prefix))
        {
            $this->text_prefix = strtoupper('com_redshop');
        }

        // Guess the list view as the suffix, eg: OptionControllerSuffix.
        if (empty($this->view_list))
        {
            $r = null;
            if (!preg_match('/(.*)Controller(.*)/i', get_class($this), $r))
            {
                throw new RuntimeException(JText::_('JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME'), 500);
            }
            $this->view_list = strtolower($r[2]);
        }
    }

    /**
     * Removes an item.
     *
     * @return  void
     *
     * @throws  RuntimeException
     */
    public function delete()
    {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // Get items to remove from the request.
        $cid = $this->input->get('cid', array(), '', 'array');

        if (empty($cid))
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'), 500);
        }
        else
        {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            jimport('joomla.utilities.arrayhelper');
            JArrayHelper::toInteger($cid);

            // Remove the items.
            if ($model->delete($cid))
            {
                $this->setMessage(JText::_('COM_REDSHOP_DELETED_SUCCESSFULLY'));
            }
            else
            {
                $this->setMessage($model->getError());
            }
        }

        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
    }

    /**
     * Display is not supported by this controller.
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  RedshopCoreControllerAdmin  A JController object to support chaining.
     */
    public function display($cachable = false, $urlparams = false)
    {
        return $this;
    }

    /**
     * Method to publish/unpublish a list of items.
     *
     * @return  void
     *
     * @throws  RuntimeException
     */
    public function publish()
    {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // Get items to publish from the request.
        $cid  = $this->input->get('cid', array(), '', 'array');
        $data = array('publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2, 'report' => -3);

        $task  = $this->getTask();
        $value = JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid))
        {
            // publish
            if ($value == 1)
            {
                $this->setMessage(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
            }

            // unpublish
            elseif ($value == 0)
            {
                $this->setMessage(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
            }
        }
        else
        {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            JArrayHelper::toInteger($cid);

            // Publish the items.
            if (!$model->publish($cid, $value))
            {
                // Create the message.
                $msg = '';
                switch ($value)
                {
                    case -3:
                        $msg = 'Failed to report the item(s)';
                        break;
                    case -2:
                        $msg = 'Failed to trash the item(s)';
                        break;
                    case 0:
                        $msg = 'Failed to unpublish the item(s)';
                        break;
                    case 1:
                        $msg = 'Failed to publish the item(s)';
                        break;
                    case 2:
                        $msg = 'Failed to archive the item(s)';
                        break;
                }

                throw new RuntimeException($msg, 500);
            }

            else
            {
                if ($value == 1)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_PUBLISHED';
                }
                elseif ($value == 0)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_UNPUBLISHED';
                }
                elseif ($value == 2)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_ARCHIVED';
                }
                else
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_TRASHED';
                }

                $this->setMessage(JText::plural($ntext, count($cid)));
            }
        }
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
    }

    /**
     * Changes the order of one or more records.
     *
     * @return  boolean  True on success
     */
    public function reorder()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $ids = $this->input->post->get('cid', array(), 'array');
        $inc = ($this->getTask() == 'orderup') ? -1 : +1;

        $model  = $this->getModel();
        $return = $model->reorder($ids, $inc);

        if ($return === false)
        {
            // Reorder failed.
            $message = JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $model->getError());
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        }

        else
        {
            // Reorder succeeded.
            $message = JText::_('JLIB_APPLICATION_SUCCESS_ITEM_REORDERED');
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message);
            return true;
        }
    }

    /**
     * Method to save the submitted ordering values for records.
     *
     * @return  boolean  True on success
     */
    public function saveorder()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Get the input
        $pks   = $this->input->post->get('cid', array(), 'array');
        $order = $this->input->post->get('order', array(), 'array');

        // Sanitize the input
        JArrayHelper::toInteger($pks);
        JArrayHelper::toInteger($order);

        // Get the model
        $model = $this->getModel();

        // Save the ordering
        $return = $model->saveorder($pks, $order);

        if ($return === false)
        {
            // Reorder failed
            $message = JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $model->getError());
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        }
        else
        {
            // Reorder succeeded.
            $this->setMessage(JText::_('JLIB_APPLICATION_SUCCESS_ORDERING_SAVED'));
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
            return true;
        }
    }

    /**
     * Copy items.
     *
     * @return  boolean  True on success
     */
    public function copy()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $cids = $this->input->post->get('cid', array(), 'array');

        $model  = $this->getModel();
        $return = $model->copy($cids);

        if ($return === false)
        {
            // Checkin failed.
            $message = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        }
        else
        {
            // Checkin succeeded.
            $message = JText::plural('COM_REDSHOP_COPIED', count($cids));
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message);
            return true;
        }
    }

    /**
     * Check in of one or more records.
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public function checkin()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $cids = $this->input->post->get('cid', array(), 'array');

        $model  = $this->getModel();
        $return = $model->checkin($cids);

        if ($return === false)
        {
            // Checkin failed.
            $message = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        }
        else
        {
            // Checkin succeeded.
            $message = JText::plural($this->text_prefix . '_N_ITEMS_CHECKED_IN', count($cids));
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message);
            return true;
        }
    }

    /**
     * Get the application object.
     *
     * @return  JApplicationBase The application object.
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * Get the input object.
     *
     * @return  JInput The input object.
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Serialize the controller.
     *
     * @return   string  The serialized controller.
     */
    public function serialize()
    {
        return serialize($this->input);
    }

    /**
     * Unserialize the controller.
     *
     * @param   string   $input  The serialized controller.
     *
     * @return  JController  Supports chaining.
     *
     * @throws  UnexpectedValueException if input is not the right class.
     */
    public function unserialize($input)
    {
        // Setup dependencies.
        $this->app = $this->loadApplication();

        // Unserialize the input.
        $this->input = unserialize($input);

        if (!($this->input instanceof JInput))
        {
            throw new UnexpectedValueException(sprintf('%s::unserialize would not accept a `%s`.', get_class($this), gettype($this->input)));
        }

        return $this;
    }

    /**
     * Load the application object.
     *
     * @return   JApplicationBase The application object.
     */
    protected function loadApplication()
    {
        return JFactory::getApplication();
    }

    /**
     * Load the input object.
     *
     * @return   JInput The input object.
     */
    protected function loadInput()
    {
        return $this->app->input;
    }
}

