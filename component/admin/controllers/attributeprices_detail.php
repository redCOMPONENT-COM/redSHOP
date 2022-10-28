<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerAttributeprices_detail extends RedshopController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'attributeprices_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    public function save()
    {
        $post = $this->input->post->getArray();

        $section_id = $this->input->get('section_id');
        $section    = $this->input->getString('section');

        $post['product_currency']    = Redshop::getConfig()->get('CURRENCY_CODE');
        $post['cdate']               = time();
        $post['discount_start_date'] = strtotime($post ['discount_start_date']);

        if ($post['discount_end_date']) {
            $post ['discount_end_date'] = strtotime($post['discount_end_date']) + (23 * 59 * 59);
        }

        $cid               = $this->input->post->get('cid', array(0), 'array');
        $post ['price_id'] = $cid [0];

        /** @var RedshopModelAttributeprices_detail $model */
        $model = $this->getModel('attributeprices_detail');

        if ($model->store($post)) {
            $this->setRedirect(
                'index.php?tmpl=component&option=com_redshop&view=attributeprices&section=' . $section . '&section_id=' . $section_id,
                \Joomla\CMS\Language\Text::_('COM_REDSHOP_PRICE_DETAIL_SAVED'),
                ''
            );
        } else {
            $this->setRedirect(
                'index.php?tmpl=component&option=com_redshop&view=attributeprices_detail&task=edit&section=' . $section . '&section_id=' . $section_id . '&cid[]=' . $post ['price_id'],
            );
        }


    }

    public function remove()
    {
        $section_id = $this->input->get('section_id');
        $section    = $this->input->getString('section');
        $cid        = $this->input->post->get('cid', array(), 'array');
        $type       = '';

        if (empty($cid)) {
            $type = 'warning';
            $msg  = JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE');
        } else {
            $msg = JText::_('COM_REDSHOP_ATTRIBUTE_PRICE_DETAIL_DELETED_SUCCESSFULLY');
        }

        /** @var RedshopModelAttributeprices_detail $model */
        $model = $this->getModel('attributeprices_detail');

        if (!$model->delete($cid)) {
            echo "<script> alert('" . /** @scrutinizer ignore-deprecated */ $model->/** @scrutinizer ignore-call */ getError(
                    null,
                    true
                ) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect(
            'index.php?tmpl=component&option=com_redshop&view=attributeprices&section=' . $section . '&section_id=' . $section_id,
            $msg,
            $type
        );
    }

    public function cancel()
    {
        $section_id = $this->input->get('section_id');

        $msg = JText::_('COM_REDSHOP_PRICE_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=com_redshop&view=attributeprices&section_id=' . $section_id, $msg);
    }
}
