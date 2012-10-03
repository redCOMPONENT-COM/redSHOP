<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class giftcard_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'giftcard';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post                  = $this->input->getArray($_POST);
        $post["giftcard_desc"] = $this->input->post->getString('giftcard_desc', '');
        $showbuttons           = $this->input->get('showbuttons');
        $option                = $this->input->get('option');

        $model = $this->getModel('giftcard_detail');
        $row   = $model->store($post);

        if ($row)
        {

            $msg = JText::_('COM_REDSHOP_GIFTCARD_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_GIFTCARD');
        }
        if (!$showbuttons)
        {
            if ($apply == 1)
            {
                $this->setRedirect('index.php?option=' . $option . '&view=giftcard_detail&task=edit&cid[]=' . $row->giftcard_id, $msg);
            }
            else
            {
                $this->setRedirect('index.php?option=' . $option . '&view=giftcard', $msg);
            }
        }
        else
        {
            ?>
        <script language="javascript" type="text/javascript">
            window.parent.SqueezeBox.close();
        </script>
        <?php
        }
    }
}

