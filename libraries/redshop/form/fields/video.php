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
class RedshopFormFieldVideo extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Video';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$app = JFactory::getApplication();

		/* Get params */
		$id = $app->getUserState('com_redshop.global.media.video.id', '');
		$source = $app->getUserState('com_redshop.global.media.video.source', 'http://clips.vorwaerts-gmbh.de/big_buck_bunny.mp4');
		$mimeType = $app->getUserState('com_redshop.global.media.video.mimetype', 'video/mp4');
		$width = $app->getUserState('com_redshop.global.media.video.width', '560');
		$height = $app->getUserState('com_redshop.global.media.video.height', '315');
		$preload = $app->getUserState('com_redshop.global.media.video.preload', 'none');
		$subtitles = $app->getUserState('com_redshop.global.media.video.preload', '');

		if (!$subtitles)
		{
			$subtitles = array();
		}
		else
		{
			$subtitles = json_decode($subtitles);
		}

		/* Clear states */
		$app->setUserState('com_redshop.global.media.video.id', '');
		$app->setUserState('com_redshop.global.media.video.width', '');
		$app->setUserState('com_redshop.global.media.video.height', '');
		$app->setUserState('com_redshop.global.media.video.preload', '');

		/* Add script */
		$doc = JFactory::getDocument();

		$doc->addStyleSheet(JURI::root() . 'media/com_redshop/js/media-elements/mediaelementplayer.min.css');
		$doc->addScript(JURI::root() . 'media/com_redshop/js/media-elements/mediaelement-and-player.min.js');
		$doc->addScript(JURI::root() . 'media/com_redshop/js/media-elements.js');

		return RedshopLayoutHelper::render(
			'field.video',
			array(
				'id' => $id,
				'source' => $source,
				'mimetype' => $mimeType,
				'width' => $width,
				'height' => $height,
				'preload' => $preload,
				'subtitles' => $subtitles,
				)
			);
	}
}
