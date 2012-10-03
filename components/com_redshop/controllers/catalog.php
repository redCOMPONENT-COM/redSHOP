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

/**
 * catalogController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class catalogController extends RedshopCoreController
{
    /*
      * Method to send catalog
      */
    public function catalog_send()
    {
        $option               = $this->input->get('option');
        $item_id              = $this->input->get('Itemid');
        $post                 = $this->input->getArray($_POST);
        $model                = $this->getModel('catalog');
        $post["registerDate"] = time();
        $post["email"]        = $post["email_address"];
        $post["name"]         = $post["name_2"];
        if ($row = $model->catalogStore($post))
        {
            $redshopMail = new redshopMail();
            $redshopMail->sendCatalogRequest($row);
            $msg = JText::_('COM_REDSHOP_CATALOG_SEND_SUCCSEEFULLY');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_CATALOG_SEND_SUCCSEEFULLY');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=catalog&Itemid=' . $item_id, $msg);
    }

    /*
      * Method to send catalog sample
      */
    public function catalogsample_send()
    {
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');
        $post    = $this->input->getArray($_POST);
        $model   = $this->getModel('catalog');

        if (isset($post["sample_code"]))
        {
            $colour_id          = implode(",", $post["sample_code"]);
            $post ['colour_id'] = $colour_id;
        }
        $post["registerdate"] = time();
        $post["email"]        = $post["email_address"];
        $post["name"]         = $post["name_2"];
        if ($row = $model->catalogSampleStore($post))
        {
            $extra_field = new extra_field();
            $extra_field->extra_field_save($post, 9, $row->request_id);
            $msg = JText::_('COM_REDSHOP_SAMPLE_SEND_SUCCSEEFULLY');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAMPLE_SEND_SUCCSEEFULLY');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=catalog&layout=sample&Itemid=' . $item_id, $msg);
    }
}

