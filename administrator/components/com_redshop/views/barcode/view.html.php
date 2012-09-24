<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class barcodeViewbarcode extends JView
{
    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('COM_REDSHOP_BARCODE'), 'redshop_order48');

        $order_id = JRequest::getInt('order_id', 0);

        $model           = $this->getModel();
        $this->logData   = $model->getLog($order_id);
        $this->logDetail = $model->getLogdetail($order_id);

        parent::display($tpl);
    }
}
