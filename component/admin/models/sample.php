<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Voucher
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since
 */
class RedshopModelSample extends RedshopModelForm
{
    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  array  The default data is an empty array.
     *
     * @throws  Exception
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_redshop.edit.sample.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_redshop.sample', $data);

        return $data;
    }

    /**
     * @param $sampleId
     *
     * @return array|mixed
     */
    public function getColorData($sampleId)
    {
        $db = $this->_db;
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_catalog_colour'))
            ->where($db->qn('sample_id') . ' = ' . $db->q($sampleId));

        return $db->setQuery($query)->loadObjectList();
    }
}
