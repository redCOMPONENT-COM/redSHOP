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
 * Controller Import
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.3
 */
class RedshopControllerImport extends RedshopControllerAdmin
{
	/**
	 * Method for upload csv file.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since  2.0.3
	 */
	public function uploadFile()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$response = array('status' => 1, 'msg' => JText::_('COM_REDSHOP_IMPORT_MESSAGE_UPLOAD_FILE_SUCCESS'));
		$plugin   = $this->input->getCmd('plugin_name', '');
		$file     = $this->input->files->get('csv_file', null);
//array (size=5)
//  'name' => string 'REDSHOP-4921 2 redshop_attribute.csv' (length=36)
//  'type' => string 'text/csv' (length=8)
//  'tmp_name' => string '/tmp/phpUfG85k' (length=14)
//  'error' => int 0
//  'size' => int 1524
		$data     = $this->input->post->getArray();
//array (size=4)
//  'plugin_name' => string 'attribute' (length=9)
//  'separator' => string ',' (length=1)
//  'encoding' => string 'UTF-8' (length=5)
//  '70e181389bc06b36997e27fcc847bcc6' => string '1' (length=1)

		JPluginHelper::importPlugin('redshop_import');
		$result = RedshopHelperUtility::getDispatcher()->trigger('onUploadFile', array($plugin, $file, $data));
//array (size=1)
//  0 =>
//    array (size=3)
//      'folder' => string '1dd7a0df061aaa6b7fafe55493de5f5d' (length=32)
//      'lines' => int 7
//      'files' => int 1

		if (in_array(false, $result, false))
		{
			$response['status'] = 0;
			$response['msg']    = JText::_('COM_REDSHOP_IMPORT_ERROR_UPLOAD_FILE');
		}
		else
		{
			$response['folder'] = $result[0]['folder'];
			$response['lines']  = $result[0]['lines'];
		}

		echo json_encode($response);

		JFactory::getApplication()->close();
	}
}


