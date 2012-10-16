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
 * Account group detail view class.
 *
 * @package		redSHOP
 * @subpackage	Controllers
 * @since		1.2
 */
class RedshopViewAccountgroup_detail extends JViewLegacy
{
    protected $item;
    protected $form;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a JError object.
     */
    public function display($tpl = null)
    {
        // Get the Data.
        $form = $this->get('Form');
        $item = $this->get('Item');

        // Assign the Data.
        $this->form = $form;
        $this->item = $item;

        // Set the toolbar.
        $this->addToolBar();

        // Display the template.
        parent::display($tpl);
    }

    /**
     * Setting the toolbar.
     *
     * @return  void
     */
    protected function addToolBar()
    {
        $isNew  = ($this->item->accountgroup_id < 1);
        $text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_accountgroup48');
        JToolBarHelper::save('accountgroup_detail.save');
        JToolBarHelper::apply('accountgroup_detail.apply');

        if ($isNew)
        {
            JToolBarHelper::cancel('accountgroup_detail.cancel');
        }
        else
        {
            JToolBarHelper::cancel('accountgroup_detail.cancel', 'Close');
        }
    }
}

