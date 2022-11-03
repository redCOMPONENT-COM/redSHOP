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
 * Controller Rating Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       __DEPLOY_VERSION__
 */
class RedshopControllerRating extends RedshopControllerForm
{
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

        if (isset($data['userid']) && $data['userid'] > 0) {
            $user             = JFactory::getUser($data['userid']);
            $data['email']    = $user->email;
            $data['username'] = $user->username;
        }

        if (isset($data['start_date']) && isset($data['end_date'])) {
            \Redshop\DateTime\DateTime::handleDateTimeRange($data['start_date'], $data['end_date']);
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
