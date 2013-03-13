<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.view');

class searchViewsearch extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		global $mainframe;

		//$decoded = json_decode($_GET['json']);

		$doc = & JFactory::getDocument();

		$doc->addStyleSheet('components/com_redshop/assets/css/search.css');

		$doc->addScript('components/com_redshop/assets/js/search.js');

		//	$aid = $decoded->aid;

		$search_detail =& $this->get('data');


		$this->assignRef('detail', $search_detail);

		parent::display($tpl);
	}
}