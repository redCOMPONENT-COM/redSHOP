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

class country_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'country';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post                 = $this->input->getArray($_POST);
        $post["country_name"] = $this->input->post->getString('country_name', '');
        $cid                  = $this->input->post->get('cid', array(0), 'array');
        $post ['country_id']  = $cid [0];
        $model                = $this->getModel('country_detail');
        $row                  = $model->store($post);

        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_COUNTRY_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=com_redshop&view=country_detail&task=edit&cid[]=' . $row->country_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=com_redshop&view=country', $msg);
        }
    }
}
