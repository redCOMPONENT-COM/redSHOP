<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class RedshopViewOrders extends RedshopView
{
    /**
     * The pagination object.
     *
     * @var  JPagination
     */
    public $pagination;
    
    public function display($tpl = null)
    {
        $app  = Factory::getApplication();
        $user = Factory::getApplication()->getIdentity();

        // Preform security checks
        if ($user->id == 0) {
            $app->redirect(Redshop\IO\Route::_('index.php?option=com_redshop&view=login&Itemid=' . Factory::getApplication()->input->getInt('Itemid')));
            $app->close();
        }

        $layout = $app->input->getCmd('layout', 'default');
        $this->setLayout($layout);

        $params = $app->getParams('com_redshop');
        RedshopHelperBreadcrumb::generate();

        // Request variables
        $limit      = $app->getUserStateFromRequest('com_redshop' . 'limit', 'limit', 10, 'int');
        $limitstart = $app->input->getInt('limitstart', 0, '', 'int');

        $detail           = $this->get('data');
        $this->pagination = $this->get('Pagination');

        $this->detail = $detail;
        $this->params = $params;
        parent::display($tpl);
    }
}