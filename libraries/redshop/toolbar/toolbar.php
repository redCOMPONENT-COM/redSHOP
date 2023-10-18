<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

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
    protected $groupTitle;

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
        $this->groupTitle = $groupTitle;

        // Set base path to find buttons.
        $this->_buttonPath[] = __DIR__ . '/button';

        parent::__construct($name);
    }

    /**
     * Render a tool bar.
     *
     * @return  void
     *
     * @since   1.7
     */
    public function renderGroup()
    {
        $html = RedshopLayoutHelper::render('toolbar.redshopgroup', array('toolbar' => $this));
		
		//Replace redshop toolbar with Joomla default one to fix admin order screen - Shardul (18-10-2023)
        $bar = JToolbar::getInstance('toolbar'); //Factory::getContainer()->get(ToolbarFactoryInterface::class)->createToolbar('toolbar');
        $bar->appendButton('Custom', $html);
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