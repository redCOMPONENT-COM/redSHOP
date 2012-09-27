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
 * product_ratingController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class product_ratingController extends RedshopCoreController
{
    /**
     * save function
     *
     * @access public
     * @return void
     */
    public function save()
    {
        $option      = $this->input->get('option');
        $item_id     = $this->input->get('Itemid');
        $post        = $this->input->getArray($_POST);
        $product_id  = $this->input->getInt('product_id', null);
        $category_id = $this->input->getInt('category_id', null);
        $model       = $this->getModel('product_rating');
        $rate        = $this->input->get('rate');

        if ($model->sendMailForReview($post))
        {
            $msg = JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY');
        }

        if ($rate == 1)
        {

            $link = 'index.php?option=' . $option . '&view=product&pid=' . $product_id . '&cid=' . $category_id . '&Itemid=' . $item_id;
            $this->setRedirect($link, $msg);
        }
        else
        {
            echo $msg;?>
        <span id="closewindow"><input type="button" value="Close Window"
                                      onclick="window.parent.redBOX.close();"/></span>
        <script>
            setTimeout("window.parent.redBOX.close();", 5000);
        </script>
        <?php
            exit;
        }
    }
}

