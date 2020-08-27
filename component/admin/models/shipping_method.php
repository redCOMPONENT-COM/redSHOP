<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Zipcode Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelShipping_Method extends RedshopModelForm
{
    /**
     * Get the associated JTable
     *
     * @param string $name   Table name
     * @param string $prefix Table prefix
     * @param array  $config Configuration array
     *
     * @return  JTable
     *
     * @throws  Exception
     */
    public function getTable($name = 'extension', $prefix = 'Table', $options = array())
    {
        return Joomla\CMS\Table\Extension::getInstance('extension');
    }

    /**
     * Method to save the form data.
     *
     * @param array $data The form data.
     *
     * @return  boolean  True on success, False on error.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function save($data)
    {
        if ( ! parent::save($data)) {
            return false;
        }

        $post            = JFactory::getApplication()->input->post->getArray();
        $post['element'] = $post['jform']['element_hidden'];
        $post['plugin']  = $post['jform']['plugin'];

        JPluginHelper::importPlugin('redshop_shipping');
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $dispatcher->trigger('onWriteconfig', array($post));

        return true;
    }
}
