<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Sample
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableSample extends RedshopTable
{
    /**
     * @var integer
     */
    public $id = null;

    /**
     * @var string
     */
    public $name = null;

    /**
     * @var integer
     */
    public $published = null;

    /**
     * The table name without prefix.
     *
     * @var string
     */
    protected $_tableName = 'redshop_catalog_sample';
    /**
     * The table key column
     *
     * @var string
     */
    protected $_tableKey = 'id';

    public function doStore($updateNulls = false)
    {
        $doStore = parent::doStore($updateNulls);

        if ($doStore) {
            $post      = JFactory::getApplication()->input->post;
            $db        = JFactory::getDbo();
            $colorIds  = $post->get('colour_id');
            $codeImage = $post->getString('code_image');
            $isImage   = $post->getString('is_image');
            $totalLoop = count($colorIds);

            $query = $db->getQuery(true)
                ->delete($db->qn('#__redshop_catalog_colour'))
                ->where($db->qn('sample_id') . ' = ' . $db->q($this->id));

            \Redshop\DB\Tool::safeExecute($db, $query);

            if ($totalLoop > 0) {
                $columns = array('sample_id', 'code_image', 'is_image');
                for ($h = 0, $nh = count($colorIds); $h < $nh; $h++) {
                    $values = [$db->q($this->id), $db->q($codeImage[$h]), $db->q($isImage[$h])];

                    $query->clear()
                        ->insert($db->quoteName('#__redshop_catalog_colour'))
                        ->columns($db->quoteName($columns))
                        ->values(implode(',', $values));

                    \Redshop\DB\Tool::safeExecute($db, $query);
                }
            }
        }

        return $doStore;
    }

}
