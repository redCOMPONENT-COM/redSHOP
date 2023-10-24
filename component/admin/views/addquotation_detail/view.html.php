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
use Joomla\CMS\HTML\HTMLHelper;

class RedshopViewAddquotation_detail extends RedshopViewAdmin
{
    /**
     * The request url.
     *
     * @var  string
     */
    public $request_url;

    /**
     * Do we have to display a sidebar ?
     *
     * @var  boolean
     */
    protected $displaySidebar = false;

    public function display($tpl = null)
    {
        $document = JFactory::getDocument();

        $document->setTitle(Text::_('COM_REDSHOP_QUOTATION_MANAGEMENT'));

        HTMLHelper::script('com_redshop/json.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.validation.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.order.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.admin.common.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/ajaxupload.min.js', ['relative' => true]);

        $session = JFactory::getSession();
        $uri     = JUri::getInstance();

        $lists   = array();
        $model   = $this->getModel();
        $user_id = JFactory::getApplication()->input->getInt('user_id', 0);

        if ($user_id != 0) {
            $billing = RedshopHelperOrder::getBillingAddress($user_id);
        } else {
            $billing = $model->setBilling();
        }

        $detail          = new stdClass;
        $detail->user_id = $user_id;

        $session->set('offlineuser_id', $user_id);

        JToolBarHelper::title(
            Text::_('COM_REDSHOP_QUOTATION_MANAGEMENT') . ': <small><small>[ '
            . Text::_('COM_REDSHOP_NEW') . ' ]</small></small>',
            'redshop_order48'
        );

        JToolBarHelper::apply();
        JToolBarHelper::save();
        JToolBarHelper::custom('send', 'send.png', 'send.png', Text::_('COM_REDSHOP_SEND'), false);
        JToolBarHelper::cancel();

        $countryarray          = RedshopHelperWorld::getCountryList((array) $billing);
        $billing->country_code = $countryarray['country_code'];
        $lists['country_code'] = $countryarray['country_dropdown'];

        $statearray                    = RedshopHelperWorld::getStateList((array) $billing);
        $lists['state_code']           = $statearray['state_dropdown'];
        $lists['quotation_extrafield'] = RedshopHelperExtrafields::listAllField(
            RedshopHelperExtrafields::SECTION_QUOTATION,
            $billing->users_info_id
        );

        $this->lists       = $lists;
        $this->detail      = $detail;
        $this->billing     = $billing;
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}