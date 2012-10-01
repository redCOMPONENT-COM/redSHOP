<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class mediaController extends RedshopCoreController
{
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    public function saveAdditionalFiles()
    {
        $post = $this->input->getArray($_POST);
        $file = $this->input->files->get('downloadfile', array(), 'array');

        $totalFile = count($file['name']);
        $model     = $this->getModel();

        // If file selected from download folder...
        $product_download_root = PRODUCT_DOWNLOAD_ROOT;
        if (substr(PRODUCT_DOWNLOAD_ROOT, -1) != DS)
        {
            $product_download_root = PRODUCT_DOWNLOAD_ROOT . DS;
        }

        if ($post['hdn_download_file'] != "")
        {
            $download_path = $product_download_root . $post['hdn_download_file_path'];
            $post['name']  = $post['hdn_download_file'];

            if ($post['hdn_download_file_path'] != $download_path)
            {
                $filename     = time() . '_' . $post['hdn_download_file']; //Make the filename unique
                $post['name'] = $product_download_root . str_replace(" ", "_", $filename);
                $down_src     = $download_path;
                $down_dest    = $post['name'];
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
                $filename = time() . "_" . $file['name'][$i];
                $fileExt  = strtolower(JFile::getExt($filename));
                if ($fileExt)
                {
                    $src         = $file['tmp_name'][$i];
                    $dest        = $product_download_root . str_replace(" ", "_", $filename);
                    $file_upload = JFile::upload($src, $dest);
                    if ($file_upload != 1)
                    {
                        $msg = JText::_('COM_REDSHOP_PLEASE_CHECK_DIRECTORY_PERMISSION');
                        JError::raiseWarning(403, $msg);
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
        $this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&layout=additionalfile&media_id=' . $post['media_id'] . '&showbuttons=1', $msg);
    }

    public function deleteAddtionalFiles()
    {
        $media_id = $this->input->getInt('media_id', 0);
        $fileId   = $this->input->getInt('fileId', 0);

        $model = $this->getModel();
        if ($model->deleteAddtionalFiles($fileId))
        {
            $msg = JText::_('COM_REDSHOP_FILE_DELETED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_FILE_DELETING');
        }
        $this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&layout=additionalfile&media_id=' . $media_id . '&showbuttons=1', $msg);
    }

    //ordering
    public function saveorder()
    {
        $option        = $this->input->get('option');
        $section_id    = $this->input->get('section_id');
        $section_name  = $this->input->get('section_name');
        $media_section = $this->input->get('media_section');
        $cid           = $this->input->post->get('cid', array(), 'array');
        $order         = $this->input->post->get('order', array(), 'array');

        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_ORDERING'), 500);
        }

        $model = $this->getModel('media');

        if (!$model->saveorder($cid, $order))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');

        if (isset($section_id))
        {
            $this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id=' . $section_id . '&showbuttons=1&section_name=' . $section_name . '&media_section=' . $media_section, $msg);
        }
        else if (isset($post['set']) && $post['media_section'] == 'manufacturer')
        {
            $link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
        <script language="javascript" type="text/javascript">
            window.parent.document.location = '<?php echo $link; ?>';
        </script><?php
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
        }
    }
}
