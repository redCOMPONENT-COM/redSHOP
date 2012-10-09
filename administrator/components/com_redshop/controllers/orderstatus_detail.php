<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class RedshopControllerOrderstatus_detail extends RedshopCoreController
{
    public $redirectViewName = 'orderstatus';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $redhelper = new redhelper();

        $post ['order_status_id'] = $cid[0];

        $model = $this->getModel('orderstatus_detail');

        if ($model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_ORDERSTATUS_DETAIL_SAVED');
        }
        elseif (JFactory::getACL())
        {

            $msg = JText::_('COM_REDSHOP_ORDERSTATUS_CODE_IS_ALLREADY');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_ORDERSTATUS_DETAIL');
        }
        $link = 'index.php?option=' . $option . '&view=orderstatus';
        $link = $redhelper->sslLink($link, 0);
        $this->setRedirect($link, $msg);
    }
}
