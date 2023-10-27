<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;


class RedshopViewQuotation extends RedshopView
{
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $uri       = \Joomla\CMS\Uri\Uri::getInstance();

        $Itemid  = $app->input->getInt('Itemid');
        $session = JFactory::getSession();
        $cart    = $session->get('cart');
        $return  = $app->input->getString('return');

        if (!$return) {
            if ($cart['idx'] < 1) {
                $app->redirect(Redshop\IO\Route::_('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid));
            }
        }

		HTMLHelper::script('com_redshop/redshop.validation.min.js', ['relative' => true]);

        $model = $this->getModel('quotation');

        $detail = $model->getData();

        $this->detail      = $detail;
        $this->request_url = $uri->toString();
        JFilterOutput::cleanText($this->request_url);

        parent::display($tpl);
    }
}
