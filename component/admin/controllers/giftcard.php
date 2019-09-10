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
 * The giftcard detail controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Giftcard
 * @since       1.6
 */
class RedshopControllerGiftcard extends RedshopControllerForm
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
	 * @since   2.1.0
	 */
	public function getModel($name = 'Giftcard', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
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
		$app       = JFactory::getApplication();
		$data      = $this->input->post->get('jform', array(), 'array');
		$file      = $app->input->files->get('jform');
		$model     = $this->getModel();
		$context   = "com_redshop.edit.giftcard";
		$table     = $model->getTable();

		if (null === $key)
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (null === $urlVar)
		{
			$urlVar = $key;
		}

		$recordId  = $this->input->getInt($urlVar);
		/** @scrutinizer ignore-call */
		$form      = $model->getForm($data, false);
		/** @scrutinizer ignore-call */
		$validData = $model->validate($form, $data);

		// Save the data in the session.
		$app->setUserState($context . '.data', $validData);

		if (!$this->
		/** @scrutinizer ignore-call */ isImage($file['giftcard_bgimage_file']['name'], 'background image', $recordId, $urlVar))
		{
			return false;
		}

		if (!$this->
		/** @scrutinizer ignore-call */ isImage($file['giftcard_image_file']['name'], 'image', $recordId, $urlVar))
		{
			return false;
		}

		parent::/** @scrutinizer ignore-call */ save($key = null, $urlVar = null);
	}


	/**
	 * Method check image.
	 *
	 * @return  boolean
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function isImage($imageName, $nameInput, $recordId, $urlVar)
	{
		$supportedImage = array('gif', 'jpg', 'jpeg', 'png');
		$ext            = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

		if (empty($imageName) || in_array($ext, $supportedImage))
		{
			return true;
		}

		// Redirect back to the edit screen.
		/** @scrutinizer ignore-deprecated */ $this->setError(JText::sprintf('COM_REDSHOP_GIFTCARD_ERROR_NOT_IMAGE', $nameInput));
		$this->setMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');

		$this->setRedirect(
			$this->/** @scrutinizer ignore-call */ getRedirectToItemRoute($this->/** @scrutinizer ignore-call */ getRedirectToItemAppend($recordId, $urlVar))
		);

		return false;
	}
}
