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

class newsletter_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'newsletter';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post         = $this->input->getArray($_POST);
        $post["body"] = $this->input->post->getString('body', '');
        $option       = $this->input->get('option');
        $cid          = $this->input->post->get('cid', array(0), 'array');

        $post ['newsletter_id'] = $cid [0];

        $model = $this->getModel('newsletter_detail');

        if ($row = $model->store($post))
        {

            $msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_NEWSLETTER_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=newsletter_detail&task=edit&cid[]=' . $row->newsletter_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=newsletter', $msg);
        }
    }
}
