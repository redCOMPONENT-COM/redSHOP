<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class wrapperViewwrapper extends JView
{
	/**
	 * The current user.
	 *
	 * @var  JUser
	 */
	public $user;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$product_id = JRequest::getVar('product_id');

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_WRAPPER'));

		$total      = $this->get('Total');
		$data       = $this->get('Data');
		$pagination = $this->get('Pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_WRAPPER'), 'redshop_wrapper48');
		JToolBarHelper::addNewX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->data = $data;
		$this->product_id = $product_id;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
