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
 * View Mass Discount
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewMass_Discount extends RedshopViewForm
{
	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_MASS_DISCOUNT_MANAGEMENT') . ' <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
	protected function addToolbar()
	{;
		$isNew = ($this->item->{$this->getPrimaryKey()} < 1);

		if ($this->canEdit)
		{
			JToolbarHelper::apply($this->getInstanceName() . '.apply');
		}

		if ($this->canEdit || $this->canCreate)
		{
			JToolbarHelper::save($this->getInstanceName() . '.save');
			JToolbarHelper::save2new($this->getInstanceName() . '.save2new');
		}

		if ($isNew)
		{
			JToolbarHelper::cancel($this->getInstanceName() . '.cancel');
		}
		else
		{
			JToolbarHelper::cancel($this->getInstanceName() . '.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
