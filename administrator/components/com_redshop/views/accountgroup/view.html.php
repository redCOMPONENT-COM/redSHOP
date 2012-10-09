<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Account group view class.
 *
 * @package		redSHOP
 * @subpackage	Controllers
 * @since		1.2
 */
class RedshopViewAccountgroup extends JViewLegacy
{
    protected $detail;
    protected $pagination;
    protected $state;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a JError object.
     */
    public function display($tpl = null)
    {
        $this->detail = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        $this->addToolBar();

        parent::display($tpl);
    }

    /**
     * Setting the toolbar.
     *
     * @return  void
     */
    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'), 'redshop_accountgroup48');
        JToolbarHelper::addNew('accountgroup_detail.add');
        JToolbarHelper::EditList('accountgroup_detail.edit');
        JToolbarHelper::deleteList('', 'accountgroup.delete');
        JToolBarHelper::publishList('accountgroup.publish');
        JToolBarHelper::unpublishList('accountgroup.unpublish');
    }
}
