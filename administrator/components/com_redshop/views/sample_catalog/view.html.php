<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class sample_catalogVIEWsample_catalog extends JViewLegacy
{
    public function display($tpl = null)
    {
        $uri = JFactory::getURI();

        $this->setLayout('default');

        $detail = $this->get('data');

        $model = $this->getModel('sample_catalog');

        $sample = $model->getsample($detail->colour_id);

        $this->assignRef('detail', $detail);
        $this->assignRef('sample', $sample);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
