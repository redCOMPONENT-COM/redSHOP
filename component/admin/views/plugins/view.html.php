<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.modal');

/**
 * View Categories
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewPlugins extends RedshopViewList
{
    /**
     * @var  string
     */
    public $showType = 'modal';

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->baseLink = "index.php?option=com_plugins&amp;client_id=0&amp;task=plugin.edit&amp;tmpl=component&amp;view=plugin&amp;layout=modal&amp;extension_id=";
    }

    /**
     * Method for render columns
     *
     * @param array  $config Row config.
     * @param int    $index  Row index.
     * @param object $row    Row data.
     *
     * @return  string
     *
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     *
     */
    public function onRenderColumn($config, $index, $row)
    {
        $isCheckedOut = $row->checked_out && JFactory::getUser()->id != $row->checked_out;
        $isInline     = Redshop::getConfig()->getBool('INLINE_EDITING');
        $value        = $row->{$config['dataCol']};

        switch ($config['dataCol']) {
            case 'name':
                if ( ! $isCheckedOut && $isInline && $this->canEdit && $config['inline'] === true) {
                    return JHtml::_('redshopgrid.inline', $config['dataCol'], $value, $value, $row->id, 'number');
                }
            default:
                return parent::onRenderColumn($config, $index, $row);
        }
    }

    public function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * Method for add toolbar.
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function addToolbar()
    {
        // Add common button
        if ($this->canCreate) {
            JToolbarHelper::addNew($this->getInstanceName() . '.add');
        }

        if ($this->canEdit) {
            if ($this->enableDuplicate) {
                JToolbarHelper::save2copy($this->getInstancesName() . '.copy', 'COM_REDSHOP_TOOLBAR_COPY');
            }

            if ( ! empty($this->stateColumns)) {
                JToolbarHelper::publish($this->getInstancesName() . '.publish', 'JTOOLBAR_PUBLISH', true);
                JToolbarHelper::unpublish($this->getInstancesName() . '.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            }

            if ($this->checkIn) {
                JToolbarHelper::checkin($this->getInstancesName() . '.checkin', 'JTOOLBAR_CHECKIN', true);
            }
        }
    }
}
