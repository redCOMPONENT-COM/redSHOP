<?php
/**
 * @package     RedSHOP.Plugins
 * @subpackage  Plugin.PlgUserAvatar
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

JLoader::import('redshop.library');

/**
 * Create Avatar user after creating redSHOP User
 *
 * @package     Joomla.User
 * @subpackage  Plugin.RedshopHighrise
 *
 * @since   1.0.0
 */
class PlgUserRedshop_Avatar extends JPlugin
{
	/**
	 * @var string
	 */
	protected $key = 'redshop_avatar';

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Returns an Avatar image generated from a given value
	 *
	 * @param   string $value URL to use
	 *
	 * @return  mixed|string
	 */
	public static function avatar($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}

		return $value;
	}

	/**
	 * Adds additional fields to the user editing form
	 *
	 * @param   JForm $form The form to be altered.
	 * @param   mixed $data The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		// Check we are manipulating a valid form.
		$name = $form->getName();

		if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration')))
		{
			return true;
		}

		// Add the registration fields to the form.
		JForm::addFormPath(__DIR__ . '/profiles');
		$form->loadFile('profile', false);

		$fields = array($this->key);

		// Change fields description when displayed in frontend or backend profile editing
		$app = JFactory::getApplication();

		if ($app->isClient('site') || $name === 'com_users.user' || $name === 'com_admin.profile')
		{
			$form->setFieldAttribute($this->key, 'description', 'PLG_USER_REDSHOP_AVATAR_FIELD_AVATAR_DESC', 'profile');
		}

		foreach ($fields as $field)
		{
			// Case using the users manager in admin
			if ($name === 'com_users.user')
			{
				// Remove the field if it is disabled in registration and profile
				if ($this->params->get('register-require_' . $field, 1) === 0
					&& $this->params->get('profile-require_' . $field, 1) === 0)
				{
					$form->removeField($field, 'profile');
				}
			}
			// Case registration
			elseif ($name === 'com_users.registration')
			{
				// Toggle whether the field is required.
				if ($this->params->get('register-require_' . $field, 1) > 0)
				{
					$form->setFieldAttribute(
						$field, 'required', ($this->params->get('register-require_' . $field) === 2) ? 'required' : '', 'profile'
					);
				}
				else
				{
					$form->removeField($field, 'profile');
				}
			}
			// Case profile in site or admin
			elseif ($name === 'com_users.profile' || $name === 'com_admin.profile')
			{
				// Toggle whether the field is required.
				if ($this->params->get('profile-require_' . $field, 1) > 0)
				{
					$form->setFieldAttribute(
						$field, 'required', ($this->params->get('profile-require_' . $field) === 2) ? 'required' : '', 'profile'
					);
				}
				else
				{
					$form->removeField($field, 'profile');
				}
			}
		}

		// Drop the profile form entirely if there aren't any fields to display.
		$remainingFields = $form->getGroup('profile');

		if (!count($remainingFields))
		{
			$form->removeGroup('profile');
		}

		return true;
	}

	/**
	 * Saves user profile data
	 *
	 * @param   array   $data   entered user data
	 * @param   boolean $isNew  true if this is a new user
	 * @param   boolean $result true if saving the user worked
	 * @param   string  $error  error message
	 *
	 * @throws  InvalidArgumentException
	 *
	 * @return  boolean
	 */
	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId = ArrayHelper::getValue($data, 'id', 0, 'int');

		$files = JFactory::getApplication()->input->files->get('jform');

		if (!$userId || !$result || empty($files['profile']) || empty($files['profile'][$this->key]))
		{
			return true;
		}

		// Image Upload
		return $this->uploadAvatar($files['profile'][$this->key], $userId);
	}

	/**
	 * Remove all user profile information for the given user ID
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param   array   $user    Holds the user data
	 * @param   boolean $success True if user was succesfully stored in the database
	 * @param   string  $msg     Message
	 *
	 * @return  boolean
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$userId = ArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = ' . $userId
					. " AND profile_key LIKE 'profile.%'"
				);

				$db->execute();
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());

				return false;
			}
		}

		return true;
	}

	/**
	 * Method run on trigger replace "account_template"
	 *
	 * @param   string $template Template content
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onReplaceAccountTemplate(&$template)
	{
		if (strpos($template, '{avatar}') === false)
		{
			return;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('profile_value'))
			->from($db->qn('#__user_profiles'))
			->where($db->qn('user_id') . ' = ' . JFactory::getUser()->id)
			->where($db->qn('profile_key') . ' = ' . $db->quote($this->key));

		$avatar = $db->setQuery($query)->loadResult();

		$html = RedshopLayoutHelper::render('redshop_avatar', array('image' => $avatar, 'key' => $this->key), __DIR__ . '/layouts');

		$template = str_replace('{avatar}', $html, $template);
	}

	/**
	 * Upload avatar for user
	 *
	 * @param   array    $file        File data
	 * @param   integer  $userId      ID of user
	 * @param   boolean  $returnPath  True for return path.
	 *
	 * @return  boolean
	 */
	protected function uploadAvatar($file, $userId = 0, $returnPath = false)
	{
		if (empty($file) || JFactory::getUser()->guest)
		{
			return false;
		}

		if (!$userId)
		{
			$userId = JFactory::getUser()->id;
		}

		// Image Upload
		$src = $file['tmp_name'];

		// Check mime
		$mimes = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');

		if (!in_array($file['type'], $mimes))
		{
			$this->_subject->setError('PLG_USER_REDSHOP_AVATAR_ERROR_FILE_MIME');

			return false;
		}

		// Clean up folder
		$folder      = REDSHOP_FRONT_IMAGES_RELPATH . $this->key . '/' . $userId;
		$thumbFolder = $folder . '/thumb';

		if (JFolder::exists($folder))
		{
			JFolder::delete($thumbFolder);
			JFolder::delete($folder);
		}

		// Create main folder
		JFolder::create($folder);
		JFolder::create($thumbFolder);

		// Upload original images.
		$fileName        = RedshopHelperMedia::cleanFileName($file['name']);
		$destinationFile = $folder . '/' . $fileName;

		if (!JFile::upload($src, $destinationFile))
		{
			return false;
		}

		$thumbFile = $thumbFolder . '/' . $fileName;

		if (!JFile::copy($destinationFile, $thumbFile))
		{
			return false;
		}

		// Create thumbnail
		// @TODO: Later move to plugin config or template tag config.
		$thumbWidth  = 150;
		$thumbHeight = 150;
		RedshopHelperMedia::resizeImage($thumbFile, $thumbWidth, $thumbHeight, 1, 'file', false);

		try
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->delete($db->qn('#__user_profiles'))
				->where($db->qn('user_id') . ' = ' . (int) $userId)
				->where($db->qn('profile_key') . ' = ' . $db->q($this->key));
			$db->setQuery($query)->execute();

			$query->clear()
				->insert($db->qn('#__user_profiles'))
				->values($userId . ', ' . $db->quote($this->key) . ', ' . $db->quote($fileName) . ', 1');

			$db->setQuery($query)->execute();
		}
		catch (RuntimeException $e)
		{
			$this->_subject->setError($e->getMessage());

			return false;
		}

		return !$returnPath ? true : REDSHOP_FRONT_IMAGES_ABSPATH . $this->key . '/' . $userId . '/thumb/' . $fileName;
	}

	/**
	 * Method for upload avatar
	 *
	 * @return  string
	 */
	public function onAjaxUploadAvatar()
	{
		JSession::checkToken() or die(JText::_('INVALID_TOKEN'));

		$app = JFactory::getApplication();

		$file = $app->input->post->files->get('avatar');

		echo $this->uploadAvatar($file, 0, true);

		$app->close();
	}
}
