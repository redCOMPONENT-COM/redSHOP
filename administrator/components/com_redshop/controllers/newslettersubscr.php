<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'default.php';

class RedshopControllerNewslettersubscr extends RedshopCoreControllerDefault
{
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    public function importdata()
    {
        $post      = $this->input->getArray($_POST);
        $option    = $this->input->get('option');
        $file      = $this->input->files->get('file', array(), 'array');
        $separator = $this->input->get('separator', ",");

        $model = $this->getModel('newslettersubscr');

        $filetype = strtolower(JFile::getExt($file['name']));

        if ($filetype == 'csv')
        {
            $src = $file['tmp_name'];

            $dest = JPATH_ADMINISTRATOR . DS . 'components/' . $option . '/assets' . DS . $file['name'];

            JFile::upload($src, $dest);

            $newsletter_id = $post['newsletter_id'];

            $row = 0;

            $handle = fopen($dest, "r");

            while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE)
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
                $this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr', $msg);
            }
            else
            {
                $msg = JText::_('COM_REDSHOP_ERROR_DATA_IMPORT');
                $this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr&task=import_data', $msg);
            }
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_FILE_EXTENTION_WRONG');
            $this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr&task=import_data', $msg);
        }
    }
}
