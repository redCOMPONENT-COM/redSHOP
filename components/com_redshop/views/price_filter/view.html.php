<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

class price_filterViewprice_filter extends JViewLegacy
{
    public function display($tpl = null)
    {
        $prdlist = $this->get('Data');
        $this->assignRef('prdlist', $prdlist);

        parent::display($tpl);
    }
}

