<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class RedshopControllerNewslettersubscr extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function importdata()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$file = JRequest::getVar('file', 'array', 'files', 'array');

		$success = false;

		$model = $this->getModel('newslettersubscr');

		$filetype = strtolower(JFile::getExt($file['name']));

		$separator = JRequest::getVar('separator', ",");

		if ($filetype == 'csv')
		{
			$src = $file['tmp_name'];

			$dest = JPATH_ADMINISTRATOR . '/components/com_redshop/assets/' . $file['name'];

			JFile::upload($src, $dest);

			$newsletter_id = $post['newsletter_id'];

			$row = 0;

			$handle = fopen($dest, "r");

			while (($data = fgetcsv($handle, 1000, $separator)) !== false)
			{
				if ($data[0] != "" && $data[1] != "")
				{
					if ($row != 0)
					{
						$success = $model->importdata($newsletter_id, $data[0], $data[1]);
					}

					$row++;
				}
			}

			fclose($handle);

			if ($success)
			{
				unlink($dest);
				$msg = JText::_('COM_REDSHOP_DATA_IMPORT_SUCCESS');
				$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_ERROR_DATA_IMPORT');
				$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr&task=import_data', $msg);
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_FILE_EXTENTION_WRONG');
			$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr&task=import_data', $msg);
		}
	}

	public function publish()
	{
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
	}

	public function unpublish()
	{
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
	}
}
