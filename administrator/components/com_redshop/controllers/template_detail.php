<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'template.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class template_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'template';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post                  = $this->input->getArray($_POST);
        $showbuttons           = $this->input->get('showbuttons');
        $template_desc         = $this->input->post->getString('template_desc', '');
        $post["template_desc"] = $template_desc;
        $option                = $this->input->get('option');

        $model = $this->getModel('template_detail');
        $row   = $model->store($post);
        if ($row)
        {

            $msg = JText::_('COM_REDSHOP_TEMPLATE_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_TEMPLATE');
        }
        if (!$showbuttons)
        {
            if ($apply == 1)
            {
                $this->setRedirect('index.php?option=' . $option . '&view=template_detail&task=edit&cid[]=' . $row->template_id, $msg);
            }
            else
            {
                $this->setRedirect('index.php?option=' . $option . '&view=template', $msg);
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

    public function cancel()
    {
        $option = $this->input->get('option');
        $model  = $this->getModel('template_detail');
        $model->checkin();

        $this->setRedirect('index.php?option=' . $option . '&view=template');
    }
}

