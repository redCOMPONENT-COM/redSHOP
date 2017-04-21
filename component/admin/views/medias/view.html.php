<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Countries
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */

class RedshopViewMedias extends RedshopViewList
{
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_MEDIA_MANAGEMENT'), 'redshop_media_48');
		JToolBarHelper::addNew('media.add');
		JToolBarHelper::deleteList('', 'medias.delete');
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
	}

    /**
     * Method for prepare table.
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function prepareTable()
    {
        parent::prepareTable();

        $this->columns[] = array(
            // This column is sortable?
            'sortable'  => false,
            // Text for column
            'text'      => JText::_('COM_REDSHOP_PRODUCTS'),
            // Name of property for get data.
            'dataCol'   => 'product',
            // Width of column
            'width'     => 'auto',
            // Enable edit inline?
            'inline'    => false,
            // Display with edit link or not?
            'edit_link' => false,
            // Type of column
            'type'      => 'text',
        );
    }
}
