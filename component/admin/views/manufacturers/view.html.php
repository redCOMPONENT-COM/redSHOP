<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The manufacturers view
 *
 * @package     RedSHOP.Backend
 * @subpackage  States.View
 * @since       2.1.0
 */
class RedshopViewManufacturers extends RedshopViewList
{
	/**
	 * @var  boolean
	 */
	public $hasOrdering = true;

	/**
	 * @var  boolean
	 */
	public $enableDuplicate = true;

	/**
	 * Method for render 'Published' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   2.0.7
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if ($config['dataCol'] !== 'media')
		{
			return parent::onRenderColumn($config, $index, $row);
		}

		$media = RedshopEntityManufacturer::getInstance($row->id)->getMedia();

		if (!$media->isValid())
		{
			return '';
		}

		$mediaFile = $media->generateThumb(100, 100);

		return '<a class="joom-box img-thumbnail" href="' . $media->getAbsImagePath() . '"'
			. 'rel="{handler: \'image\', size: {}}">'
			. '<img src="' . $mediaFile['abs'] . '" height="50" width="50"/>'
			. '</a>';
	}
}
