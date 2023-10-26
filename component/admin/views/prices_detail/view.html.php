<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

class RedshopViewPrices_detail extends RedshopViewAdmin
{
    /**
     * Do we have to display a sidebar ?
     *
     * @var  boolean
     */
    protected $displaySidebar = false;

    public function display($tpl = null)
    {
        JToolBarHelper::title(Text::_('COM_REDSHOP_PRICE_MANAGEMENT_DETAIL'), 'redshop_vatrates48');

        $this->setLayout('default');

        $this->lists  = array();
        $this->detail = $this->get('data');
        $isNew        = ($this->detail->price_id < 1);
        $text         = $isNew ? Text::_('COM_REDSHOP_NEW') : Text::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(
            Text::_('COM_REDSHOP_PRICE') . ': <small><small>[ ' . $text . ' ]</small></small>',
            'redshop_vatrates48'
        );
        JToolBarHelper::apply();
        JToolBarHelper::save();

        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            JToolBarHelper::cancel('cancel', Text::_('JTOOLBAR_CLOSE'));
        }

        $this->lists['product_id']         = $this->detail->product_id;
        $this->lists['product_name']       = $this->detail->product_name;
        $this->lists['shopper_group_name'] = RedshopHelperShopper_Group::listAll(
            "shopper_group_id",
            0,
            array((int) $this->detail->shopper_group_id)
        );

        $this->request_url = \Joomla\CMS\Uri\Uri::getInstance()->toString();

        parent::display($tpl);
    }
}
