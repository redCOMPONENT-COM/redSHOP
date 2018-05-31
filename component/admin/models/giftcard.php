<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Gift Card Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Giftcards
 * @since       1.6
 */
class RedshopModelGiftcard extends RedshopModelForm
{
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  Exception
	 */
	public function save($data)
	{
		$input = JFactory::getApplication()->input;

		$bgImage = $input->post->getString('giftcard_bgimage', '');
		$image   = $input->post->getString('giftcard_image', '');

		if (!JFolder::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/'))
		{
			JFolder::create(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/');
		}

		// Giftcard background image process
		if (!empty($bgImage))
		{
			// Make the filename unique
			$fileName = basename($bgImage);
			$data['giftcard_bgimage'] = $fileName;
			JFile::move(JPATH_ROOT . '/' . $bgImage, REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $fileName);
		}

		// Giftcard background image process
		if (!empty($image))
		{
			// Make the filename unique
			$fileName = RedshopHelperMedia::cleanFileName(basename($image));
			$data['giftcard_image'] = $fileName;
			JFile::move(JPATH_ROOT . '/' . $image, REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $fileName);
		}

		return parent::save($data);
	}
}


