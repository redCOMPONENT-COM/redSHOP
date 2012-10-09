<?php
/**
 * @package     redSHOP
 * @subpackage  Backend
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

/**
 * Front Controller of Redshop Backend.
 */
class RedshopController extends RedshopCoreController
{
    /**
     * Typical view method for MVC based architecture
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController  A JController object to support chaining.
     */
    public function display($cachable = false, $urlparams = false)
    {
        // Set default view if not set.
        $this->input->set('view', $this->input->get('view', 'Redshop'));

        // Call parent behavior.
        return parent::display($cachable);
    }
}
