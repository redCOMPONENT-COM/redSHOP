<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       2.0.4
 */

defined('_JEXEC') or die;

/**
 * View Media
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */

class RedshopViewMedium extends RedshopViewForm
{
    /**
     * Method for run before display to initial variables.
     *
     * @param   string &$tpl Template name
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    public function beforeDisplay(&$tpl)
    {
        // Get data from the model
        parent::beforeDisplay($tpl);
    }

    /**
     * Method for get page title.
     *
     * @return  string
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getTitle()
    {
        return JText::_('COM_REDSHOP_MEDIA_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $isNew = ($this->item->id < 1);

        JToolBarHelper::save('medium.save');

        if ($isNew)
        {
            JToolBarHelper::cancel('medium.cancel');
        }
        else
        {
            JToolBarHelper::cancel('medium.cancel', JText::_('JTOOLBAR_CLOSE'));
        }
    }
}
