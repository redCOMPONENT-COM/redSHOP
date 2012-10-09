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

class RedshopControllerAttributeprices_detail extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');

        // Set the redirect view.
        $section_id             = $this->input->get('section_id');
        $section                = $this->input->get('section');
        $this->redirectViewName = 'attributeprices&section=' . $section . '&section_id=' . $section_id;
    }

    public function save($apply = 0)
    {
        $post       = $this->input->getArray($_POST);
        $option     = $this->input->get('option');
        $section_id = $this->input->get('section_id');
        $section    = $this->input->get('section');

        $post['product_currency']    = CURRENCY_CODE;
        $post['cdate']               = time();
        $post['discount_start_date'] = strtotime($post ['discount_start_date']);

        if ($post['discount_end_date'])
        {
            $post ['discount_end_date'] = strtotime($post['discount_end_date']) + (23 * 59 * 59);
        }

        $cid               = $this->input->post->get('cid', array(0), 'array');
        $post ['price_id'] = $cid [0];

        $model = $this->getModel('attributeprices_detail');

        if ($model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_PRICE_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_PRICE_DETAIL');
        }

        $this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=attributeprices&section=' . $section . '&section_id=' . $section_id, $msg);
    }
}
