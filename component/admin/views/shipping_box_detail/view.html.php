<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewShipping_box_detail extends RedshopViewAdmin
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
	public $request_url;

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_SHIPPING_BOX'), 'redshop_templates48');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->shipping_box_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_BOXES') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_shipping_box48');
		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		// TEMPLATE MOVE DB TO FILE
		$post = JRequest::get('post');

		if ($isNew && (isset($post['shipping_box_name']) && $post['shipping_box_name'] != ""))
		{
			$detail->template_name = $post['template_name'];
			$detail->template_section = $post['template_section'];
			$template_desc = JRequest::getVar('template_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$detail->template_desc = $template_desc;
			$detail->published = $post['published'];
			$detail->msg = JText::_('PLEASE_CHANGE_FILE_NAME_IT_IS_ALREADY_EXISTS');
		}

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
