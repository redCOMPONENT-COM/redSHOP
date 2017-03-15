<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewQuotation extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $requestUrl;

	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display template function
	 *
	 * @param   object  $tpl  template variable
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$this->form       = $this->get('Form');
		$this->item       = $this->get('Item');
		$this->state      = $this->get('State');
		$this->requestUrl = JUri::getInstance()->toString();

		$this->addToolbar();

		parent::display($tpl);
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
		JToolBarHelper::title(JText::_('COM_REDSHOP_QUOTATION_DETAIL'), 'redshop_quotation48');
		JToolBarHelper::apply('quotation.apply');
		JToolBarHelper::save('quotation.save');
		JToolBarHelper::custom('quotation.send', 'send.png', 'send.png', JText::_('COM_REDSHOP_SEND'), false);
		JToolBarHelper::cancel('quotation.cancel', JText::_('JTOOLBAR_CLOSE'));
	}
}
