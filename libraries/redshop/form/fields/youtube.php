<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Redshop Countries field.
 *
 * @since  1.0
 */
class RedshopFormFieldYoutube extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Youtube';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$app = JFactory::getApplication();
		$id = $app->getUserState('com_redshop.global.media.youtube.id', '');
		$app->setUserState('com_redshop.global.media.youtube.id', '');

		$width = '560';
		$height = '315';
		$border = '0';

		$isConnect = $this->isConnectedYoutube();

		/**
		 * Check if can not connect Youtube, prevent to load embeded code
		 * Which can cause load page too long.
		 */
		if (!$isConnect)
		{
			return JText::_('COM_REDSHOP_MEDIA_CANNOT_CONNECT_YOUTUBE');
		}

		return RedshopLayoutHelper::render(
			'field.youtube',
			array(
				'id' => $id,
				'width' => $width,
				'height' => $height,
				'border' => $border,
				)
			);
	}

	/**
	 * isConnectedYoutube check whenever connected Youtube or not
	 * 
	 * @return boolean
	 */
	protected function isConnectedYoutube()
	{
		$isConnect = false;

		try
		{
			$connection = fsockopen("www.youtube.com", 80);

			if ($connection)
			{
				$isConnect = true;
				fclose($connection);
			}
		}
		catch (Exception $e)
		{
			$isConnect = false;
		}

		return $isConnect;
	}
}
