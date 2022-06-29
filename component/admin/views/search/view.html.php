<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

class RedshopViewSearch extends RedshopViewAdmin
{
    /**
     * @param   string  $tpl  Layout
     *
     * @return  void
     */
    public function display($tpl = null)
    {
		HTMLHelper::stylesheet('com_redshop/redshop.search.min.css', ['relative' => true]);
		HTMLHelper::script('com_redshop/redshop.search.min.js', ['relative' => true]);

        $this->detail = $this->get('data');

        parent::display($tpl);
    }
}
