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

class RedshopControllerNewslettersubscr extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * Method import data.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function importdata()
	{
		$app        = JFactory::getApplication();
		$post       = $this->input->post->getArray();
		$file       = $this->input->files->get('file', array(), 'array');
		$success    = false;
		$msgSuccess = null;
		$msgError   = null;

		/** @var RedshopModelNewslettersubscr $model */
		$model = $this->getModel('newslettersubscr');

		$filetype = strtolower(JFile::getExt($file['name']));

		$separator = $this->input->getString('separator', ",");

		if ($filetype == 'csv')
		{
			$src = $file['tmp_name'];

			$dest = JPATH_ADMINISTRATOR . '/components/com_redshop/assets/' . $file['name'];

			JFile::upload($src, $dest);

			$newsletterId = $post['newsletter_id'];

			$row = 0;

			$handle = fopen($dest, "r");

			while (($data = fgetcsv($handle, 1000, $separator)) !== false)
			{
				if ($data[0] != "" && $data[1] != "")
				{
					if ($row != 0)
					{
						$success = $model->importdata($newsletterId, $data[0], $data[2]);

						if ($success)
						{
							$msgSuccess .= '<p>' . JText::sprintf('COM_REDSHOP_DATA_IMPORT_SUCCESS_AT_ROW', ($row + 1)) . '</p>';
						}
						else
						{
							$msgError .= '<p>' . JText::sprintf('COM_REDSHOP_ERROR_DATA_IMPORT_AT_ROW', ($row + 1)) . '</p>';
						}
					}

					$row++;
				}
			}

			fclose($handle);

			$app->enqueueMessage($msgSuccess);
			$app->enqueueMessage($msgError, 'error');
			JFile::delete($dest);

			if ($msgError == null)
			{
				$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr');
			}
			else
			{
				$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr&task=import_data');
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_FILE_EXTENTION_WRONG');
			$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr&task=import_data', $msg, 'warning');
		}
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		/** @var RedshopModelNewslettersubscr_detail $model */
		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . /** @scrutinizer ignore-deprecated */ $model->getError() . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		/** @var RedshopModelNewslettersubscr_detail $model */
		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . /** @scrutinizer ignore-deprecated */ $model->getError() . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
	}
}
