<?php
/**
 * @package     RedShop
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Doctrine\Common\Inflector\Inflector;
use Joomla\Utilities\ArrayHelper;

JLoader::import('joomla.application.component.controlleradmin');

/**
 * Base Controller Admin class.
 *
 * @package     Redcore
 * @subpackage  Controller
 * @since       1.0
 */
abstract class RedshopControllerAdminBase extends JControllerAdmin
{
	/**
	 * The method => state map.
	 *
	 * @var  array
	 */
	protected $states = array(
		'publish'   => 1,
		'unpublish' => 0,
		'archive'   => 2,
		'trash'     => -2,
		'report'    => -3
	);

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @throws  Exception
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// J2.5 compatibility
		if (empty($this->input))
		{
			$this->input = JFactory::getApplication()->input;
		}
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string $name   The model name. Optional.
	 * @param   string $prefix The class prefix. Optional.
	 * @param   array  $config Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 */
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		$class = get_class($this);

		if (empty($name))
		{
			$name = strstr($class, 'Controller');
			$name = str_replace('Controller', '', $name);
			$name = Inflector::singularize($name);
		}

		if (empty($prefix))
		{
			$prefix = strstr($class, 'Controller', true) . 'Model';
		}

		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return    void
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$pks   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		$pks   = ArrayHelper::toInteger($pks);
		$order = ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

	/**
	 * Removes an item.
	 *
	 * @return  void
	 */
	public function delete()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			$cid = ArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->delete($cid))
			{
				$this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError(), 'error');
			}

			// Invoke the postDelete method to allow for the child class to access the model.
			$this->postDeleteHook($model, $cid);
		}

		// Set redirect
		$this->setRedirect($this->getRedirectToListRoute());
	}

	/**
	 * Method to publish a list of items
	 *
	 * @return  void
	 */
	public function publish()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid   = JFactory::getApplication()->input->get('cid', array(), 'array');
		$value = ArrayHelper::getValue($this->states, $this->getTask(), 0, 'int');

		if (empty($cid))
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			$cid = ArrayHelper::toInteger($cid);

			// Publish the items.
			try
			{
				$text = $this->text_prefix;

				if ($model->publish($cid, $value))
				{
					switch ($this->getTask())
					{
						case 'publish':
							$text .= '_N_ITEMS_PUBLISHED';
							break;

						case 'unpublish':
							$text .= '_N_ITEMS_UNPUBLISHED';
							break;

						case 'archive':
							$text .= '_N_ITEMS_ARCHIVED';
							break;

						case 'trash':
							$text .= '_N_ITEMS_TRASHED';
							break;

						case 'report':
							$text .= '_N_ITEMS_REPORTED';
							break;
					}

					$this->setMessage(JText::plural($text, count($cid)));
				}
				else
				{
					$this->setMessage($model->getError(), 'error');
				}
			}
			catch (Exception $e)
			{
				$this->setMessage(JText::_('JLIB_DATABASE_ERROR_ANCESTOR_NODES_LOWER_STATE'), 'error');
			}
		}

		$extension    = $this->input->get('extension');
		$extensionURL = ($extension) ? '&extension=' . $extension : '';

		// Set redirect
		$this->setRedirect($this->getRedirectToListRoute($extensionURL));
	}

	/**
	 * Check in of one or more records.
	 *
	 * @return  boolean  True on success
	 */
	public function checkin()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$ids    = JFactory::getApplication()->input->post->get('cid', array(), 'array');
		$model  = $this->getModel();
		$return = $model->checkin($ids);

		if ($return === false)
		{
			// Checkin failed.
			$message = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());

			// Set redirect
			$this->setRedirect($this->getRedirectToListRoute(), $message, 'error');

			return false;
		}
		else
		{
			// Checkin succeeded.
			$message = JText::plural($this->text_prefix . '_N_ITEMS_CHECKED_IN', count($ids));

			// Set redirect
			$this->setRedirect($this->getRedirectToListRoute(), $message);

			return true;
		}
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

		$ids = JFactory::getApplication()->input->post->get('cid', array(), 'array');
		$inc = ($this->getTask() == 'orderup') ? -1 : 1;

		$model  = $this->getModel();
		$return = $model->reorder($ids, $inc);

		if ($return === false)
		{
			// Reorder failed.
			$message = JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $model->getError());

			// Set redirect
			$this->setRedirect($this->getRedirectToListRoute(), $message, 'error');

			return false;
		}

		else
		{
			// Reorder succeeded.
			$message = JText::_('JLIB_APPLICATION_SUCCESS_ITEM_REORDERED');

			// Set redirect
			$this->setRedirect($this->getRedirectToListRoute(), $message);

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
		$pks   = ArrayHelper::toInteger($pks);
		$order = ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return === false)
		{
			// Reorder failed
			$message = JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $model->getError());

			// Set redirect
			$this->setRedirect($this->getRedirectToListRoute(), $message, 'error');

			return false;
		}

		else
		{
			// Reorder succeeded.
			$this->setMessage(JText::_('JLIB_APPLICATION_SUCCESS_ORDERING_SAVED'));

			// Set redirect
			$this->setRedirect($this->getRedirectToListRoute());

			return true;
		}
	}

	/**
	 * Get the JRoute object for a redirect to list.
	 *
	 * @param   string $append An optional string to append to the route
	 *
	 * @return  string           The JRoute string
	 */
	protected function getRedirectToListRoute($append = null)
	{
		$returnUrl = $this->input->get('return', '', 'Base64');

		if ($returnUrl)
		{
			$returnUrl = base64_decode($returnUrl);

			return JRoute::_($returnUrl . $append, false);
		}
		else
		{
			return JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $append, false);
		}
	}

	/**
	 * Method to publish a list of items
	 *
	 * @return  void
	 */
	public function ajaxInlineEdit()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$editData = $this->input->get('jform_inline', array(), 'ARRAY');
		$editKey  = $this->input->get('id', 0);

		if (empty($editData) || empty($editData[$editKey]))
		{
			echo 0;
		}

		$editData = $editData[$editKey];

		/** @var RedshopTable $table */
		$table  = $this->getModel()->getTable();
		$result = true;

		if (!$table->load($editKey) || !$table->bind($editData) || !$table->check() || !$table->store())
		{
			$result = false;
		}

		echo (int) $result;

		JFactory::getApplication()->close();
	}
}
