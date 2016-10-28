<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
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

	public function saveAdditionalFiles()
	{
		$post = JRequest::get('POST');
		$file = JRequest::getVar('downloadfile', 'array', 'files', 'array');
		$totalFile = count($file['name']);
		$model = $this->getModel('media');

		$product_download_root = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT');

		if (substr(Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT'), -1) != DIRECTORY_SEPARATOR)
		{
			$product_download_root = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT') . '/';
		}

		if ($post['hdn_download_file'] != "")
		{
			$download_path = $product_download_root . $post['hdn_download_file_path'];
			$post['name'] = $post['hdn_download_file'];

			if ($post['hdn_download_file_path'] != $download_path)
			{
				$post['name'] = RedShopHelperImages::cleanFileName($post['hdn_download_file']);
				$down_src = $download_path;
				$down_dest = $post['name'];
				copy($down_src, $down_dest);
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
				$filename = RedShopHelperImages::cleanFileName($file['name'][$i]);
				$fileExt = JFile::getExt($filename);

				if ($fileExt)
				{
					$src = $file['tmp_name'][$i];
					$dest = $product_download_root . $filename;
					$file_upload = JFile::upload($src, $dest);

					if ($file_upload != 1)
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
		$media_id = JRequest::getInt('media_id');
		$fileId = JRequest::getInt('fileId');
		$model = $this->getModel('media');

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

		$section_id = JRequest::getVar('section_id');
		$section_name = JRequest::getVar('section_name');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');

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
			$link = 'index.php?option=com_redshop&view=manufacturer';    ?>
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
		$app = JFactory::getApplication();
		$post = JRequest::get('post');
		$section_id = JRequest::getVar('section_id');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

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
			$link = 'index.php?option=com_redshop&view=manufacturer';    ?>
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

		if (!empty($file))
		{
			$filename = RedShopHelperImages::cleanFileName($file['name'], time());

			// Image Upload
			$src = $file['tmp_name'];
			$tempDir = REDSHOP_FRONT_IMAGES_RELPATH . 'tmp/';
			JFolder::create($tempDir, 0755);
			$dest = $tempDir . $filename;
			JFile::upload($src, $dest);
		}

		echo new JResponseJson(
			array(
			'success' => true,
			'file' => $filename
			)
		);

		die;
	}
}
