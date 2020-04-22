<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class RedshopModelWrapper_detail extends RedshopModel
{
    public $_id = null;

    public $_productid = null;

    public $_data = null;

    public $_table_prefix = null;

    public function __construct()
    {
        parent::__construct();
        $this->_table_prefix = '#__redshop_';

        /**
         * Only setup ID from cid if not add task
         * TODO Refactor this form into right Joomla! standard
         */
        $input = JFactory::getApplication()->input;

        if ($input->getCmd('task') != 'add') {
            $array = $input->getInt('cid', 0);

            // Set record Id from cid
            $this->setId((is_array($array)) ? (int)$array[0] : $array);
        } else {
            $this->setId(0);
        }

        $this->_sectionid = JRequest::getVar('product_id', 0, '', 'int');
    }

    public function setId($id)
    {
        $this->_id = $id;
        $this->_data = null;
    }

    public function &getData()
    {
        if ($this->_loadData()) {
        } else {
            $this->_initData();
        }

        return $this->_data;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function _loadData()
    {
        if (empty($this->_data)) {
            $this->_data = \Redshop\Wrapper\Helper::getWrapperById($this->_id);

            return (boolean)$this->_data;
        }

        return true;
    }

    public function _initData()
    {
        if (empty($this->_data)) {
            $detail = new stdClass;
            $detail->wrapper_id = 0;
            $detail->product_id = $this->_productid;
            $detail->category_id = 0;
            $detail->wrapper_price = 0.00;
            $detail->wrapper_name = null;
            $detail->wrapper_image = null;
            $detail->published = 1;
            $detail->wrapper_use_to_all = 0;

            $this->_data = $detail;

            return (boolean)$this->_data;
        }

        return true;
    }

    /**
     * @param $productId
     * @return mixed
     */
    public function getProductName($productId)
    {
        $product = \Redshop\Product\Product::getProductById($productId);

        return $product->product_name ?? '';
    }

    /**
     * @param int $productId
     * @return array
     */
    public function getProductInfo($productId = 0)
    {
        $product = \Redshop\Product\Product::getProductById($productId);

        return [
            'text' => $product->product_name ?? '',
            'value' => $product->product_id ?? ''
        ];
    }

    /**
     * @param $categoryId
     * @return mixed
     */
    public function getCategoryName($categoryId)
    {
        return \RedshopHelperCategory::getCategoryById($categoryId);
    }

    public function getCategoryInfo($categoryId = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_category'))
            ->where($db->qn('level') . ' > 0');

        if ($categoryId > 0) {
            $query->where($db->qn('id') . ' = ' . $db->q((int)$categoryId));
        }

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * @param int $productId
     * @return mixed
     */
    public function getProductInfoWrapper($productId = 0)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->qn('product_name', 'text') . ', ' . $db->qn('product_id', 'value'))
            ->from($db->qn('#__redshop_product'))
            ->where($db->qn('published') . ' = ' . $db->q('1'));

        if (isset($productId) & $productId > 0) {
            $query->where($db->qn('product_id') . ' IN (' . $productId . ')');
        } else {
            $query->where($db->qn('product_id') . ' = "" ');
        }

        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * @param $name
     * @param $list
     * @param $selectList
     * @param $displayId
     * @param $displayName
     * @param bool $multiple
     * @return string
     */
    public function getMultiselectBox($name, $list, $selectList, $displayId, $displayName, $multiple = false)
    {
        $multiple = $multiple ? "multiple='multiple'" : "";
        $id = str_replace('[]', '', $name);
        $size = 10;
        $data = [];

        for ($i = 0, $in = count($list); $i < $in; $i++) {
            $e = new stdClass;
            $e->name = $list[$i]->$displayName;
            $e->value = $list[$i]->$displayId;

            for ($j = 0, $jn = count($selectList); $j < $jn; $j++) {
                if ($selectList[$j] == $list[$i]->$displayId) {
                    $e->selected = 'selected';
                    break;
                }
            }

            $data[] = $e;
        }

        return \RedshopLayoutHelper::render(
            'tags.common.select',
            [
                'name'  => $name,
                'id'    => $id,
                'size'  => $size,
                'list'  => $data
            ],
            '',
            \RedshopLayoutHelper::$layoutOption
        );
    }

    /**
     * @param $data
     * @return bool|JTable
     * @throws Exception
     */
    public function store($data)
    {
        $row = $this->getTable();

        if (!$row->bind($data)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        $input = JFactory::getApplication()->input;
        $wrapperFile = $input->files->get('wrapper_image', '', 'array');
        $wrapperImage = "";

        if ($wrapperFile['name'] != "") {
            $wrapperImage = RedshopHelperMedia::cleanFileName($wrapperFile['name']);

            $src = $wrapperFile['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . '/wrapper/' . $wrapperImage;

            if ($data['wrapper_name'] == "") {
                $data['wrapper_name'] = $wrapperImage;
            }

            $row->wrapper_image = $wrapperImage;
            \JFile::upload($src, $dest);
        }

        if ($row->wrapper_id) {
            $wrapper = \RedshopHelperProduct::getWrapper($row->product_id, $row->wrapper_id);

            if (is_array($wrapper)
                && (count($wrapper) > 0)
                && ($wrapperImage != "")) {
                $unlinkPath = REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/thumb/' . $wrapper[0]->wrapper_image;

                if (\JFile::exists($unlinkPath)) {
                    \JFile::delete($unlinkPath);
                }

                $unlinkPath = REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/' . $wrapper[0]->wrapper_image;

                if (\JFile::exists($unlinkPath)) {
                    \JFile::delete($unlinkPath);
                }
            }
        }

        $categoryId = 0;

        if (count($input->get('categoryid')) > 0) {
            $categoryId = implode(",", $_POST['categoryid']);
        }

        $row->category_id = $categoryId;

        $row->product_id = $data['container_product'];

        if (!$row->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        if (isset($data['thumb_image_delete'])) {
            $row->product_thumb_image = "";
            $unlinkPath = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/' . $data['old_thumb_image']);

            if (\JFile::exists($unlinkPath)) {
                \JFile::delete($unlinkPath);
            }
        }

        return $row;
    }

    /**
     * @param array $wrapperIds
     * @return bool
     * @throws Exception
     */
    public function delete($wrapperIds = array())
    {
        return \Redshop\Wrapper\Helper::removeWrappers($wrapperIds);
    }

    /**
     * @param array $wrapperIds
     * @param int $publish
     * @return bool
     * @throws Exception
     */
    public function publish($wrapperIds = [], $publish = 1)
    {
        return \Redshop\Wrapper\Helper::setPublishStatus($wrapperIds, $publish);
    }

    /**
     * @param array $wrapperIds
     * @param int $status
     * @return bool
     * @throws Exception
     */
    public function enableWrapperUseToAll($wrapperIds = array(), $status = 1)
    {
        return \Redshop\Wrapper\Helper::enableWrapperUseToAll($wrapperIds, $status);
    }
}
