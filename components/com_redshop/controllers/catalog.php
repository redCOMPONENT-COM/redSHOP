<?php
/**
 * @version    2.5
 * @package    Joomla.Site
 * @subpackage com_redshop
 * @author     redWEB Aps
 * @copyright  com_redshop (C) 2008 - 2012 redCOMPONENT.com
 * @license    GNU/GPL, see LICENSE.php
 *             com_redshop can be downloaded from www.redcomponent.com
 *             com_redshop is free software; you can redistribute it and/or
 *             modify it under the terms of the GNU General Public License 2
 *             as published by the Free Software Foundation.
 *             com_redshop is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License
 *             along with com_redshop; if not, write to the Free Software
 *             Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 **/
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

