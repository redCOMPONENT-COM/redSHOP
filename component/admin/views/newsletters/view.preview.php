<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * View newletters
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewNewsletters extends RedshopViewAdmin
{
    public function display($tpl = null)
    {
        $app     = JFactory::getApplication();
        $context = 'newsletter_preview';

        $cid = $app->input->post->get('cid', array(0), 'array');

        $selected_product = $app->input->get('product', '');
        $n                = $cid[0];

        /** @var RedshopModelNewsletters $model */
        $model       = $this->getModel();
        $subscribers = $model->listallsubscribers($n);

        $document = JFactory::getDocument();
        $document->setTitle(Text::_('COM_REDSHOP_NEWSLETTER'));

        JToolBarHelper::title(Text::_('COM_REDSHOP_NEWSLETTER_MANAGEMENT'), 'redshop_newsletter48');

        JToolBarHelper::custom('sendNewsletter', 'send.png', 'send.png', 'Send Newsletter');
        JToolBarHelper::cancel('close', Text::_('JTOOLBAR_CLOSE'));

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'newsletter_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $newsletters        = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $oprand = $app->input->getCmd('oprand', 'select');

        $optionoprand    = array();
        $optionoprand[]  = JHtml::_('select.option', 'select', Text::_('COM_REDSHOP_SELECT'));
        $optionoprand[]  = JHtml::_('select.option', 'more', Text::_('COM_REDSHOP_GTOREQUEL'));
        $optionoprand[]  = JHtml::_('select.option', 'less', Text::_('COM_REDSHOP_LTOREQUEL'));
        $optionoprand[]  = JHtml::_('select.option', 'equally', Text::_('COM_REDSHOP_EQUAL_SIGN'));
        $lists['oprand'] = JHtml::_(
            'select.genericlist',
            $optionoprand,
            'oprand',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $oprand
        );

        $country_option   = array();
        $country_option[] = JHtml::_('select.option', '', Text::_('COM_REDSHOP_SELECT_COUNTRY'));

        $country = $model->getCountry();

        $country_value = $app->input->get('country', '');

        $lists['country'] = JHtml::_(
            'select.genericlist',
            $country,
            'country[]',
            'class="inputbox" multiple="multiple" size="4" ',
            'value',
            'text',
            $country_value
        );

        $selectedCategory    = $app->input->get('product_category', array());
        $categories          = RedshopHelperCategory::listAll(
            "product_category[]",
            0,
            $selectedCategory,
            10,
            true,
            true
        );
        $lists['categories'] = $categories;

        $product_data = $model->getProduct();

        $lists['product'] = JHtml::_(
            'select.genericlist',
            $product_data,
            'product[]',
            'class="inputbox" multiple="multiple" size="8" ',
            'value',
            'text',
            $selected_product
        );

        $shopper_option   = array();
        $shopper_option[] = JHtml::_('select.option', '', Text::_('COM_REDSHOP_SELECT'));
        $shoppergroup     = $app->input->get('shoppergroups', '');
        $shoppergroups    = $model->getShopperGroup();

        $lists['shoppergroups'] = JHtml::_(
            'select.genericlist',
            $shoppergroups,
            'shoppergroups[]',
            'class="inputbox" multiple="multiple" size="8" ',
            'value',
            'text',
            $shoppergroup
        );

        $this->subscribers = $subscribers;
        $this->lists       = $lists;
        $this->newsletters = $newsletters;
        $this->pagination  = $pagination;
        $this->request_url = JUri::getInstance()->toString();

        $this->setLayout('preview');

        parent::display($tpl);
    }
}
