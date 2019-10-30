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
 * Tool Image controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.1.0
 */
class RedshopControllerTool_Image extends RedshopController
{
	/**
	 * Method for get overall image files.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function getImages()
	{
		JSession::checkToken() or jexit(JText::_('INVALID_TOKEN'));

		$images = array();

		// Folder
		$folders = JFolder::folders(REDSHOP_FRONT_IMAGES_RELPATH);

		foreach ($folders as $index => $folder)
		{
			if ($folder == 'tmp')
			{
				unset($folders[$index]);

				continue;
			}

			$files = JFolder::files(REDSHOP_FRONT_IMAGES_RELPATH . $folder);

			foreach ($files as $key => $file)
			{
				if ($file == 'index.html')
				{
					unset($files[$key]);

					continue;
				}

				$images[] = REDSHOP_FRONT_IMAGES_RELPATH . $folder . '/' . $file;
			}
		}

		JFactory::getApplication()->setUserState('com_redshop.tools.images', $images);

		echo JText::sprintf('COM_REDSHOP_TOOLS_IMAGES_COUNT', count($images));

		JFactory::getApplication()->close();
	}

	/**
	 * Method for get overall image files.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function cleanThumbFolders()
	{
		JSession::checkToken() or jexit(JText::_('INVALID_TOKEN'));

		$results = array();

		// Folder
		$folders = JFolder::folders(REDSHOP_FRONT_IMAGES_RELPATH);

		foreach ($folders as $index => $folder)
		{
			if ($folder == 'tmp')
			{
				unset($folders[$index]);

				continue;
			}

			$subFolders = JFolder::folders(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $folder);

			foreach ($subFolders as $subFolder)
			{
				if ($subFolder != 'thumb')
				{
					continue;
				}

				JFolder::delete(JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $folder . '/thumb'));
				JFolder::create(JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $folder . '/thumb'));
				JFile::copy(
					REDSHOP_FRONT_IMAGES_RELPATH . '/index.html',
					REDSHOP_FRONT_IMAGES_RELPATH . '/' . $folder . '/thumb/index.html'
				);
				$results[] = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $folder . '/thumb');
			}
		}

		echo json_encode($results);

		JFactory::getApplication()->close();
	}

	/**
	 * Method for process image checks.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function processImageCheck()
	{
		JSession::checkToken() or jexit(JText::_('INVALID_TOKEN'));

		$app   = JFactory::getApplication();
		$files = $app->getUserState('com_redshop.tools.images', array());
		$file  = array_shift($files);

		if (empty($file))
		{
			$results = array('status' => 1, 'msg' => JText::_('COM_REDSHOP_TOOLS_IMAGES_DONE'));
			$app->setUserState('com_redshop.tools.images', null);
		}
		else
		{
			if (JFile::exists($file) && RedshopHelperMedia::isImage($file))
			{
				$maxWidth  = Redshop::getConfig()->getInt('IMAGE_MAX_WIDTH', 2048);
				$maxHeight = Redshop::getConfig()->getInt('IMAGE_MAX_HEIGHT', 2048);

				list($width, $height, $type, $attr) = getimagesize($file);

				if ($width >= $maxWidth || $height >= $maxHeight)
				{
					RedshopHelperMedia::resizeImage(
						$file, $maxWidth, $maxHeight, Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING'), 'file', false
					);
				}
			}

			$results = array('status' => 2, 'msg' => $file);
			$app->setUserState('com_redshop.tools.images', $files);
		}

		echo json_encode($results);

		$app->close();
	}
}
