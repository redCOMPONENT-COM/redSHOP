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
 * View Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */

class RedshopViewMedia extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $requestUrl;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	protected $form;

	protected $item;

	protected $state;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   2.0.4
	 */
	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_MEDIA_MANAGEMENT'), 'redshop_media_48');

		$this->form       = $this->get('Form');
		$this->item       = $this->get('Item');
		$this->state      = $this->get('State');
		$this->requestUrl = JUri::getInstance()->toString();

		// Set user state for form field section_id
		$app = JFactory::getApplication();
		$app->setUserState('com_redshop.global.media.section', $this->item->section);

		if ($this->item->type == 'youtube')
		{
			$app->setUserState('com_redshop.global.media.youtube.id', $this->item->name);
		}

		$this->addToolBar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.0.4
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id < 1);

		// Prepare text for title
		$title = JText::_('COM_REDSHOP_MEDIA_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolBarHelper::title($title, 'redshop_media_48');
		JToolBarHelper::apply('media.apply');
		JToolBarHelper::save('media.save');

		if ($isNew)
		{
			JToolBarHelper::cancel('media.cancel');
		}
		else
		{
			JToolBarHelper::cancel('media.cancel', JText::_('JTOOLBAR_CLOSE'));
		}
	}
}
