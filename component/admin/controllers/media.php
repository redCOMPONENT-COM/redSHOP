<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class RedshopControllerMedia extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * @return  void
	 *
	 * @since  2.0.6
	 */
	public function saveAdditionalFiles()
	{
		$post      = $this->input->post->getArray();
		$file      = $this->input->files->get('downloadfile', array(), 'array');
		$totalFile = count($file['name']);
		$model     = $this->getModel('media');

		// Default message
		$msg = JText::_('COM_REDSHOP_UPLOAD_FAIL');

		$productDownloadRoot = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT');

		if (substr(Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT'), -1) != DIRECTORY_SEPARATOR)
		{
			$productDownloadRoot = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT') . '/';
		}

		if ($post['hdn_download_file'] != "")
		{
			$downloadPath = $productDownloadRoot . $post['hdn_download_file_path'];
			$post['name'] = $post['hdn_download_file'];

			if ($post['hdn_download_file_path'] != $downloadPath)
			{
				$post['name'] = RedshopHelperMedia::cleanFileName($post['hdn_download_file']);
				copy($downloadPath, $post['name']);
			}

			if ($model->store($post))
			{
				$msg = JText::_('COM_REDSHOP_UPLOAD_COMPLETE');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_UPLOAD_FAIL');
			}
		}

		for ($i = 0; $i < $totalFile; $i++)
		{
			$errors = $file['error'][$i];

			if (!$errors)
			{
				$filename = RedshopHelperMedia::cleanFileName($file['name'][$i]);
				$fileExt  = JFile::getExt($filename);

				if ($fileExt)
				{
					$src        = $file['tmp_name'][$i];
					$dest       = $productDownloadRoot . $filename;
					$fileUpload = JFile::upload($src, $dest);

					if ($fileUpload != 1)
					{
						$msg = JText::_('COM_REDSHOP_PLEASE_CHECK_DIRECTORY_PERMISSION');
						JFactory::getApplication()->enqueueMessage($msg, 'error');
					}
					else
					{
						$post['name'] = $dest;

						if ($model->store($post))
						{
							$msg = JText::_('COM_REDSHOP_UPLOAD_COMPLETE');
						}
						else
						{
							$msg = JText::_('COM_REDSHOP_UPLOAD_FAIL');
						}
					}
				}
			}
		}

		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&layout=additionalfile&media_id='
			. $post['media_id'] . '&showbuttons=1', $msg
		);
	}

	public function deleteAddtionalFiles()
	{
		$media_id = $this->input->getInt('media_id');
		$fileId   = $this->input->getInt('fileId');
		$model    = $this->getModel('media');

		if ($model->deleteAddtionalFiles($fileId))
		{
			$msg = JText::_('COM_REDSHOP_FILE_DELETED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_FILE_DELETING');
		}

		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&layout=additionalfile&media_id=' . $media_id
			. '&showbuttons=1', $msg
		);
	}

	public function saveorder()
	{
		$section_id    = $this->input->getInt('section_id');
		$section_name  = $this->input->get('section_name');
		$media_section = $this->input->get('media_section');
		$cid           = $this->input->post->get('cid', array(), 'array');
		$order         = $this->input->post->get('order', array(), 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_ORDERING'));
		}

		$model = $this->getModel('media');

		if (!$model->saveorder($cid, $order))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');

		if (isset($section_id))
		{
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $section_id
				. '&showbuttons=1&section_name=' . $section_name
				. '&media_section=' . $media_section, $msg
			);
		}
        elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
            <script language="javascript" type="text/javascript">
                window.parent.document.location = '<?php echo $link; ?>';
            </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
		}
	}

	/**
	 * Select Media as Default
	 *
	 * @return  void
	 */
	public function setDefault()
	{
		$app           = JFactory::getApplication();
		$post          = $this->input->post->getArray();
		$section_id    = $this->input->get('section_id');
		$media_section = $this->input->get('media_section');
		$cid           = $this->input->post->get('cid', array(0), 'array');

		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_MAKE_PRIMARY_MEDIA'));
		}

		$model = $this->getModel('media_detail');

		if (isset($cid[0]) && $cid[0] != 0)
		{
			if (!$model->defaultmedia($cid[0], $section_id, $media_section))
			{
				$msg = $model->getError();
			}
		}

		if ($section_id)
		{
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
        elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$app->enqueueMessage($msg);
			$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
            <script language="javascript" type="text/javascript">
                window.parent.document.location = '<?php echo $link; ?>';
            </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
		}
	}

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
			$filename = RedshopHelperMedia::cleanFileName($file['name']);

			// Image Upload
			$src     = $file['tmp_name'];
			$tempDir = REDSHOP_FRONT_IMAGES_RELPATH . 'tmp/';
			JFolder::create($tempDir, 0755);
			$dest = $tempDir . $filename;
			JFile::upload($src, $dest);

			$fileId    = '';
			$mediaType = 'images';

			if ($new)
			{
				// Create new media
				$model = $this->getModel('media');

				$fileinfo = pathinfo($dest);

				switch ($fileinfo['extension'])
				{
					case 'zip':
					case '7z':
						$mediaType = 'archives';
						break;

					case 'pdf':
						$mediaType = 'pdfs';
						break;

					case 'docx':
					case 'doc':
						$mediaType = 'words';
						break;

					case 'xlsx':
					case 'xls':
						$mediaType = 'excels';
						break;

					case 'pptx':
					case 'ppt':
						$mediaType = 'powerpoints';
						break;

					case 'mp3':
					case 'flac':
						$mediaType = 'sounds';
						break;

					case 'mp4':
					case 'mkv':
					case 'flv':
						$mediaType = 'videos';
						break;

					case 'txt':
						$mediaType = 'texts';
						break;

					case 'jpeg':
					case 'jpg':
					case 'png':
					case 'gif':
						$mediaType = 'images';
						break;

					default:
						$mediaType = '';
						break;
				}

				$fileId = $model->newFile(array
					(
						'media_name'     => $filename,
						'media_section'  => 'tmp',
						'media_type'     => $mediaType,
						'media_mimetype' => $file['type']
					)
				);
			}

			$dimension = getimagesize($dest);

			if ($dimension)
			{
				$dimension = $dimension[0] . ' x ' . $dimension[1];
			}

			echo new JResponseJson(
				array(
					'success' => true,
					'file'    => array(
						'id'        => $fileId,
						'url'       => 'components/com_redshop/assets/images/tmp/' . $filename,
						'name'      => $filename,
						'size'      => RedshopHelperMediaImage::sizeFilter(filesize($dest)),
						'dimension' => $dimension,
						'media'     => 'tmp',
						'mime'      => substr($mediaType, 0, -1),
						'status'    => ''
					)
				)
			);
		}

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
	 * Publish Media
	 *
	 * @return  [type]  [description]
	 */
	public function publish()
	{
		$post = $this->input->post->getArray();

		$section_id    = $this->input->get('section_id');
		$media_section = $this->input->get('media_section');
		$cid           = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_PUBLISHED_SUCCESSFULLY');

		if ($section_id)
		{
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}

        elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
            <script language="javascript" type="text/javascript">
                window.parent.document.location = '<?php echo $link; ?>';
            </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
		}
	}

	/**
	 * Unpublish Media
	 *
	 * @return  [type]  [description]
	 */
	public function unpublish()
	{
		$post = $this->input->post->getArray();

		$section_id    = $this->input->get('section_id');
		$media_section = $this->input->get('media_section');
		$cid           = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_UNPUBLISHED_SUCCESSFULLY');

		if ($section_id)
		{
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
        elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
            <script language="javascript" type="text/javascript">
                window.parent.document.location = '<?php echo $link; ?>';
            </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
		}
	}
}
