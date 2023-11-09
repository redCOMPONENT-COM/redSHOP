<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Newsletter view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewNewsletter extends RedshopView
{
    /**
     * @var  JUser
     */
    public $user;

    /**
     * @var string
     */
    public $userdata;

    /**
     * @var \Joomla\Registry\Registry
     */
    public $params;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed         A string if successful, otherwise a JError object.
     * @throws  Exception
     */
    public function display($tpl = null)
    {
        /** @var JApplicationSite $app */
        $app = JFactory::getApplication();

        $pathway = $app->getPathway();
        $pathway->addItem(Text::_('COM_REDSHOP_NEWSLETTER_SUBSCRIPTION'), '');

        $layout = $app->input->getCmd('layout');

        $this->user     = Factory::getApplication()->getIdentity();
        $this->userdata = $app->input->getString('userdata');
        $this->params   = $app->getParams('com_redshop');

        if ($layout == 'thankyou') {
            $this->setLayout('thankyou');
        }

        parent::display($tpl);
    }
}
