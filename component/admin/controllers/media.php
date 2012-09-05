<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.controller' );

jimport('joomla.filesystem.file');

class mediaController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}
	function display() 
	{
		parent::display();
	}
	
	function saveAdditionalFiles()
	{
		$post = JRequest::get('POST');
		$file = JRequest::getVar('downloadfile', 'array' , 'files', 'array');
		$totalFile = count($file['name']);
		$model = $this->getModel();
		// if file selected from download folder...

		$product_download_root = PRODUCT_DOWNLOAD_ROOT;
		if (substr(PRODUCT_DOWNLOAD_ROOT,-1) != DS)
			$product_download_root = PRODUCT_DOWNLOAD_ROOT.DS;

		if($post['hdn_download_file'] != "")
		{
			$download_path = $product_download_root.$post['hdn_download_file_path'];
			$post['name'] = $post['hdn_download_file'];
			$filenewtype = strtolower(JFile::getExt($post['hdn_download_file']));
			if($post['hdn_download_file_path'] != $download_path)
			{
				$filename = time().'_'. $post['hdn_download_file']; //Make the filename unique
				$post['name'] =  $product_download_root.str_replace(" ","_",$filename);
				$down_src = $download_path;
				$down_dest = $post['name'];
				copy($down_src,$down_dest);
			}
			if($model->store($post))
			{
				$msg = JText::_ ( 'UPLOAD_COMPLETE' );
			}else{
				$msg = JText::_ ( 'UPLOAD_FAIL' );
			}
		}
		for($i=0;$i<$totalFile;$i++)
		{
			$errors =  $file['error'][$i];
			if(!$errors)
			{
				$filename =  time()."_".$file['name'][$i];
				$fileExt = strtolower(JFile::getExt($filename));
				if ($fileExt)
				{
					$src 	  =  $file['tmp_name'][$i];
					$dest = $product_download_root.str_replace(" ","_",$filename);
					$file_upload = JFile::upload($src, $dest);
					if($file_upload != 1)
					{
						$msg = JText::_ ( 'PLEASE_CHECK_DIRECTORY_PERMISSION' );
						JError::raiseWarning(403, $msg );
					}
					else
					{
						$post['name'] = $dest;
						if($model->store($post))
						{
							$msg = JText::_ ( 'UPLOAD_COMPLETE' );
						}else{
							$msg = JText::_ ( 'UPLOAD_FAIL' );
						}
					}
				}
			}
		}
		$this->setRedirect ( 'index3.php?option=com_redshop&view=media&layout=additionalfile&media_id='.$post['media_id'].'&showbuttons=1', $msg );
	}

	function deleteAddtionalFiles()
	{
		$media_id = JRequest::getInt('media_id');
		$fileId = JRequest::getInt('fileId');
		$model = $this->getModel();
		if ($model->deleteAddtionalFiles($fileId)){
			$msg = JText::_ ( 'FILE_DELETED' );
		}else{
			$msg = JText::_ ( 'ERROR_FILE_DELETING' );
		}
		$this->setRedirect ( 'index3.php?option=com_redshop&view=media&layout=additionalfile&media_id='.$media_id.'&showbuttons=1', $msg );
	}
}?>