<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model' . DS . 'detail.php';

class stockimage_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'stockimage';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post                     = $this->input->getArray($_POST);
        $option                   = $this->input->get('option');
        $cid                      = $this->input->post->get('cid', array(0), 'array');
        $post ['stock_amount_id'] = $cid [0];

        $model = $this->getModel('stockimage_detail');

        if ($row = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_STOCKIMAGE_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKIMAGE_DETAIL');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=stockimage', $msg);
    }
}

