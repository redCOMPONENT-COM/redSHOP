<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
	<div class="row">
		<div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_MEDIA_UPLOAD_SETTINGS'),
					'content' => $this->loadTemplate('media_upload_settings')
				)
			);
			?>
		</div>
	    <div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_MEDIA_VIDEO_SETTINGS'),
					'content' => $this->loadTemplate('media_video_settings')
				)
			);

			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_MEDIA_YOUTUBE_SETTINGS'),
					'content' => $this->loadTemplate('media_youtube_settings')
				)
			);
			?>
	    </div>
	</div>
</fieldset>
