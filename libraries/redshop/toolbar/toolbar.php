<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Utility class for the button bar.
 *
 * @package     Redshop.Library
 * @subpackage  Application
 * @since       1.4
 */
class RedshopToolbar extends JToolbar
{
	/**
	 * Group title
	 *
	 * @var    string
	 */
	protected $groupTitle = null;

	/**
	 * Constructor
	 *
	 * @param   string  $name        The toolbar name.
	 * @param   string  $groupTitle  The group title.
	 *
	 * @since   1.7
	 */
	public function __construct($name = 'toolbar', $groupTitle = '')
	{
		$this->_name = $name;

		$this->groupTitle = $groupTitle;

		// Set base path to find buttons.
		$this->_buttonPath[] = __DIR__ . '/button';
	}

	/**
	 * Render a tool bar.
	 *
	 * @return  string  HTML for the toolbar.
	 *
	 * @since   1.7
	 */
	public function renderGroup()
	{
		$dhtml = RedshopLayoutHelper::render('toolbar.redshopgroup', array('toolbar' => $this));

		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton('Custom', $dhtml);
	}

	/**
	 * Get the group title.
	 *
	 * @return  string
	 *
	 * @since   1.7
	 */
	public function getGroupTitle()
	{
		return $this->groupTitle;
	}
}
