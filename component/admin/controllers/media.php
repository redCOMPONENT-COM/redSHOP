<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Controller Country Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.4
 */
class RedshopControllerMedia extends RedshopControllerForm
{
	/**
	 * AJAX upload a file
	 *
	 * @return void
	 */
	public function ajaxUpload()
	{
		$file = $this->input->files->get('file', array(), 'array');
		$new  = $this->input->post->get('new');

		if (!empty($file))
		{
			$filename = $file['name'];

			// Image Upload
			$src = $file['tmp_name'];

			$path = '/media/com_redshop/files/';

			$tempDir = JPATH_ROOT . $path . 'tmp/';
			JFolder::create($tempDir, 0755);
			$dest = $tempDir . $filename;

			JFile::upload($src, $dest);

			$fileId = '';

			$fileinfo = pathinfo($dest);

			$fileinfo['mimetype'] = mime_content_type($dest);

			switch ($fileinfo['extension'])
			{
				case 'zip':
				case '7z':
					$media_type = 'archives';
					break;

				case 'pdf':
					$media_type = 'pdfs';
					break;

				case 'docx':
				case 'doc':
					$media_type = 'words';
					break;

				case 'xlsx':
				case 'xls':
					$media_type = 'excels';
					break;

				case 'pptx':
				case 'ppt':
					$media_type = 'powerpoints';
					break;

				case 'mp3':
				case 'flac':
					$media_type = 'sounds';
					break;

				case 'mp4':
				case 'mkv':
				case 'flv':
					$media_type = 'videos';
					break;

				case 'txt':
					$media_type = 'texts';
					break;

				case 'jpeg':
				case 'jpg':
				case 'png':
				case 'gif':
					$media_type = 'images';
					break;

				default:
					$media_type = '';
					break;
			}
		}

		$dimension = getimagesize($dest);

		if ($dimension)
		{
			$dimension = $dimension[0] . ' x ' . $dimension[1];
		}

		echo new JResponseJson(
			array(
			'success' => true,
			'file' => array(
					'url'        => $path . 'tmp/' . $filename,
					'name'       => $filename,
					'size'       => RedshopHelperMediaImage::sizeFilter(filesize($dest)),
					'dimension'  => $dimension,
					'media'      => 'tmp',
					'media_type' => $media_type,
					'mime'       => $fileinfo['mimetype'],
					'status'     => ''
				)
			)
		);

		die;
	}

	/**
	 * AJAX delete a file
	 *
	 * @return void
	 */
	public function ajaxDelete()
	{
		$id = $this->input->post->get('id');

		if (!empty($id))
		{
			$model = $this->getModel('media');

			if ($model->deleteFile($id))
			{
				echo new JResponseJson(
					array(
					'success' => true
					)
				);

				die;
			}
		}

		echo new JResponseJson(
			array(
			'success' => false
			)
		);

		die;
	}

	/**
	 * ajaxUpdateSectionId
	 * 
	 * @return void
	 */
	public function ajaxUpdateSectionId()
	{
		$app = JFactory::getApplication();
		$input = $app->input;

		$mediaSection = trim($input->get('media_section', 'product'));

		$app->setUserState('com_redshop.global.media.section', $mediaSection);

		$model = $this->getModel();
		$form = $model->getForm();

		$formHTML = $form->renderField('section_id');

		echo $formHTML;

		$app->close();
	}

	/**
	 * ajaxUpdateYoutubeVideo
	 * 
	 * @return void
	 */
	public function ajaxUpdateYoutubeVideo()
	{
		$app = JFactory::getApplication();

		$input = $app->input;

		$youtubeId = trim($input->get('youtube_id', ''));

		$app->setUserState('com_redshop.global.media.youtube.id', $youtubeId);

		$model = $this->getModel();
		$form = $model->getForm();

		$youtubeVideo = $form->getField('youtube_content')->input;

		echo $youtubeVideo;

		$app->close();
	}
}
