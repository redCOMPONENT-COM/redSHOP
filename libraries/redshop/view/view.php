<?php
/**
 * @package     Redshop
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Application\CMSApplication;

defined('_JEXEC') or die;

//jimport('joomla.application.component.viewlegacy');

/**
 * Base view.
 *
 * @package     Redshob.Libraries
 * @subpackage  View
 * @since       1.5
 */
class RedshopView extends HtmlView
{
    /**
     * @var  \JInput
     *
     * @since 3.0.1
     */
    protected $input;

    /**
     * @var  CMSApplication
     *
     * @since 3.0.1
     */
    protected $app;

    public function __construct($config = array())
    {
        $this->app   = JFactory::getApplication();
        $this->input = $this->app->input;

        parent::__construct($config);
    }
}
