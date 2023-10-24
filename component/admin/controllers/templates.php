<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Templates controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       2.0.7
 */
class RedshopControllerTemplates extends RedshopControllerAdmin
{
    /**
     * Method to clone an existing supplier.
     *
     * @return  void
     *
     * @since   2.0.0.6
     */
    public function duplicate()
    {
        // Check for request forgeries
        JSession::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $pks = $this->input->post->get('cid', array(), 'array');
        $pks = \Joomla\Utilities\ArrayHelper::toInteger($pks);

        try {
            $model = $this->getModel();
            $model->duplicate($pks);
            $this->setMessage(JText::plural('COM_REDSHOP_N_SUPPLIERS_DUPLICATED', count($pks)));
        }
        catch (Exception $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
        }

        $this->setRedirect('index.php?option=com_redshop&view=templates');
    }

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   2.1.0
     */
    public function getModel($name = 'Template', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}