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
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class catalog_detailController extends RedshopCoreController
{
    public $redirectViewName = 'catalog';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');

        $cid = $this->input->post->get('cid', array(0), 'array');

        $post ['catalog_id'] = $cid [0];
        $link                = 'index.php?option=' . $option . '&view=catalog';
        $model               = $this->getModel('catalog_detail');

        if ($model->store($post))
        {

            $msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_CATALOG_DETAIL');
        }

        $this->setRedirect($link, $msg);
    }
}

