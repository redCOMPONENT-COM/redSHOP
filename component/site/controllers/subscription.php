<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class SubscriptionController extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
	}

	public function download()
	{
			$user    = &JFactory::getUser();
			$model   = $this->getModel('subscription');
			$user_id = $user->id;

			if ($user_id > 0)
			{
				$flag       = false;
				$product_id = JRequest::getInt('id');
				$media_id   = JRequest::getInt('media_id');

				if ($media_id > 0)
				{
					$namepro = $model->getNameProductMedia($media_id);
				}
				else
				{
					$namepro = $model->getNameProduct($product_id);
				}

				$nameprodl = substr(basename($namepro), 11);
				$baseURL   = JURI::root();
				$tmp_type  = strtolower(JFile::getExt($namepro));

				if ($namepro <> "")
				{
					switch ($tmp_type)
					{
						case "pdf":
									$ctype = "application/pdf";
									break;
						case "psd":
									$ctype = "application/psd";
									break;
						case "exe":
									$ctype = "application/octet-stream";
									break;
						case "zip":
									$ctype = "application/x-zip";
									break;
						case "doc":
									$ctype = "application/msword";
									break;
						case "xls":
									$ctype = "application/vnd.ms-excel";
									break;
						case "ppt":
									$ctype = "application/vnd.ms-powerpoint";
									break;
						case "gif":
									$ctype = "image/gif";
									break;
						case "png":
									$ctype = "image/png";
									break;
						case "jpg":
									$ctype = "image/jpg";
									break;
						default:
									$ctype = "application/force-download";
					}

					ob_clean();
					header("Pragma: public");
					header('Expires: 0');
					header("Content-Type: $ctype", false);
					header('Content-Length: ' . filesize($namepro));
					header('Content-Disposition: attachment; filename=' . $nameprodl);

					// Red file using chunksize
					$this->readfile_chunked($namepro);
					exit;
				}
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_LOGIN_DESCRIPTION');
				$this->setRedirect('index.php', $msg);
			}
	}

	public function readfile_chunked($filename,$retbytes=true)
	{
		$chunksize = 10 * (1024 * 1024);
		$buffer = '';
		$cnt = 0;
		$handle = fopen($filename, 'rb');

		if ($handle === false)
		{
			return false;
		}

		while (!feof($handle))
		{
			$buffer = fread($handle, $chunksize);
			echo $buffer;
			ob_flush();
			flush();

			if ($retbytes)
			{
				$cnt += strlen($buffer);
			}
		}

		$status = fclose($handle);

		if ($retbytes && $status)
		{
			return $cnt;
		}

		return $status;
	}
}?>