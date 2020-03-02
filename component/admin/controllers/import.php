<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
		$data     = $this->input->post->getArray();


		JPluginHelper::importPlugin('redshop_import');
		$result = RedshopHelperUtility::getDispatcher()->trigger('onUploadFile', array($plugin, $file, $data));

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


