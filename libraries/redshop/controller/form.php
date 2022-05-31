<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Joomla\String\Inflector;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Input\Input;

jimport('joomla.application.component.controllerform');

/**
 * Redshop Controller
 *
 * @package     Redshop.library
 * @subpackage  Controller
 * @since       1.5
 */
class RedshopControllerForm extends JControllerForm
{
    use \Redshop\Model\Traits\HasDateTimeRange;

    /**
     * The name of the controller
     *
     * @var    string
     * @since  2.1.3
     */
    protected $name;

	/**
	 * Constructor.
	 *
	 * @param   array                 $config       An optional associative array of configuration settings.
	 *                                              Recognized key values include 'name', 'default_task', 'model_path', and
	 *                                              'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface   $factory      The factory.
	 * @param   CMSApplication        $app          The Application for the dispatcher
	 * @param   Input                 $input        Input
	 * @param   FormFactoryInterface  $formFactory  The form factory.
	 *
	 * @since   3.0
	 */
	public function __construct(
		$config = [],
		MVCFactoryInterface $factory = null,
		?CMSApplication $app = null,
		?Input $input = null,
		FormFactoryInterface $formFactory = null
	)
	{
		parent::__construct($config, $factory, $app, $input, $formFactory);

		if (empty($this->view_list))
		{
			$this->view_list = Inflector::pluralize($this->view_item);
		}
    }

    /**
     * Method to cancel an edit.
     *
     * @param   string  $key  The name of the primary key of the URL variable.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     */
    public function cancel($key = null)
    {
        JSession::checkToken() or jexit(JText::_('INVALID_TOKEN'));

        $app     = JFactory::getApplication();
        $model   = $this->getModel();
        $table   = $model->getTable();
        $checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";

        if (empty($key)) {
            $key = $table->getKeyName();
        }

        $recordId = $app->input->getInt($key);

        // Attempt to check-in the current record.
        if ($recordId) {
            // Check we are holding the id in the edit list.
            if (!$this->checkEditId($context, $recordId)) {
                // Somehow the person just went to the form - we don't allow that.
                /** @scrutinizer ignore-deprecated */
                $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
                $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

                // Redirect to the list screen
                $this->setRedirect(
                    $this->getRedirectToListRoute($this->getRedirectToListAppend())
                );

                return false;
            }

            if ($checkin) {
                if ($model->checkin($recordId) === false) {
                    // Check-in failed, go back to the record and display a notice.
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
                    $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

                    // Redirect back to the edit screen.
                    $this->setRedirect(
                        $this->getRedirectToItemRoute($this->getRedirectToItemAppend($recordId, $key))
                    );

                    return false;
                }
            }
        }

        // Clean the session data and redirect.
        $this->releaseEditId($context, $recordId);
        $app->setUserState($context . '.data', null);

        // Redirect to the list screen
        $this->setRedirect(
            $this->getRedirectToListRoute($this->getRedirectToListAppend())
        );

        return true;
    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     */
    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
    {
        $class = get_class($this);

        if (empty($name)) {
            $name = strstr($class, 'Controller');
            $name = str_replace('Controller', '', $name);
        }

        if (empty($prefix)) {
            $prefix = strstr($class, 'Controller', true) . 'Model';
        }

        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Get the JRoute object for a redirect to list.
     *
     * @param   string  $append  An optional string to append to the route
     *
     * @return  string
     */
    protected function getRedirectToListRoute($append = null)
    {
        $returnUrl = $this->input->get('return', '', 'Base64');

        if ($returnUrl) {
            $returnUrl = base64_decode($returnUrl);

            return Redshop\IO\Route::_($returnUrl . $append, false);
        }

        return Redshop\IO\Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $append, false);
    }

    /**
     * Gets the URL arguments to append to a list redirect.
     *
     * @return  string  The arguments to append to the redirect URL.
     */
    protected function getRedirectToListAppend()
    {
        $append = parent::getRedirectToListAppend();

        $return = $this->input->get('return', '', 'Base64');

        if ($return) {
            $append .= '&return=' . $return;
        }

        return $append;
    }

    /**
     * Get the JRoute object for a redirect to item.
     *
     * @param   string  $append  An optionnal string to append to the route
     *
     * @return  string           The url string
     */
    protected function getRedirectToItemRoute($append = null)
    {
        return Redshop\IO\Route::_(
            'index.php?option=' . $this->option . '&view=' . $this->view_item
            . $append,
            false
        );
    }

    /**
     * Gets the URL arguments to append to an item redirect.
     *
     * @param   integer  $recordId  The primary key id for the item.
     * @param   string   $urlVar    The name of the URL variable for the id.
     *
     * @return  string  The arguments to append to the redirect URL.
     */
    protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
    {
        $append = parent::getRedirectToItemAppend($recordId, $urlVar);

        $return = $this->input->get('return', '', 'Base64');

        if ($return) {
            $append .= '&return=' . $return;
        }

        return $append;
    }

    /**
     * Method to edit an existing record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key
     *                           (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if access level check and checkout passes, false otherwise.
     */
    public function edit($key = null, $urlVar = null)
    {
        $app     = JFactory::getApplication();
        $model   = $this->getModel();
        $table   = $model->getTable();
        $cid     = $this->input->post->get('cid', array(), 'array');
        $context = "$this->option.edit.$this->context";

        // Determine the name of the primary key for the data.
        if (empty($key)) {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (empty($urlVar)) {
            $urlVar = $key;
        }

        // Get the previous record id (if any) and the current record id.
        $recordId = (int)(count($cid) ? $cid[0] : $this->input->getInt($urlVar));
        $checkin  = property_exists($table, 'checked_out');

        // Access check.
        if (!$this->allowEdit(array($key => $recordId), $key)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
            $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

            // Redirect to the list screen
            $this->setRedirect(
                $this->getRedirectToListRoute($this->getRedirectToListAppend())
            );

            return false;
        }

        // Attempt to check-out the new record for editing and redirect.
        if ($checkin && !$model->checkout($recordId)) {
            // Check-out failed, display a notice but allow the user to see the record.
            /** @scrutinizer ignore-deprecated */
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()));
            $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

            // Redirect back to the edit screen.
            $this->setRedirect(
                $this->getRedirectToItemRoute($this->getRedirectToItemAppend($recordId, $urlVar))
            );

            return false;
        } else {
            // Check-out succeeded, push the new record id into the session.
            $this->holdEditId($context, $recordId);
            $app->setUserState($context . '.data', null);

            // Redirect back to the edit screen.
            $this->setRedirect(
                $this->getRedirectToItemRoute($this->getRedirectToItemAppend($recordId, $urlVar))
            );

            return true;
        }
    }

    /**
     * Method to add a new record.
     *
     * @return  mixed  True if the record can be added, a error object if not.
     */
    public function add()
    {
        $app     = JFactory::getApplication();
        $context = "$this->option.edit.$this->context";

        // Access check.
        if (!$this->allowAdd()) {
            // Set the internal error and also the redirect error.
            /** @scrutinizer ignore-deprecated */
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
            $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

            // Redirect to the list screen
            $this->setRedirect(
                $this->getRedirectToListRoute($this->getRedirectToListAppend())
            );

            return false;
        }

        // Clear the record edit information from the session.
        $app->setUserState($context . '.data', null);

        // Redirect back to the edit screen.
        $this->setRedirect(
            $this->getRedirectToItemRoute($this->getRedirectToItemAppend())
        );

        return true;
    }

    /**
     * Method to save a record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean          True if successful, false otherwise.
     * @throws  Exception
     */
    public function save($key = null, $urlVar = null)
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app     = JFactory::getApplication();
        $lang    = JFactory::getLanguage();
        $model   = $this->getModel();
        $table   = $model->getTable();
        $data    = $this->input->post->get('jform', array(), 'array');
        $checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
        $task    = $this->getTask();

        // Determine the name of the primary key for the data.
        if (null === $key) {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (null === $urlVar) {
            $urlVar = $key;
        }

        if (isset($data['start_date']) && isset($data['end_date'])) {
            $this->handleDateTimeRange($data['start_date'], $data['end_date']);
        }

        $recordId = $this->input->getInt($urlVar);

        if (!$this->checkEditId($context, $recordId)) {
            // Somehow the person just went to the form and tried to save it. We don't allow that.
            /** @scrutinizer ignore-deprecated */
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
            $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

            // Redirect to the list screen
            $this->setRedirect(
                $this->getRedirectToListRoute($this->getRedirectToListAppend())
            );

            return false;
        }

        // Populate the row id from the session.
        $data[$key] = $recordId;

        // The save2copy task needs to be handled slightly differently.
        if ($task === 'save2copy') {
            // Check-in the original row.
            if ($checkin && $model->checkin($data[$key]) === false) {
                // Check-in failed. Go back to the item and display a notice.
                /** @scrutinizer ignore-deprecated */
                $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
                $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

                // Redirect back to the edit screen.
                $this->setRedirect(
                    $this->getRedirectToItemRoute($this->getRedirectToItemAppend($recordId, $urlVar))
                );

                return false;
            }

            // Reset the ID, the multilingual associations and then treat the request as for Apply.
            $data[$key]           = 0;
            $data['associations'] = array();
            $task                 = 'apply';
        }

        // Access check.
        if (!$this->allowSave($data, $key)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
            $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

            // Redirect to the list screen
            $this->setRedirect(
                $this->getRedirectToListRoute($this->getRedirectToListAppend())
            );

            return false;
        }

        // Validate the posted data.
        // Sometimes the form needs some posted data, such as for plugins and modules.
        $form = $model->getForm($data, false);

        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        // Test whether the data is valid.
        $validData = $model->validate($form, $data);

        // Check for validation errors.
        if ($validData === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'error');
                } else {
                    $app->enqueueMessage($errors[$i], 'error');
                }
            }

            // Save the data in the session.
            $app->setUserState($context . '.data', $data);

            // Redirect back to the edit screen.
            $this->setRedirect(
                $this->getRedirectToItemRoute($this->getRedirectToItemAppend($recordId, $urlVar))
            );

            return false;
        }

        if (!isset($validData['tags'])) {
            $validData['tags'] = null;
        }

        // Attempt to save the data.
        if (!$model->save($validData)) {
            // Save the data in the session.
            $app->setUserState($context . '.data', $validData);

            // Redirect back to the edit screen.
            /** @scrutinizer ignore-deprecated */
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

            // Redirect back to the edit screen.
            $this->setRedirect(
                $this->getRedirectToItemRoute($this->getRedirectToItemAppend($recordId, $urlVar))
            );

            return false;
        }

        // Save succeeded, so check-in the record.
        if ($checkin && $model->checkin($validData[$key]) === false) {
            // Save the data in the session.
            $app->setUserState($context . '.data', $validData);

            // Check-in failed, so go back to the record and display a notice.
            /** @scrutinizer ignore-deprecated */
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
            $this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

            // Redirect back to the edit screen.
            $this->setRedirect(
                $this->getRedirectToItemRoute($this->getRedirectToItemAppend($recordId, $urlVar))
            );

            return false;
        }

        $this->setMessage(
            JText::_(
                ($lang->hasKey(
                    $this->text_prefix . ($recordId == 0 && $app->isClient('site') ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
                )
                    ? $this->text_prefix
                    : 'JLIB_APPLICATION') . ($recordId == 0 && $app->isClient(
                    'site'
                ) ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
            )
        );

        // Redirect the user and adjust session state based on the chosen task.
        switch ($task) {
            case 'apply':
                // Set the record data in the session.
                $recordId = $model->getState($this->context . '.id');
                $this->holdEditId($context, $recordId);
                $app->setUserState($context . '.data', null);
                $model->checkout($recordId);

                // Redirect back to the edit screen.
                $this->setRedirect(
                    $this->getRedirectToItemRoute($this->getRedirectToItemAppend($recordId, $urlVar))
                );
                break;

            case 'save2new':
                // Clear the record id and data from the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState($context . '.data', null);

                // Redirect back to the edit screen.
                $this->setRedirect(
                    $this->getRedirectToItemRoute($this->getRedirectToItemAppend(null, $urlVar))
                );
                break;

            default:
                // Clear the record id and data from the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState($context . '.data', null);

                // Set redirect
                $this->setRedirect(
                    $this->getRedirectToListRoute($this->getRedirectToListAppend())
                );
                break;
        }

        // Invoke the postSave method to allow for the child class to access the model.
        $this->postSaveHook($model, $validData);

        return true;
    }
}
