<?php
/**
 * @package     redSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use \Joomla\CMS;
use \Joomla\CMS\Form\Form;

/**
 * Plugin Redshop_PromotionPromotion
 *
 * @since __DEPLOY_VERSION__
 */
class PlgRedshop_PromotionPromotion extends JPlugin
{
    protected $db;
    protected $app;
    protected $query;
    protected $form;

    /**
     * Constructor
     *
     * @param   object  $subject  The object to observe
     * @param   array   $config   An array that holds the plugin configuration
     *
     * @since    __DEPLOY_VERSION__
     */
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        Form::addFormPath(JPATH_PLUGINS . $this->_type . DS . $this->_name . DS . 'form');

        $this->loadLanguage();
        $this->app = Factory::getApplication();
        $this->db = Factory::getDbo();
        $this->query = $this->db->getQuery(true);
        $this->form = Form::getInstance();
        $this->form->loadFile('promotion', false);
    }

    public function onSavePromotion() {

    }

    public function onLoadPromotion() {

    }

    public function onDeletePromotion() {

    }

    public function onRenderBackEndLayout() {
        $layoutFile = JPATH_PLUGINS . DS . $this->_type . 'DS' . $this->_name . 'layouts';
        return RedshopLayoutHelper::render($layoutFile, [$this]);
    }
}