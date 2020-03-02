<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');


/**
 * Product_Detail Model.
 *
 * @package     RedSHOP.Backend
 * @subpackage  Administrator
 *
 * @since       1.0
 */
class RedshopModelProduct_Detail extends RedshopModel
{
    use Redshop\Model\Traits\HasDateTimeRange;

    public $id = null;

    public $data = null;

    public $table_prefix = null;

    public $attribute_data = null;

    public $copydata = null;

    public $app;

    public $input;

    protected static $childproductlist = array();

    /**
     * Alias for querying mapping and content type table.
     *
     * @var    string
     * @since  2.0.7
     */
    public $typeAlias = 'com_redshop.product';

    /**
     * Constructor to set the right model
     */
    public function __construct()
    {
        parent::__construct();

        $this->table_prefix = '#__redshop_';
        $this->app = JFactory::getApplication();
        $this->input = $this->app->input;
        $array = $this->input->get('cid', array(0), 'array');

        $this->setId((int)$array[0]);
    }

    /**
     * Function setId.
     *
     * @param int $id ID.
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
        $this->data = null;
    }

    /**
     * Function getData.
     *
     * @return object
     */
    public function &getData()
    {
        if ($this->_loadData()) {
            if (!empty($_POST)) {
                $this->_initData();
            }
        } else {
            $this->_initData();
        }

        // Set discount Price null for '0' value
        if (!$this->data->discount_price) {
            $this->data->discount_price = null;
        }

        return $this->data;
    }

    /**
     * Function _loadData.
     *
     * @return boolean
     */
    public function _loadData()
    {
        if (empty($this->data)) {
            // Initialiase variables.
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->qn('#__redshop_product'))
                ->where($db->qn('product_id') . ' = ' . (int)$this->id);

            // Set the query and load the result.
            $db->setQuery($query);

            try {
                $this->data = $db->loadObject();
            } catch (RuntimeException $e) {
                throw new RuntimeException($e->getMessage(), $e->getCode());
            }

            return (boolean)$this->data;
        }

        return true;
    }

    /**
     * Function _initData.
     *
     * @return mixed
     */
    public function _initData()
    {
        $detail = new stdClass;

        // ToDo: This is potentially unsafe because $_POST elements are not sanitized.
        $data = $this->input->post->getArray();
        $data['product_desc'] = JFilterInput::getInstance(null, null, 1, 1)->clean($this->input->get('product_desc', '', 'RAW'), 'html');
        $data['product_s_desc'] = JFilterInput::getInstance(null, null, 1, 1)->clean($this->input->get('product_s_desc', '', 'RAW'), 'html');
        $detail->product_id = (isset($data['product_id'])) ? $data['product_id'] : 0;
        $detail->product_parent_id = (isset($data['product_parent_id'])) ? $data['product_parent_id'] : 0;
        $detail->product_number = (isset($data['product_number'])) ? $data['product_number'] : null;
        $detail->product_price = (isset($data['product_price'])) ? $data['product_price'] : 0;
        $detail->discount_price = (isset($data['discount_price'])) ? $data['discount_price'] : null;
        $detail->discount_stratdate = (isset($data['discount_stratdate'])) ? $data['discount_stratdate'] : null;
        $detail->discount_enddate = (isset($data['discount_enddate'])) ? $data['discount_enddate'] : null;
        $detail->product_volume = (isset($data['product_volume'])) ? $data['product_volume'] : 0;
        $detail->product_type = (isset($data['product_type'])) ? $data['product_type'] : null;
        $detail->product_name = (isset($data['product_name'])) ? $data['product_name'] : null;
        $detail->product_s_desc = (isset($data['product_s_desc'])) ? $data['product_s_desc'] : null;
        $detail->product_desc = (isset($data['product_desc'])) ? $data['product_desc'] : null;
        $detail->product_template = (isset($data['product_template'])) ? $data['product_template'] : 0;
        $detail->product_full_image = (isset($data['old_image'])) ? $data['old_image'] : null;
        $detail->product_thumb_image = (isset($data['old_thumb_image'])) ? $data['old_thumb_image'] : null;
        $detail->product_back_full_image = (isset($data['product_back_full_image'])) ? $data['product_back_full_image'] : null;
        $detail->product_back_thumb_image = (isset($data['product_back_thumb_image'])) ? $data['product_back_thumb_image'] : null;
        $detail->product_preview_image = (isset($data['product_preview_image'])) ? $data['product_preview_image'] : null;
        $detail->product_preview_back_image = (isset($data['product_preview_back_image'])) ? $data['product_preview_back_image'] : null;

        $detail->visited = (isset($data['visited'])) ? $data['visited'] : 0;
        $detail->metakey = (isset($data['metakey'])) ? $data['metakey'] : null;
        $detail->metadesc = (isset($data['metadesc'])) ? $data['metadesc'] : null;
        $detail->metalanguage_setting = (isset($data['metalanguage_setting'])) ? $data['metalanguage_setting'] : null;
        $detail->metarobot_info = (isset($data['metarobot_info'])) ? $data['metarobot_info'] : null;
        $detail->pagetitle = (isset($data['pagetitle'])) ? $data['pagetitle'] : null;
        $detail->pageheading = (isset($data['pageheading'])) ? $data['pageheading'] : null;
        $detail->sef_url = (isset($data['sef_url'])) ? $data['sef_url'] : null;
        $detail->cat_in_sefurl = (isset($data['cat_in_sefurl'])) ? $data['cat_in_sefurl'] : null;
        $detail->manufacturer_id = (isset($data['manufacturer_id'])) ? $data['manufacturer_id'] : null;
        $detail->supplier_id = (isset($data['supplier_id'])) ? $data['supplier_id'] : null;
        $detail->product_on_sale = (isset($data['product_on_sale'])) ? $data['product_on_sale'] : null;
        $detail->product_special = (isset($data['product_special'])) ? $data['product_special'] : 0;
        $detail->product_download = (isset($data['product_download'])) ? $data['product_download'] : 0;
        $detail->not_for_sale = (isset($data['not_for_sale'])) ? $data['not_for_sale'] : 0;
        $detail->published = (isset($data['published'])) ? $data['published'] : 1;
        $detail->product_tax_id = (isset($data['product_tax_id'])) ? $data['product_tax_id'] : null;
        $detail->product_tax_group_id = (isset($data['product_tax_group_id'])) ? $data['product_tax_group_id'] : null;
        $detail->weight = (isset($data['weight'])) ? $data['weight'] : 0;
        $detail->expired = (isset($data['expired'])) ? $data['expired'] : 0;
        $detail->use_discount_calc = (isset($data['use_discount_calc'])) ? $data['use_discount_calc'] : 0;
        $detail->discount_calc_method = (isset($data['discount_calc_method'])) ? $data['discount_calc_method'] : null;
        $detail->min_order_product_quantity = (isset($data['min_order_product_quantity'])) ? $data['min_order_product_quantity'] : 0;
        $detail->product_length = (isset($data['product_length'])) ? $data['product_length'] : 0;
        $detail->product_width = (isset($data['product_width'])) ? $data['product_width'] : 0;
        $detail->product_height = (isset($data['product_height'])) ? $data['product_height'] : 0;
        $detail->product_diameter = (isset($data['product_diameter'])) ? $data['product_diameter'] : 0;
        $detail->use_range = (isset($data['use_range'])) ? $data['use_range'] : 0;
        $detail->product_availability_date = (isset($data['product_availability_date'])) ? $data['product_availability_date'] : 0;
        $detail->product_download_days = (isset($data['product_download_days'])) ? $data['product_download_days'] : 0;
        $detail->product_download_limit = (isset($data['product_download_limit'])) ? $data['product_download_limit'] : 0;
        $detail->product_download_clock = (isset($data['product_download_clock'])) ? $data['product_download_clock'] : 0;
        $detail->product_download_clock_min = (isset($data['product_download_clock_min'])) ? $data['product_download_clock_min'] : 0;
        $detail->product_download_infinite = (isset($data['product_download_infinite'])) ? $data['product_download_infinite'] : 0;

        $detail->checked_out = (isset($data['checked_out'])) ? $data['checked_out'] : 0;
        $detail->checked_out_time = (isset($data['checked_out_time'])) ? $data['checked_out_time'] : 0;
        $detail->accountgroup_id = (isset($data['accountgroup_id'])) ? $data['accountgroup_id'] : 0;
        $detail->quantity_selectbox_value = (isset($data['quantity_selectbox_value'])) ? $data['quantity_selectbox_value'] : null;
        $detail->preorder = (isset($data['preorder'])) ? $data['preorder'] : 'global';
        $detail->minimum_per_product_total = (isset($data['minimum_per_product_total'])) ? $data['minimum_per_product_total'] : 0;
        $detail->attribute_set_id = (isset($data['attribute_set_id'])) ? $data['attribute_set_id'] : 0;
        $detail->append_to_global_seo = ((isset($data['append_to_global_seo']))
            ? $data['append_to_global_seo'] : JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO'));
        $detail->allow_decimal_piece = (isset($data['allow_decimal_piece'])) ? $data['allow_decimal_piece'] : 0;

        $detail->use_individual_payment_method = (isset($data['use_individual_payment_method'])) ? $data['use_individual_payment_method'] : 0;

        $this->data = $detail;

        return (boolean)$this->data;
    }

    /**
     * Function store.
     *
     * @param array $data Product detail data.
     *
     * @return  boolean|TableProduct_Detail
     * @throws  Exception
     */
    public function store($data)
    {
        $dispatcher = \RedshopHelperUtility::getDispatcher();
        $db = \JFactory::getDbo();
        $catOrder = [];
        $oldCategory = [];

        /** @var TableProduct_Detail $row */
        $row = $this->getTable('product_detail');

        if (!empty($data['product_id'])) {
            $row->load($data['product_id']);
        }

        if ($data['task'] == 'save2copy') {
            $data['product_name'] = $this->renameToUniqueValue('product_name', $data['product_name']);
            $data['product_number'] = $this->renameToUniqueValue('product_number', $data['product_number'], 'dash');
        }

        $this->handleDateTimeRange($data['discount_stratdate'], $data['discount_enddate']);

        if (!$row->bind($data)) {
            $this->app->enqueueMessage(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg(), 'error');

            return false;
        }

        if (isset($data['copy_attribute'])) {
            if ($data['copy_attribute'] > 0) {
                if ($data['attribute_set_id'] <= 0) {
                    return false;
                }

                $row->attribute_set_id = 0;
            }
        }

        if (!$row->check()) {
            $this->app->enqueueMessage($row->getError(), 'error');

            return false;
        }

        if (isset($data['thumb_image_delete'])) {
            $row->product_thumb_image = "";
            $unlink_path = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['old_thumb_image']);

            if (JFile::exists($unlink_path)) {
                JFile::delete($unlink_path);
            }
        }

        $thumbFile = $this->input->files->get('product_thumb_image', array(), 'array');

        if ($thumbFile['name'] != "") {
            $filename = \RedshopHelperMedia::cleanFileName($thumbFile['name'], $row->product_id);
            $row->product_thumb_image = $filename;

            // Image Upload
            $src = $thumbFile['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
            JFile::upload($src, $dest);
        }

        // Get File name, tmp_name
        $file = $this->input->files->get('product_full_image', array(), 'array');

        if (isset($data['image_delete']) || !empty($file['name']) || !empty($data['product_full_image'])) {
            $unlink_path = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/thumb/' . $data['old_image']);

            if (JFile::exists($unlink_path)) {
                JFile::delete($unlink_path);
            }

            $unlink_path = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['old_image']);

            if (JFile::exists($unlink_path)) {
                JFile::delete($unlink_path);
            }

            $db = $this->_db;
            $query = $db->getQuery(true);
            $query->delete($db->qn('#__redshop_media'))
                ->where($db->qn('media_name') . ' = ' . $db->q(data['old_image']))
                ->where($db->qn('media_section') . ' = ' . $db->q('product'))
                ->where($db->qn('section_id') . ' = ' . $row->product_id);
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }
        }

        if (isset($data['product_full_image_delete']) && boolval($data['product_full_image_delete']) === true) {
            if (!empty($row->product_full_image)) {
                $oldImage = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $row->product_full_image);

                if (JFile::exists($oldImage)) {
                    JFile::delete($oldImage);
                }
            }

            $row->product_full_image = '';
        }

        $mediaFullImage = '';

        if (empty($data['copy_product'])) {
            // Media: Store product full image
            $mediaFullImage = $this->storeMedia($row, 'product_full_image');
        }

        if (isset($data['back_thumb_image_delete'])) {
            $row->product_back_thumb_image = "";
            $unlink_path = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_back_thumb_image']);

            if (JFile::exists($unlink_path)) {
                JFile::delete($unlink_path);
            }
        }

        $backthumbfile = $this->input->files->get('product_back_thumb_image', array(), 'array');

        if ($backthumbfile['name'] != "") {
            $filename = RedshopHelperMedia::cleanFileName($backthumbfile['name'], $row->product_id);
            $row->product_back_thumb_image = $filename;

            // Image Upload
            $src = $backthumbfile['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
            JFile::upload($src, $dest);
        }

        if (isset($data['back_image_delete'])) {
            $row->product_back_full_image = "";
            $unlink_path = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_back_full_image']);

            if (JFile::exists($unlink_path)) {
                JFile::delete($unlink_path);
            }
        }

        $backthumbfile = $this->input->files->get('product_back_full_image', array(), 'array');

        if ($backthumbfile['name'] != "") {
            $filename = RedshopHelperMedia::cleanFileName($backthumbfile['name'], $row->product_id);
            $row->product_back_full_image = $filename;

            // Image Upload
            $src = $backthumbfile['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
            JFile::upload($src, $dest);
        }

        // Upload product preview image.
        if (isset($data['preview_image_delete'])) {
            $row->product_preview_image = "";
            $unlink_path = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_preview_image']);

            if (JFile::exists($unlink_path)) {
                JFile::delete($unlink_path);
            }
        }

        $previewfile = $this->input->files->get('product_preview_image', array(), 'array');

        if ($previewfile['name'] != "") {
            $filename = RedshopHelperMedia::cleanFileName($previewfile['name'], $row->product_id);
            $row->product_preview_image = $filename;

            // Image Upload
            $src = $previewfile['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
            JFile::upload($src, $dest);
        }

        // Upload product preview back image
        if (isset($data['preview_back_image_delete'])) {
            $row->product_preview_image = "";
            $unlink_path = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_preview_back_image']);

            if (JFile::exists($unlink_path)) {
                JFile::delete($unlink_path);
            }
        }

        $previewbackfile = $this->input->files->get('product_preview_back_image', array(), 'array');

        if ($previewbackfile['name'] != "") {
            $filename = RedshopHelperMedia::cleanFileName($previewfile['name'], $row->product_id);
            $row->product_preview_back_image = $filename;

            // Image Upload
            $src = $previewbackfile['tmp_name'];
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $filename;
            JFile::upload($src, $dest);
        }

        // Product not for sell - Show price or not
        if ($row->not_for_sale && $data['not_for_sale_showprice']) {
            $row->not_for_sale = 2;
        }

        $isNew = ($row->product_id > 0) ? false : true;

        JPluginHelper::importPlugin('redshop_product');
        JPluginHelper::importPlugin('redshop_product_type');

        /**
         * @var array Trigger redSHOP Product Plugin
         */
        $result = $dispatcher->trigger('onBeforeProductSave', array(&$row, $isNew));

        if (in_array(false, $result, true)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $row->getError());

            return false;
        }

        if (!$row->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        $dispatcher->trigger('onAfterProductSave', array(&$row, $isNew));

        // Upgrade media reference Id if needed
        if ($isNew && !empty($mediaFullImage) !== false && !$data['copy_product']) {
            /** @var Tablemedia_detail $mediaTable */
            $mediaTable = $this->getTable('media_detail');

            if ($mediaTable->load($mediaFullImage)) {
                $mediaTable->set('section_id', $row->product_id);
                $mediaTable->store();
            }
        }

        if (isset($data['copy_attribute'])) {
            if ($data['copy_attribute'] > 0) {
                $row->attribute_set_id = 0;
                $this->copyAttributeSetAttribute($data['attribute_set_id'], $row->product_id);
            }
        }

        if (!$data['product_id']) {
            $prodid = $row->product_id;
        } else {
            $prodid = $data['product_id'];
            $cids = implode(",", $data['product_category']);
            $query = "SELECT category_id,ordering FROM " . $this->table_prefix . "product_category_xref
					  WHERE product_id='" . $prodid . "'
					  AND category_id IN(" . $cids . ")";
            $categories = $this->_getList($query);

            for ($g = 0, $gn = count($categories); $g < $gn; $g++) {
                $oldCategory[$g] = $categories[$g]->category_id;
                $catOrder[$categories[$g]->category_id] = $categories[$g]->ordering;
            }

            $query = 'DELETE FROM ' . $this->table_prefix . 'product_category_xref WHERE product_id="' . $prodid . '" ';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            // Delete redshop_product_payment_xref
            $db = $this->_db;
            $query = $db->getQuery(true)
                ->delete($db->qn('#__redshop_product_payment_xref'))
                ->where($db->qn('product_id') . ' = ' . $db->q($prodid));

            if (!$db->setQuery($query)->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }
        }

        $catDiscountQuery = $this->_db->getQuery(true);
        $catDiscountQuery->select('*')->from('#__redshop_mass_discount');

        $categories = array_unique($data['product_category']);

        // Building product categories relationship
        foreach ($categories as $index => $category) {
            if (array_key_exists($category, $catOrder)) {
                $ordering = $catOrder [$category];
            } else {
                $queryorder = "SELECT max(ordering)  FROM " . $this->table_prefix . "product_category_xref WHERE  category_id ='" . $category . "' ";
                $this->_db->setQuery($queryorder);
                $result = $this->_db->loadResult();
                $ordering = $result + 1;
            }

            $query = 'INSERT INTO ' . $this->table_prefix . 'product_category_xref(category_id,product_id,ordering)
					  VALUES ("' . $category . '","' . $prodid . '","' . $ordering . '")';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            if ((count($categories) - 1) != $index) {
                $catDiscountQuery->where(' FIND_IN_SET(' . (int)$category . ',' . $this->_db->quoteName('category_id') . ')', 'OR');
            } else {
                $catDiscountQuery->where(' FIND_IN_SET(' . (int)$category . ',' . $this->_db->quoteName('category_id') . ')');
            }
        }

        $category_array = array_diff($categories, $oldCategory);

        if (count($category_array) > 0) {
            $category_array = array_diff($oldCategory, $categories);
        }

        $catDiscountQuery->order($this->_db->quoteName('id') . ' DESC LIMIT 0, 1');
        $this->_db->setQuery($catDiscountQuery);
        $mass_discount = $this->_db->loadObject();

        if (count($category_array) > 0) {
            $this->updateproductdiscount($mass_discount, $row);
        }

        // Insert product_payment
        if (!empty($data['payment_method'])) {
            $payments = array_unique($data['payment_method']);

            // Building product payments relationship
            foreach ($payments as $index => $paymentMethod) {
                $query = $this->_db->getQuery(true);
                $columns = array('payment_id', 'product_id');
                $values = array($this->_db->q($paymentMethod), $this->_db->q($prodid));

                // Prepare the insert query.
                $query
                    ->insert($this->_db->qn($this->table_prefix . 'product_payment_xref'))
                    ->columns($this->_db->qn($columns))
                    ->values(implode(',', $values));
                $this->_db->setQuery($query);

                if (!$this->_db->execute()) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                    return false;
                }
            }
        }

        $sel = "SELECT * FROM " . $this->table_prefix . "mass_discount WHERE FIND_IN_SET('" . $row->manufacturer_id .
            "',manufacturer_id) ORDER BY id desc limit 0,1";
        $this->_db->setQuery($sel);
        $mass_discount = $this->_db->loadObject();

        if ($data['old_manufacturer_id'] != $row->manufacturer_id) {
            $this->updateproductdiscount($mass_discount, $row);
        }

        // Save Stcok and Preorder stock for Product
        if ((isset($data['quantity']) && $data['quantity']) || (isset($data['preorder_stock']) && $data['preorder_stock'])) {
            $productId = $row->product_id;

            for ($i = 0, $countQuantity = count($data['quantity']); $i < $countQuantity; $i++) {
                if ($data['ordered_preorder'][$i] > $data['preorder_stock'][$i]) {
                    $this->app->enqueueMessage(JText::_('COM_REDSHOP_PREORDER_STOCK_NOT_ALLOWED'), 'notice');

                    return false;
                }

                $query = "DELETE FROM " . $this->table_prefix . "product_stockroom_xref "
                    . "WHERE product_id = '" . $productId . "' and  stockroom_id ='" . $data['stockroom_id'][$i] . "'";
                $this->_db->setQuery($query);

                if (!$this->_db->execute()) {
                    return false;
                } else {
                    if ($data['quantity'][$i] != "" || !Redshop::getConfig()->get('USE_BLANK_AS_INFINITE')) {
                        $this->insertProductStock(
                            $productId,
                            $data['stockroom_id'][$i],
                            $data['quantity'][$i],
                            $data['preorder_stock'][$i],
                            $data['ordered_preorder'][$i]
                        );
                    }
                }
            }
        }

        // Building product categories relationship end.
        if (!empty($data['product_accessory'])) {
            $data['product_accessory'] = array_merge(array(), $data['product_accessory']);

            for ($a = 0, $countAccessory = count($data['product_accessory']); $a < $countAccessory; $a++) {
                $acc = $data['product_accessory'][$a];
                $accdetail = $this->getTable('accessory_detail');

                if (!isset($data['copy_product']) || $data['copy_product'] != 1) {
                    $accdetail->accessory_id = $acc['accessory_id'];
                }

                $accdetail->product_id = $row->product_id;
                $accdetail->child_product_id = $acc['child_product_id'];
                $accdetail->accessory_price = $acc['accessory_price'];
                $accdetail->oprand = $acc['oprand'];
                $accdetail->ordering = $acc['ordering'];
                $accdetail->setdefault_selected = (isset($acc['setdefault_selected']) && $acc['setdefault_selected'] == 1) ? 1 : 0;

                if (!$accdetail->store()) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                    return false;
                }
            }
        }

        $query_rel_del = 'DELETE FROM ' . $this->table_prefix . 'product_related ' . 'WHERE product_id IN ( ' . $row->product_id . ' )';
        $this->_db->setQuery($query_rel_del);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        $ordering_related = 0;

        if (isset($data['related_product']) && ($relatedProducts = explode(',', $data['related_product']))) {
            foreach ($relatedProducts as $related_data) {
                $ordering_related++;
                $related_id = $related_data;
                $productId = $row->product_id;
                $query_related = 'INSERT INTO ' . $this->table_prefix . 'product_related(related_id,product_id,ordering)
								  VALUES ("' . $related_id . '","' . $productId . '","' . $ordering_related . '")';
                $this->_db->setQuery($query_related);

                if (!$this->_db->execute()) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                    return false;
                }
            }
        }

        // Discount calculator start
        $query = "DELETE FROM `" . $this->table_prefix . "product_discount_calc` WHERE product_id='" . $row->product_id . "' ";
        $this->_db->setQuery($query);
        $this->_db->execute();

        $calc_error = 0;
        $calc_extra = 0;
        $err_msg = '';
        $extra_err_msg = '';

        if (isset($data['area_start']) && count($data['area_start']) > 0) {
            $discount_calc_unit = $data['discount_calc_unit'];
            $area_start = $data['area_start'];
            $area_end = $data['area_end'];
            $area_price = $data['area_price'];

            for ($c = 0, $cn = count($area_start); $c < $cn; $c++) {
                // Convert whatever unit into meter
                $unit = \Redshop\Helper\Utility::getUnitConversation("m", $discount_calc_unit[$c]);

                // Replace comma with dot
                $new_area_start = str_replace(",", ".", $area_start[$c]);
                $new_area_end = str_replace(",", ".", $area_end[$c]);

                if ($data['discount_calc_method'] == 'volume') {
                    $calcunit = pow($unit, 3);
                } elseif ($data['discount_calc_method'] == 'area') {
                    $calcunit = pow($unit, 2);
                } else {
                    $calcunit = $unit;
                }

                // Updating value
                $converted_area_start = $new_area_start * $calcunit;
                $converted_area_end = $new_area_end * $calcunit;

                $calcrow = $this->getTable('product_discount_calc');
                $calcrow->load();
                $calcrow->discount_calc_unit = $discount_calc_unit[$c];
                $calcrow->area_start = $new_area_start;
                $calcrow->area_end = $new_area_end;
                $calcrow->area_price = $area_price[$c];
                $calcrow->area_start_converted = $converted_area_start;
                $calcrow->area_end_converted = $converted_area_end;
                $calcrow->product_id = $row->product_id;

                if ($calcrow->check()) {
                    if (!$calcrow->store()) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }
                } else {
                    $calc_error = 1;
                    $err_msg = $calcrow->_error;
                }
            }
        }

        // Discount calculator add extra data
        $query = "DELETE FROM `" . $this->table_prefix . "product_discount_calc_extra` WHERE product_id='" . $row->product_id . "' ";
        $this->_db->setQuery($query);
        $this->_db->execute();

        if (isset($data['pdc_option_name']) && count($data['pdc_option_name']) > 0) {
            $pdc_oprand = $data['pdc_oprand'];
            $pdc_option_name = $data['pdc_option_name'];
            $pdc_price = $data['pdc_price'];
            $calc_extra = 0;

            for ($c = 0, $cn = count($pdc_option_name); $c < $cn; $c++) {
                if (trim($pdc_option_name[$c]) != "") {
                    $pdcextrarow = $this->getTable('product_discount_calc_extra');
                    $pdcextrarow->load();
                    $pdcextrarow->pdcextra_id = 0;
                    $pdcextrarow->option_name = $pdc_option_name[$c];
                    $pdcextrarow->oprand = $pdc_oprand[$c];
                    $pdcextrarow->price = $pdc_price[$c];
                    $pdcextrarow->product_id = $row->product_id;

                    if (!$pdcextrarow->store()) {
                        $calc_extra = 1;
                        $extra_err_msg = /** @scrutinizer ignore-deprecated */
                            $this->_db->getErrorMsg();
                    }
                }
            }
        }

        if ($calc_error == 1) {
            /** @scrutinizer ignore-deprecated */
            $this->setError($err_msg);

            return false;
        }

        if ($calc_extra == 1) {
            /** @scrutinizer ignore-deprecated */
            $this->setError($extra_err_msg);

            return false;
        }

        $db = $this->_db;
        $subscriptionQuery = $db->getQuery(true);

        $subscriptionQuery->delete($db->qn('#__redshop_product_subscription'))
            ->where($db->qn('product_id') . ' = ' . $db->q($row->product_id));

        // Product subscription start
        if (isset($data['subscription_id']) && is_array($data['subscription_id'])) {
            $data['subscription_id'] = $db->q($data['subscription_id']);
            $subscriptionQuery->where($db->qn('subscription_id') . ' NOT IN('
                . implode(",", $data['subscription_id']) . ')');
        }

        $this->_db->setQuery($subscriptionQuery);
        $this->_db->execute();

        if (isset($data['subscription_period']) && count($data['subscription_period']) > 0) {
            for ($sub = 0, $countSubscription = count($data['subscription_period']); $sub < $countSubscription; $sub++) {
                $sub_row = $this->getTable('product_subscription');
                $sub_row->subscription_id = $data['subscription_id'][$sub];
                $sub_row->subscription_period = $data['subscription_period'][$sub];
                $sub_row->period_type = $data['period_type'][$sub];
                $sub_row->subscription_price = $data['subscription_price'][$sub];
                $sub_row->product_id = $row->product_id;

                if (!$sub_row->store()) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                    return false;
                }
            }
        }

        // Subscription renewal
        $subscriptionRenewal = $this->getTable('product_subscription_renewal');
        $subscriptionRenewal->renewal_id = "";
        $subscriptionRenewal->before_no_days = "";
        $subscriptionRenewal->product_id = $row->product_id;

        if (isset($data['renewal_id'])) {
            $subscriptionRenewal->renewal_id = $data['renewal_id'];
        }

        if (isset($data['before_no_days'])) {
            $subscriptionRenewal->before_no_days = $data['before_no_days'];
        }

        try {
            $subscriptionRenewal->store();
        } catch (\RuntimeException $e) {
            /** @scrutinizer ignore-deprecated */
            \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }

        // If product_type = file and csv file uploaded than do this
        $productCSVfile = $this->input->files->get('serialcsvFile', array(), 'array');
        $ext = "";

        if (isset($productCSVfile['name'])) {
            $ext = strtolower(JFile::getExt($productCSVfile['name']));
        }

        if (isset($productCSVfile['tmp_name']) && $productCSVfile['tmp_name'] != "") {
            if ($ext == 'csv') {
                if (($handle = fopen($productCSVfile['tmp_name'], "r")) !== false) {
                    while (($csv_row = fgetcsv($handle, 1000, ",")) !== false) {
                        if ($csv_row[0] != "") {
                            $product_serial = $this->getTable('product_serial_number');
                            $product_serial->serial_number = $csv_row[0];
                            $product_serial->product_id = $row->product_id;

                            if (!$product_serial->store()) {
                                /** @scrutinizer ignore-deprecated */
                                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                return false;
                            }
                        }
                    }

                    fclose($handle);
                } else {
                    $this->app->enqueueMessage(JText::_("COM_REDSHOP_CSV_FILE_NOT_UPLOADED_TRY_AGAIN"), 'notice');
                }
            } else {
                $this->app->enqueueMessage(JText::_("COM_REDSHOP_ONLY_CSV_FILE_ALLOWED"), 'notice');

                return false;
            }
        }

        // Tags
        $tagsHelper = new JHelperTags;
        $tagsHelper->typeAlias = $this->typeAlias;

        $jtags = !empty($data['jtags']) ? $data['jtags'] : array();

        $tagsHelper->preStoreProcess($row, $jtags);
        $tagsHelper->postStoreProcess($row, $jtags);

        return $row;
    }

    /**
     * Function updateproductdiscount.
     *
     * @param array $mass_discount Object.
     * @param object $row Data detail row.
     *
     * @return boolean
     */
    public function updateproductdiscount($mass_discount, $row)
    {
        if (count($mass_discount) > 0) {
            $p_price = ($mass_discount->discount_type == 1) ?
                ($row->product_price - ($row->product_price * $mass_discount->discount_amount / 100)) :
                $mass_discount->discount_amount;

            $query = 'UPDATE ' . $this->table_prefix . 'product SET product_on_sale="1" '
                . ', discount_price="' . $p_price . '" , discount_stratdate="' . $mass_discount->discount_startdate . '" '
                . ', discount_enddate="' . $mass_discount->discount_enddate . '" WHERE product_id="' . $row->product_id . '" ';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Function delete.
     *
     * @param array $cid Array of IDs.
     *
     * @return  boolean
     * @throws  Exception
     */
    public function delete($cid = array())
    {
        if (empty($cid)) {
            return true;
        }

        $productIds = implode(',', $cid);

        if (empty($productIds)) {
            return false;
        }

        $parents = array();
        $db = $this->_db;

        JPluginHelper::importPlugin('redshop_product');
        JPluginHelper::importPlugin('redshop_product_type');

        RedshopHelperUtility::getDispatcher()->trigger('onBeforeProductDelete', array($cid));

        $query = $db->getQuery(true)
            ->select('COUNT(' . $db->qn('product_id') . ') AS ' . $db->qn('total'))
            ->select($db->qn('product_parent_id'))
            ->from($db->qn('#__redshop_product'))
            ->where($db->qn('product_parent_id') . ' IN (' . $productIds . ')')
            ->group($db->qn('product_parent_id'));

        $parentIds = (array)$db->setQuery($query)->loadObjectList();

        foreach ($parentIds as $parentId) {
            $parents[] = $parentId->product_parent_id;
            $parentKeys = array_keys($cid, $parentId->product_parent_id);
            unset($cid[$parentKeys[0]]);
        }

        if (count($parentIds) > 0) {
            $parentIds = implode(',', $parents);
            $errorMSG = sprintf(JText::_('COM_REDSHOP_PRODUCT_PARENT_ERROR_MSG'), $parentIds);
            $this->app->enqueueMessage($errorMSG, 'error');

            return false;
        }

        $imageQuery = $this->_db->getQuery(true);
        $db = $this->_db;
        $imageQuery->select($db->qn('pa.attribute_id')
            . ', ' . $db->qn('pap.property_image'))
            ->from($db->qn('#__redshop_product_attribute', 'pa'))
            ->leftJoin($db->qn('#__redshop_product_attribute_property', 'pap')
                . ' ON ' . $db->qn('pa.attribute_id') . ' = ' . $db->qn('pap.attribute_id'))
            ->where($db->qn('pa.product_id') . ' IN (' . $db->q($productIds) . ')');

        $this->_db->setQuery($imageQuery);
        $propertyimage = $this->_db->loadObjectlist();

        foreach ($propertyimage as $imagename) {
            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $imagename->property_image;
            $tsrc = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/thumb/' . $imagename->property_image;

            if (JFile::exists($dest)) {
                JFile::delete($dest);
            }

            if (JFile::exists($tsrc)) {
                JFile::delete($tsrc);
            }

            // Subattribute delete
            $subattr_delete = 'DELETE FROM ' . $this->table_prefix . 'product_subattribute_color  WHERE subattribute_id ="' .
                $imagename->property_id . '" ';
            $this->_db->setQuery($subattr_delete);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
            }

            $attr_delete = 'DELETE FROM ' . $this->table_prefix . 'product_attribute WHERE attribute_id ="' . $imagename->attribute_id . '" ';
            $this->_db->setQuery($attr_delete);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
            }

            $prop_delete = 'DELETE FROM ' . $this->table_prefix . 'product_attribute_property WHERE attribute_id ="' . $imagename->attribute_id . '" ';
            $this->_db->setQuery($prop_delete);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
            }
        }

        $imageQuery = 'SELECT p.product_thumb_image,
								   p.product_full_image,
								   p.product_back_full_image,
								   p.product_back_thumb_image,
								   p.product_preview_image,
								   p.product_preview_back_image
							FROM ' . $this->table_prefix . 'product as p
							WHERE p.product_id IN( ' . $productIds . ')';
        $this->_db->setQuery($imageQuery);
        $product_image = $this->_db->loadObjectlist();

        foreach ($product_image as $imagename) {
            $dest_full = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_full_image;
            $tsrc_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_thumb_image;
            $dest_back_full = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_back_full_image;
            $tsrc_back_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imagename->product_back_thumb_image;
            $dest_preview = REDSHOP_FRONT_IMAGES_RELPATH . '/product/' . $imagename->product_preview_image;
            $tsrc_preview_back = REDSHOP_FRONT_IMAGES_RELPATH . '/product/' . $imagename->product_preview_back_image;

            if (JFile::exists($dest_full)) {
                JFile::delete($dest_full);
            }

            if (JFile::exists($tsrc_thumb)) {
                JFile::delete($tsrc_thumb);
            }

            if (JFile::exists($dest_back_full)) {
                JFile::delete($dest_back_full);
            }

            if (JFile::exists($tsrc_back_thumb)) {
                JFile::delete($tsrc_back_thumb);
            }

            if (JFile::exists($dest_preview)) {
                JFile::delete($dest_preview);
            }

            if (JFile::exists($tsrc_preview_back)) {
                JFile::delete($tsrc_preview_back);
            }
        }

        $query = 'DELETE FROM ' . $this->table_prefix . 'product WHERE product_id IN ( ' . $productIds . ' )';
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        $query_related = 'DELETE FROM ' . $this->table_prefix . 'product_accessory WHERE product_id IN ( ' . $productIds . ' )';

        $this->_db->setQuery($query_related);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        $query_related = 'DELETE FROM ' . $this->table_prefix . 'product_related WHERE product_id IN ( ' . $productIds . ' )';

        $this->_db->setQuery($query_related);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        $query_media = 'DELETE FROM ' . $this->table_prefix . 'media WHERE section_id IN ( ' . $productIds . ' ) AND media_section = "product"';
        $this->_db->setQuery($query_media);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        // Remove product category xref relation
        $query_relation = 'DELETE FROM ' . $this->table_prefix . 'product_category_xref WHERE product_id IN ( ' . $productIds . ' ) ';
        $this->_db->setQuery($query_relation);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        // Delete Association if Exist

        $check_asso = $this->CheckRedProductFinder();

        if ($check_asso > 0) {
            $this->RemoveAssociation($cid);
        }

        // Remove product tags relation
        $query = 'DELETE FROM ' . $this->table_prefix . 'product_tags_xref  WHERE product_id IN ( ' . $productIds . ' ) ';
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        // Remove product wishlist relation
        $query = 'DELETE FROM ' . $this->table_prefix . 'wishlist_product  WHERE product_id IN ( ' . $productIds . ' ) ';
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        // Remove product compare relation
        $query = 'DELETE FROM ' . $this->table_prefix . 'product_compare  WHERE product_id IN ( ' . $productIds . ' ) ';
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        // Remove fields_data relation
        $query = 'DELETE FROM ' . $this->table_prefix . 'fields_data  WHERE itemid IN ( ' . $productIds . ' ) ';
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
        }

        RedshopHelperUtility::getDispatcher()->trigger('onAfterProductDelete', array($cid));

        return true;
    }

    /**
     * Function publish.
     *
     * @param array $cid Array of IDs.
     * @param int $publish Publish.
     *
     * @return boolean
     */
    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid)) {
            $cids = implode(',', $cid);
            $query = 'UPDATE ' . $this->table_prefix . 'product'
                . ' SET published = "' . intval($publish) . '" '
                . ' WHERE product_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }
        }

        return true;
    }

    /**
     * Function copy.
     *
     * @param array $cid Array of IDs.
     * @param bool $postMorePriority Flag what data more priority for copy - POST or DB
     *
     * @return  boolean|TableProduct_Detail
     */
    public function copy($cid = array(), $postMorePriority = false)
    {
        $row = null;
        $db = JFactory::getDbo();

        if (count($cid)) {
            $cids = implode(',', $cid);
            $query = 'SELECT * FROM ' . $this->table_prefix . 'product WHERE product_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $this->copydata = $this->_db->loadObjectList();
        }

        foreach ($this->copydata as $pdata) {
            $query = 'SELECT * FROM ' . $this->table_prefix . 'product_price WHERE product_id IN ( ' . $pdata->product_id . ' )';
            $this->_db->setQuery($query);
            $productpricedata = $this->_db->loadObjectList();

            $query = 'SELECT * FROM ' . $this->table_prefix . 'media WHERE media_section = "product" AND section_id IN ( ' . $pdata->product_id . ' ) ORDER BY media_id ASC';
            $this->_db->setQuery($query);
            $mediadata = $this->_db->loadObjectList();

            if (!$postMorePriority) {
                $query = 'SELECT category_id FROM ' . $this->table_prefix . 'product_category_xref
					  WHERE product_id IN ( ' . $pdata->product_id . ' )';
                $this->_db->setQuery($query);
                $categorydata = $this->_db->loadObjectList();
                $copycategory = array();

                for ($i = 0, $in = count($categorydata); $i < $in; $i++) {
                    $copycategory[$i] = $categorydata[$i]->category_id;
                }

                $query = $this->_db->getQuery(true);
                $query->select('*')
                    ->from($this->_db->qn($this->table_prefix . 'product_stockroom_xref'))
                    ->where($this->_db->qn('product_id') . 'IN(' . $pdata->product_id . ')');
                $stockroomdata = $this->_db->setQuery($query)->loadObjectList();
                $copystockroom = array();
                $copyquantity = array();
                $copypreorder_stock = array();
                $copyordered_preorder = array();

                for ($i = 0, $in = count($stockroomdata); $i < $in; $i++) {
                    $copystockroom[$i] = $stockroomdata[$i]->stockroom_id;
                    $copyquantity[$i] = $stockroomdata[$i]->quantity;
                    $copypreorder_stock[$i] = $stockroomdata[$i]->preorder_stock;
                    $copyordered_preorder[$i] = $stockroomdata[$i]->ordered_preorder;
                }

                $query = 'SELECT * FROM ' . $this->table_prefix . 'product_accessory WHERE product_id IN ( ' . $pdata->product_id . ' )';
                $this->_db->setQuery($query);
                $accessorydata = $this->_db->loadObjectList();
                $copyaccessory = array();

                // Accessory_product.
                for ($i = 0, $in = count($accessorydata); $i < $in; $i++) {
                    $copyaccessory[$i] = (array)$accessorydata[$i];
                }

                if (!isset($pdata->use_individual_payment_method)) {
                    $pdata->use_individual_payment_method = '';
                } else {
                    $post['payment_method'] = RedshopHelperPayment::getPaymentByIdProduct($pdata->product_id);
                }

                $post['product_parent_id'] = $pdata->product_parent_id;
                $post['manufacturer_id'] = $pdata->manufacturer_id;
                $post['supplier_id'] = $pdata->supplier_id;
                $post['product_on_sale'] = $pdata->product_on_sale;
                $post['product_special'] = $pdata->product_special;
                $post['product_download'] = $pdata->product_download;
                $post['product_template'] = $pdata->product_template;
                $post['product_name'] = $pdata->product_name;
                $post['product_price'] = $pdata->product_price;
                $post['discount_price'] = $pdata->discount_price;
                $post['discount_stratdate'] = $pdata->discount_stratdate;
                $post['discount_enddate'] = $pdata->discount_enddate;
                $post['product_length'] = $pdata->product_length;
                $post['product_height'] = $pdata->product_height;
                $post['product_width'] = $pdata->product_width;
                $post['product_diameter'] = $pdata->product_diameter;
                $post['discount_calc_method'] = $pdata->discount_calc_method;
                $post['use_discount_calc'] = $pdata->use_discount_calc;
                $post['use_range'] = $pdata->use_range;
                $post['product_number'] = $pdata->product_number;
                $post['product_type'] = $pdata->product_type;
                $post['product_s_desc'] = $pdata->product_s_desc;
                $post['product_desc'] = $pdata->product_desc;
                $post['product_volume'] = $pdata->product_volume;
                $post['product_tax_id'] = $pdata->product_tax_id;
                $post['attribute_set_id'] = $pdata->attribute_set_id;
                $post['product_tax_group_id'] = $pdata->product_tax_group_id;
                $post['min_order_product_quantity'] = $pdata->min_order_product_quantity;
                $post['max_order_product_quantity'] = $pdata->max_order_product_quantity;
                $post['accountgroup_id'] = $pdata->accountgroup_id;
                $post['quantity_selectbox_value'] = $pdata->quantity_selectbox_value;
                $post['not_for_sale'] = $pdata->not_for_sale;
                $post['product_availability_date'] = $pdata->product_availability_date;
                $post['published'] = 0;
                $post['product_thumb_image'] = $pdata->product_thumb_image;
                $post['product_full_image'] = $pdata->product_full_image;
                $post['product_back_full_image'] = $pdata->product_back_full_image;
                $post['product_back_thumb_image'] = $pdata->product_back_thumb_image;
                $post['product_preview_image'] = $pdata->product_preview_image;
                $post['product_preview_back_image'] = $pdata->product_preview_back_image;
                $post['metakey'] = $pdata->metakey;
                $post['metadesc'] = $pdata->metadesc;
                $post['metalanguage_setting'] = $pdata->metalanguage_setting;
                $post['metarobot_info'] = $pdata->metarobot_info;
                $post['pagetitle'] = $pdata->pagetitle;
                $post['pageheading'] = $pdata->pageheading;
                $post['cat_in_sefurl'] = $pdata->cat_in_sefurl;
                $post['weight'] = $pdata->weight;
                $post['expired'] = $pdata->expired;
                $post['sef_url'] = $pdata->sef_url;
                $post['canonical_url'] = $pdata->canonical_url;
                $post['product_category'] = $copycategory;
                $post['quantity'] = $copyquantity;
                $post['preorder_stock'] = $copypreorder_stock;
                $post['ordered_preorder'] = $copyordered_preorder;
                $post['stockroom_id'] = $copystockroom;
                $post['product_accessory'] = $copyaccessory;
                $post['use_individual_payment_method'] = $pdata->use_individual_payment_method;
            } else {
                $post = $this->input->post->getArray();
                $this->_initData();
                $post = array_merge($post, (array)$this->data);
            }

            $post['copy_product'] = 1;
            $post['product_id'] = 0;
            $post['product_name'] = $this->renameToUniqueValue('product_name', $post['product_name']);
            $post['product_number'] = $this->renameToUniqueValue('product_number', $post['product_number'], 'dash');
            $post['publish_date'] = date("Y-m-d H:i:s");
            $post['update_date'] = date("Y-m-d H:i:s");
            $post['visited'] = 0;
            $post['checked_out'] = 0;
            $post['checked_out_time'] = '0000-00-00 00:00:00';

            if (isset($post['sef_url']) && $post['sef_url'] != '') {
                $post['sef_url'] = $this->renameToUniqueValue('sef_url', $post['sef_url'], 'dash');
            }

            if (isset($post['canonical_url']) && $post['canonical_url'] != '') {
                $post['canonical_url'] = $this->renameToUniqueValue('canonical_url', $post['canonical_url'], 'dash');
            }

            $new_product_thumb_image = $this->changeCopyImageName($post['product_thumb_image']);
            $new_product_full_image = $this->changeCopyImageName($post['product_full_image']);
            $new_product_back_full_image = $this->changeCopyImageName($post['product_back_full_image']);
            $new_product_back_thumb_image = $this->changeCopyImageName($post['product_back_thumb_image']);
            $new_product_preview_image = $this->changeCopyImageName($post['product_preview_image']);
            $new_product_preview_back_image = $this->changeCopyImageName($post['product_preview_back_image']);

            // Prevent remove old images
            if (isset($post['old_image'])) {
                unset($post['old_image']);
            }

            if ($row = $this->store($post)) {
                $path = REDSHOP_FRONT_IMAGES_RELPATH . 'product/';
                copy($path . $pdata->product_full_image, $path . $new_product_full_image);
                copy($path . $pdata->product_thumb_image, $path . $new_product_thumb_image);
                copy($path . $pdata->product_preview_image, $path . $new_product_preview_image);
                copy($path . $pdata->product_preview_back_image, $path . $new_product_preview_back_image);
                copy($path . $pdata->product_back_full_image, $path . $new_product_back_full_image);
                copy($path . $pdata->product_back_thumb_image, $path . $new_product_back_thumb_image);

                // Copy related product only when not send in POST data
                // When POST data is set related product will be created using above store method.
                if (!isset($post['related_product'])) {
                    $query = $db->getQuery(true)
                        ->select('*')
                        ->from($db->qn('#__redshop_product_related'))
                        ->where('product_id = ' . (int)$pdata->product_id);

                    $relatedProductData = $db->setQuery($query)->loadObjectList();

                    if ($relatedProductData) {
                        foreach ($relatedProductData as $relatedData) {
                            $query = $db->getQuery(true)
                                ->insert($db->qn('#__redshop_product_related'))
                                ->set('related_id = ' . (int)$relatedData->related_id)
                                ->set('product_id = ' . (int)$row->product_id)
                                ->set('ordering = ' . (int)$relatedData->ordering);

                            if (!$db->setQuery($query)->execute()) {
                                /** @scrutinizer ignore-deprecated */
                                $this->setError(/** @scrutinizer ignore-deprecated */ $db->getErrorMsg());

                                return false;
                            }
                        }
                    }
                }

                // Field_section 1 :Product.
                RedshopHelperExtrafields::copyProductExtraField($pdata->product_id, $row->product_id);

                // End.
                $this->copyProductAttribute($pdata->product_id, $row->product_id);
                $this->copyDiscountCalcdata($pdata->product_id, $row->product_id, $pdata->discount_calc_method);

                for ($i = 0, $in = count($productpricedata); $i < $in; $i++) {
                    $rowprices_detail = $this->getTable('prices_detail');
                    $data['price_id '] = 0;
                    $data['product_id'] = $row->product_id;
                    $data['product_price'] = $productpricedata[$i]->product_price;
                    $data['product_currency'] = $productpricedata[$i]->product_currency;
                    $data['shopper_group_id'] = $productpricedata[$i]->shopper_group_id;
                    $data['price_quantity_start'] = $productpricedata[$i]->price_quantity_start;
                    $data['price_quantity_end'] = $productpricedata[$i]->price_quantity_end;

                    if (!$rowprices_detail->bind($data)) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }

                    if (!$rowprices_detail->store()) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }
                }

                for ($j = 0, $jn = count($mediadata); $j < $jn; $j++) {
                    $old_img = $mediadata[$j]->media_name;
                    $new_img = strstr($old_img, '_') ? strstr($old_img, '_') : $old_img;
                    $old_media = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $mediadata[$j]->media_name;
                    $mediaName = RedshopHelperMedia::cleanFileName($new_img);

                    if ($pdata->product_full_image == $old_img) {
                        $mediaName = $new_product_full_image;
                    }

                    $new_media = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $mediaName;
                    copy($old_media, $new_media);

                    $rowmedia = $this->getTable('media_detail');
                    $data['media_id '] = 0;
                    $data['media_name'] = $mediaName;
                    $data['media_alternate_text'] = $mediadata[$j]->media_alternate_text;
                    $data['media_section'] = $mediadata[$j]->media_section;
                    $data['section_id'] = $row->product_id;
                    $data['media_type'] = $mediadata[$j]->media_type;
                    $data['media_mimetype'] = $mediadata[$j]->media_mimetype;
                    $data['published'] = $mediadata[$j]->published;
                    $data['ordering'] = $mediadata[$j]->ordering;

                    if (!$rowmedia->bind($data)) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }

                    if (!$rowmedia->store()) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }
                }
            }
        }

        return $row;
    }

    /**
     * Change Copy Image Name
     *
     * @param string  &$imageName Image name
     *
     * @return null|string
     */
    public function changeCopyImageName(&$imageName)
    {
        if ($imageName && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $imageName)) {
            $newImageName = strstr($imageName, '_') ? strstr($imageName, '_') : $imageName;
            $newImageName = $imageName = RedshopHelperMedia::cleanFileName($newImageName);
        } else {
            $imageName = '';
            $newImageName = null;
        }

        return $newImageName;
    }

    /**
     * Function copyProductAttribute.
     *
     * @param string|int $cid Array of IDs.
     * @param int $productId Product ID.
     *
     * @return boolean
     */
    public function copyProductAttribute($cid, $productId)
    {
        $db = $this->_db;

        $query = $db->getQuery(true)
            ->select(
                $db->qn(
                    array(
                        'attribute_id',
                        'attribute_name',
                        'attribute_required',
                        'ordering',
                        'attribute_description',
                        'attribute_published'
                    )
                )
            )
            ->from($db->qn('#__redshop_product_attribute'))
            ->where($db->qn('product_id') . " IN ( " . $cid . " )")
            ->order($db->qn('ordering'));

        $attribute = $db->setQuery($query)->loadObjectList();

        for ($att = 0, $countAttribute = count($attribute); $att < $countAttribute; $att++) {
            $attribute[$att]->product_id = $productId;
            $oldAttributeId = $attribute[$att]->attribute_id;
            $attribute[$att]->attribute_id = 0;

            $resultInsertAttr = $db->insertObject('#__redshop_product_attribute', $attribute[$att]);

            if (!$resultInsertAttr) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            $attributeId = $this->_db->insertid();
            $query = 'SELECT * FROM `' . $this->table_prefix . 'product_attribute_property`
					  WHERE `attribute_id` = "' . $oldAttributeId . '" order by ordering asc';
            $this->_db->setQuery($query);
            $att_property = $this->_db->loadObjectList();


            for ($prop = 0, $countProperty = count($att_property); $prop < $countProperty; $prop++) {
                $property_save['attribute_id'] = $attributeId;
                $property_save['property_name'] = $att_property[$prop]->property_name;
                $property_save['property_price'] = $att_property[$prop]->property_price;
                $property_save['oprand'] = $att_property[$prop]->oprand;
                $property_save['property_number'] = $att_property[$prop]->property_number;
                $property_save['property_image'] = $att_property[$prop]->property_image;
                $property_save['property_main_image'] = $att_property[$prop]->property_main_image;
                $property_save['ordering'] = $att_property[$prop]->ordering;
                $property_save['setrequire_selected'] = $att_property[$prop]->setrequire_selected;
                $property_save['setdefault_selected'] = $att_property[$prop]->setdefault_selected;
                $property_save['extra_field'] = $att_property[$prop]->extra_field;
                $property_array = $this->store_pro($property_save);
                $propertyId = $property_array->property_id;
                $listImages = $this->getImageInfor($att_property[$prop]->property_id, 'property');
                $propertyimage = '';
                $property_main_image = '';

                $query = $db->getQuery(true)
                    ->select('*')
                    ->from($db->qn($this->table_prefix . 'product_attribute_price'))
                    ->where($db->qn('section_id') . ' = ' . $db->q($att_property[$prop]->property_id));

                $price_prop = $db->setQuery($query)->loadObjectList();

                for ($i = 0, $in = count($price_prop); $i < $in; $i++) {
                    $attribute_price_detail = $this->getTable('attributeprices_detail');
                    $attr_price['price_id '] = 0;
                    $attr_price['section_id'] = $propertyId;
                    $attr_price['product_price'] = $price_prop[$i]->product_price;
                    $attr_price['section'] = $price_prop[$i]->section;
                    $attr_price['product_currency'] = $price_prop[$i]->product_currency;
                    $attr_price['cdate'] = $price_prop[$i]->cdate;
                    $attr_price['shopper_group_id'] = $price_prop[$i]->shopper_group_id;
                    $attr_price['price_quantity_start'] = $price_prop[$i]->price_quantity_start;
                    $attr_price['price_quantity_end'] = $price_prop[$i]->price_quantity_end;
                    $attr_price['discount_price'] = $price_prop[$i]->discount_price;
                    $attr_price['discount_start_date'] = $price_prop[$i]->discount_start_date;
                    $attr_price['discount_end_date'] = $price_prop[$i]->discount_end_date;

                    if (!$attribute_price_detail->bind($attr_price)) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }

                    if (!$attribute_price_detail->store()) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }
                }

                $query = $db->getQuery(true)
                    ->select('*')
                    ->from($db->qn($this->table_prefix . 'product_attribute_stockroom_xref'))
                    ->where($db->qn('section_id') . ' = ' . $db->q($att_property[$prop]->property_id));

                $stock_prop = $db->setQuery($query)->loadObjectList();

                for ($i = 0, $in = count($stock_prop); $i < $in; $i++) {
                    $attr_stock['section_id'] = $propertyId;
                    $attr_stock['section'] = $stock_prop[$i]->section;
                    $attr_stock['stockroom_id'][$i] = $stock_prop[$i]->stockroom_id;
                    $attr_stock['quantity'][$i] = $stock_prop[$i]->quantity;
                    $attr_stock['preorder_stock'][$i] = $stock_prop[$i]->preorder_stock;
                    $attr_stock['ordered_preorder'][$i] = $stock_prop[$i]->ordered_preorder;

                    $this->SaveAttributeStockroom($attr_stock);
                }

                // Update image names and copy
                if (!empty($att_property[$prop]->property_image)) {
                    $propertyimage = 'product_attributes/' . $att_property[$prop]->property_image;
                    $new_property_image = $this->copy_image_from_path($propertyimage, 'product_attributes');
                    $propertyimage = $new_property_image;
                }

                if (!empty($att_property[$prop]->property_main_image)) {
                    $property_main_image = 'property/' . $att_property[$prop]->property_main_image;
                    $new_property_main_image = $this->copy_image_from_path($property_main_image, 'property');
                    $property_main_image = $new_property_main_image;
                }

                $this->update_attr_property_image($propertyId, $propertyimage, $property_main_image);

                $countImage = count($listImages);

                for ($li = $countImage - 1; $li >= 0; $li--) {
                    $mImages = array();
                    $mImages['media_name'] = $listImages[$li]->media_name;
                    $mImages['media_alternate_text'] = $listImages[$li]->media_alternate_text;
                    $mImages['media_section'] = 'property';
                    $mImages['section_id'] = $propertyId;
                    $mImages['media_type'] = 'images';
                    $mImages['media_mimetype'] = $listImages[$li]->media_mimetype;
                    $mImages['published'] = $listImages[$li]->published;
                    $isMainImage = false;

                    // detect main image
                    if ($listImages[$li]->media_name == $att_property[$prop]->property_main_image) {
                        $isMainImage = true;
                    }

                    $this->copyadditionalImage($mImages, $isMainImage, $property_main_image);
                }

                $query = 'SELECT * FROM ' . $this->table_prefix . 'product_subattribute_color
						  WHERE `subattribute_id` =  "' . $att_property[$prop]->property_id . '" order by ordering asc';
                $this->_db->setQuery($query);
                $subatt_property = $this->_db->loadObjectList();
                $countSubProperty = count($subatt_property);

                for ($subprop = 0; $subprop < $countSubProperty; $subprop++) {
                    $subproperty_save = array();
                    $subproperty_save['subattribute_color_name'] = $subatt_property[$subprop]->subattribute_color_name;
                    $subproperty_save['subattribute_color_title'] = $subatt_property[$subprop]->subattribute_color_title;
                    $subproperty_save['subattribute_color_price'] = $subatt_property[$subprop]->subattribute_color_price;
                    $subproperty_save['oprand'] = $subatt_property[$subprop]->oprand;
                    $subproperty_save['subattribute_id'] = $propertyId;
                    $subproperty_save['ordering'] = $subatt_property[$subprop]->ordering;
                    $subproperty_save['subattribute_color_number'] = $subatt_property[$subprop]->subattribute_color_number;
                    $subproperty_save['subattribute_color_image'] = $subatt_property[$subprop]->subattribute_color_image;
                    $subproperty_save['subattribute_color_main_image'] = $subatt_property[$subprop]->subattribute_color_main_image;
                    $subproperty_save['setdefault_selected'] = $subatt_property[$subprop]->setdefault_selected;

                    $subproperty_array = $this->store_sub($subproperty_save);
                    $subPropertyId = $subproperty_array->subattribute_color_id;
                    $new_subattribute_color_image = '';
                    $new_subattribute_color_main_image = '';

                    // Update image names and copy
                    if (!empty($subatt_property[$subprop]->subattribute_color_image)) {
                        $subattribute_color_image = 'subcolor/' . $subatt_property[$subprop]->subattribute_color_image;
                        $new_subattribute_color_image = $this->copy_image_from_path($subattribute_color_image, 'subcolor');
                    }

                    if (!empty($subatt_property[$subprop]->subattribute_color_main_image)) {
                        $subattribute_color_main_image = 'subproperty/' . $subatt_property[$subprop]->subattribute_color_main_image;
                        $new_subattribute_color_main_image = $this->copy_image_from_path($subattribute_color_main_image, 'subproperty');
                    }

                    $this->update_subattr_image($subPropertyId, $new_subattribute_color_image, $new_subattribute_color_main_image);

                    $listsubpropImages = $this->getImageInfor($subatt_property[$subprop]->subattribute_color_id, 'subproperty');
                    $countSubPropertyImage = count($listsubpropImages);

                    for ($lsi = $countSubPropertyImage - 1; $lsi >= 0; $lsi--) {
                        $smImages = array();
                        $smImages['media_name'] = $listsubpropImages[$lsi]->media_name;
                        $smImages['media_alternate_text'] = $listsubpropImages[$lsi]->media_alternate_text;
                        $smImages['media_section'] = 'subproperty';
                        $smImages['section_id'] = $subPropertyId;
                        $smImages['media_type'] = 'images';
                        $smImages['media_mimetype'] = $listsubpropImages[$lsi]->media_mimetype;
                        $smImages['published'] = $listsubpropImages[$lsi]->published;

                        $isMainImage = false;

                        // detect main image
                        if ($listsubpropImages[$lsi]->media_name == $subatt_property[$subprop]->subattribute_color_main_image) {
                            $isMainImage = true;
                        }

                        $this->copyadditionalImage($smImages, $isMainImage, $new_subattribute_color_main_image);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Function gettax.
     *
     * @return array
     */
    public function gettax()
    {
        $query = 'SELECT id as value,tax_rate as text FROM ' . $this->table_prefix . 'tax_rate ';
        $this->_db->setQuery($query);

        return $this->_db->loadObjectlist();
    }

    /**
     * Function getmanufacturers.
     *
     * @return array
     */
    public function getmanufacturers()
    {
        $query = 'SELECT id as value,name as text FROM ' . $this->table_prefix . 'manufacturer
				  WHERE published=1 ORDER BY `name`';
        $this->_db->setQuery($query);

        return $this->_db->loadObjectlist();
    }

    /**
     * Function getsupplier.
     *
     * @return array
     */
    public function getsupplier()
    {
        $query = 'SELECT id as value,name as text FROM ' . $this->table_prefix . 'supplier ';
        $this->_db->setQuery($query);

        return $this->_db->loadObjectlist();
    }

    /**
     * Function getproductcats.
     *
     * @return array
     */
    public function getproductcats()
    {
        $query = 'SELECT category_id FROM ' . $this->table_prefix . 'product_category_xref  WHERE product_id="' . $this->id . '" ';
        $this->_db->setQuery($query);

        return $this->_db->loadColumn();
    }

    /**
     * Function getprductpaymes.
     *
     * @return array
     */
    public function getproductpayments()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query
            ->select($db->qn('a.payment_id'))
            ->from($db->qn($this->table_prefix . 'product_payment_xref', 'a'))
            ->where($db->qn('a.product_id') . ' = ' . $db->q($this->id));

        return $db->setQuery($query)->loadColumn();
    }

    /**
     * Function catin_sefurl.
     *
     * @return array
     */
    public function catin_sefurl()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select($db->qn('c.id', 'value'))
            ->select($db->qn('c.name', 'text'))
            ->from($db->qn('#__redshop_product_category_xref', 'pcx'))
            ->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
            ->where($db->qn('pcx.product_id') . ' = ' . $db->q((int)$this->id));

        return $db->setQuery($query)->loadObjectlist();
    }

    /**
     * Function getPropertyImages.
     *
     * @param int $propertyId Property ID.
     *
     * @return  array
     */
    public function getPropertyImages($propertyId)
    {
        $query = "SELECT * FROM " . $this->table_prefix . "product_attribute_property as p, " . $this->table_prefix . "media AS m
				  WHERE m.section_id = p.property_id  and m.media_section='property' and media_type='images'
				  AND p.property_id = '" . $propertyId . "'  and m.published = 1 order by m.ordering,m.media_id asc";
        $this->_db->setQuery($query);

        return $this->_db->loadObjectlist();
    }

    /**
     * Function getSubpropertyImages.
     *
     * @param int $subPropertyId Subproperty ID.
     *
     * @return  array
     */
    public function getSubpropertyImages($subPropertyId)
    {
        $query = "SELECT * FROM " . $this->table_prefix . "product_subattribute_color as p, " . $this->table_prefix . "media AS m
				  WHERE m.section_id = p.subattribute_color_id  and m.media_section='subproperty' and media_type='images'
				  AND p.subattribute_color_id = '" . $subPropertyId . "'  and m.published = 1 order by m.ordering,m.media_id asc";
        $this->_db->setQuery($query);

        return $this->_db->loadObjectlist();
    }

    /**
     * Function getPropertyMainImage.
     *
     * @param int $propertyId Property ID.
     *
     * @return  array
     */
    public function getPropertyMainImage($propertyId)
    {
        $query = "SELECT * FROM " . $this->table_prefix . "product_attribute_property as p
				  WHERE p.property_id = '" . $propertyId . "' ORDER BY p.property_id ASC  ";
        $this->_db->setQuery($query);

        return $this->_db->loadObject();
    }

    /**
     * Function getSubAttributeColor.
     *
     * @param int $propertyId Property ID.
     *
     * @return  array
     */
    public function getSubAttributeColor($propertyId)
    {
        $query = "SELECT * FROM " . $this->table_prefix . "product_attribute_property AS p,
				 " . $this->table_prefix . "product_subattribute_color AS m
				  WHERE m.subattribute_id = p.property_id and p.property_id = '" . $propertyId . "' ";
        $this->_db->setQuery($query);

        return $this->_db->loadObjectlist();
    }

    /**
     * Function getParentProduct.
     *
     * @param int $productId Product ID.
     *
     * @return  array
     */
    public function getParentProduct($productId)
    {
        $query = "SELECT product_name FROM " . $this->table_prefix . "product
				  WHERE product_id = '" . $productId . "'   ";
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }

    /**
     * Function getattributes.
     *
     * @return mixed
     */
    public function getattributes()
    {
        if ($this->id != 0) {
            $query = 'SELECT * FROM ' . $this->table_prefix . 'product_attribute WHERE product_id="' . $this->id . '" ORDER BY ordering ASC';

            $this->_db->setQuery($query);
            $attr = $this->_db->loadObjectlist();
            $attribute_data = array();

            for ($i = 0, $in = count($attr); $i < $in; $i++) {
                $query = 'SELECT * FROM ' . $this->table_prefix . 'product_attribute_property WHERE attribute_id ="'
                    . $attr[$i]->attribute_id . '" ORDER BY ordering ASC';

                $this->_db->setQuery($query);
                $prop = $this->_db->loadObjectlist();
                $attributeId = $attr[$i]->attribute_id;
                $attribute_name = $attr[$i]->attribute_name;
                $attribute_description = $attr[$i]->attribute_description;
                $attribute_required = $attr[$i]->attribute_required;
                $allow_multiple_selection = $attr[$i]->allow_multiple_selection;
                $hide_attribute_price = $attr[$i]->hide_attribute_price;
                $ordering = $attr[$i]->ordering;
                $attribute_published = $attr[$i]->attribute_published;
                $display_type = $attr[$i]->display_type;

                for ($j = 0, $jn = count($prop); $j < $jn; $j++) {
                    $query = 'SELECT * FROM ' . $this->table_prefix . 'product_subattribute_color WHERE subattribute_id ="'
                        . $prop[$j]->property_id . '" ORDER BY ordering ASC';
                    $this->_db->setQuery($query);
                    $subprop = $this->_db->loadObjectlist();
                    $prop[$j]->subvalue = $subprop;
                }

                $attribute_data[] = array('attribute_id' => $attributeId, 'attribute_name' => $attribute_name,
                    'attribute_description' => $attribute_description,
                    'attribute_required' => $attribute_required, 'ordering' => $ordering, 'property' => $prop,
                    'allow_multiple_selection' => $allow_multiple_selection, 'hide_attribute_price' => $hide_attribute_price,
                    'attribute_published' => $attribute_published, 'display_type' => $display_type,
                    'attribute_set_id' => $attr[$i]->attribute_set_id);
            }

            return $attribute_data;
        }

        return false;
    }

    /**
     * Function getpropertylist.
     *
     * @param array $data Data.
     *
     * @return  array
     */
    public function getpropertylist($data)
    {
        $prop = null;

        if (count($data)) {
            $cids = implode(',', $data);
            $query = 'SELECT * FROM ' . $this->table_prefix . 'product_attribute_property WHERE property_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $prop = $this->_db->loadObjectlist();
        }

        return $prop;
    }

    /**
     * Function deleteprop.
     *
     * @param array $cid Array of IDs.
     * @param array $image_name Image name.
     *
     * @return  boolean
     */
    public function deleteprop($cid = array(), $image_name = array())
    {
        if (!empty($cid)) {
            $cids = implode(',', $cid);

            foreach ($image_name as $imagename) {
                $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $imagename;

                $tsrc = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/thumb/' . $imagename;

                if (file_exists($dest)) {
                    JFile::delete($dest);
                }

                if (file_exists($tsrc)) {
                    JFile::delete($tsrc);
                }
            }

            $query = 'DELETE FROM ' . $this->table_prefix . 'product_attribute_property WHERE property_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            } else {
                // Changed 5 feb
                $query = 'DELETE FROM ' . $this->table_prefix . 'product_subattribute_color  WHERE subattribute_id IN (' . $cids . ' )';
                $this->_db->setQuery($query);

                if (!$this->_db->execute()) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Function property_image_list.
     *
     * @param string $cid string of IDs.
     *
     * @return  array
     */
    public function property_image_list($cid)
    {
        $prop = null;

        if ($cid != null) {
            $imageQuery = 'SELECT property_image FROM ' . $this->table_prefix . 'product_attribute_property WHERE attribute_id IN ( ' . $cid . ' )';
            $this->_db->setQuery($imageQuery);
            $prop = $this->_db->loadObjectlist();
        }

        return $prop;
    }

    /**
     * Function store_attr.
     *
     * @param array $data Array of IDs.
     *
     * @return  mixed
     */
    public function store_attr($data)
    {
        $row = $this->getTable('product_attribute');

        if (!$row->bind($data)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        if (!$row->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        return $row;
    }

    /**
     * Function store_pro.
     *
     * @param array $data Array of IDs.
     *
     * @return  mixed
     */
    public function store_pro($data)
    {
        $row = $this->getTable('attribute_property');

        if (!$row->bind($data)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        if (!$row->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        return $row;
    }

    /**
     * Function store_sub.
     *
     * @param array $data Array of IDs.
     *
     * @return  mixed
     */
    public function store_sub($data)
    {
        $row = $this->getTable('subattribute_property');

        if (!$row->bind($data)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        if (!$row->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        return $row;
    }

    /**
     * Function accessory_product_data.
     *
     * @param int $productId Product ID.
     *
     * @return  array
     */
    public function accessory_product_data($productId)
    {
        $query = "SELECT cp.child_product_id as product_id,
						 p.product_name,
						 cp.accessory_price as price,
						 cp.oprand,
						 p.product_price as normal_price
				  FROM " . $this->table_prefix . "product as p , " . $this->table_prefix . "product_accessory as cp
				  WHERE cp.product_id='" . $productId . "' and cp.child_product_id=p.product_id ";
        $this->_db->setQuery($query);
        $productdata = $this->_db->loadObjectList();

        return $productdata;
    }

    /**
     * Function related_product_data.
     *
     * @param int $productId Product ID.
     *
     * @return  array
     */
    public function related_product_data($productId)
    {
        $query = "SELECT cp.related_id as value,p.product_name as text
				  FROM " . $this->table_prefix . "product as p , " . $this->table_prefix . "product_related as cp
				  WHERE cp.product_id='" . $productId . "' and cp.related_id=p.product_id order by cp.ordering asc";
        $this->_db->setQuery($query);
        $productdata = $this->_db->loadObjectList();

        return $productdata;
    }

    /**
     * Function property_more_img.
     *
     * @param array $post Post.
     * @param array $main_img Main img.
     * @param array $sub_img Sub img.
     *
     * @return  mixed
     */
    public function property_more_img($post, $main_img, $sub_img)
    {
        if ($main_img['name'] != '') {
            $filetype = strtolower(JFile::getExt($main_img['name']));

            if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg') {
                return false;
            } else {
                $main_name = RedshopHelperMedia::cleanFileName($main_img['name']);
                $main_src = $main_img['tmp_name'];

                if ($post['fsec'] == 'subproperty') {
                    $main_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $main_name;

                    JFile::upload($main_src, $main_dest);

                    $query = "UPDATE " . $this->table_prefix . "product_subattribute_color SET subattribute_color_image = '" . $main_name .
                        "' WHERE subattribute_color_id ='" . $post['section_id'] . "' ";
                    $this->_db->setQuery($query);

                    if (!$this->_db->execute()) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }
                } else {
                    $main_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $main_name;

                    JFile::upload($main_src, $main_dest);

                    $query = "UPDATE " . $this->table_prefix . "product_attribute_property SET property_main_image = '" . $main_name
                        . "' WHERE property_id ='" . $post['section_id'] . "' ";
                    $this->_db->setQuery($query);

                    if (!$this->_db->execute()) {
                        /** @scrutinizer ignore-deprecated */
                        $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                        return false;
                    }
                }
            }
        }

        $num = count($sub_img['name']);

        for ($i = 0; $i < $num; $i++) {
            if ($sub_img['name'][$i] != "") {
                $filetype = strtolower(JFile::getExt($sub_img['name'][$i]));

                if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg') {
                    return false;
                } else {
                    $sub_name = RedshopHelperMedia::cleanFileName($sub_img['name'][$i]);

                    $sub_src = $sub_img['tmp_name'][$i];

                    $sub_type = $sub_img['type'][$i];

                    $sub__dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $sub_name;

                    JFile::upload($sub_src, $sub__dest);

                    $mediarow = $this->getTable('media_detail');
                    $mediapost = array();
                    $mediapost['media_id'] = 0;
                    $mediapost['media_name'] = $sub_name;
                    $mediapost['media_section'] = $post['fsec'];
                    $mediapost['section_id'] = $post['section_id'];
                    $mediapost['media_type'] = "images";
                    $mediapost['media_mimetype'] = $sub_type;
                    $mediapost['published'] = 1;

                    if (!$mediarow->bind($mediapost)) {
                        return false;
                    }

                    if (!$mediarow->store()) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Function deletesubimage.
     *
     * @param int $mediaid Media ID.
     *
     * @return  boolean
     */
    public function deletesubimage($mediaid)
    {
        $query = 'SELECT * FROM ' . $this->table_prefix . 'media  WHERE media_id = ' . $mediaid;
        $this->_db->setQuery($query);
        $imgdata = $this->_db->loadObject();

        $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $imgdata->media_name;
        $tsrc = REDSHOP_FRONT_IMAGES_RELPATH . 'property/thumb/' . $imgdata->media_name;

        if (file_exists($dest)) {
            JFile::delete($dest);
        }

        if (file_exists($tsrc)) {
            JFile::delete($tsrc);
        }

        $query = 'DELETE FROM ' . $this->table_prefix . 'media WHERE media_id = "' . $mediaid . '" ';

        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        return true;
    }

    /**
     * Function subattribute_color.
     *
     * @param array $post Post.
     * @param array $sub_img Sub img.
     *
     * @return  boolean
     */
    public function subattribute_color($post, $sub_img)
    {
        $num = count($sub_img['name']);

        for ($i = 0; $i < $num; $i++) {
            if ($sub_img['name'][$i] != "") {
                $filetype = strtolower(JFile::getExt($sub_img['name'][$i]));

                if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg') {
                    return false;
                } else {
                    $sub_name = RedshopHelperMedia::cleanFileName($sub_img['name'][$i]);

                    $sub_src = $sub_img['tmp_name'][$i];

                    $sub__dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $sub_name;

                    JFile::upload($sub_src, $sub__dest);

                    if ($post['property_sub_img_tmp'][$i] != "") {
                        $sub = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $post['property_sub_img_tmp'][$i];
                        $sub_thumb = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/thumb/' . $post['property_sub_img_tmp'][$i];

                        if (file_exists($sub)) {
                            JFile::delete($sub);
                        }

                        if (file_exists($sub_thumb)) {
                            JFile::delete($sub_thumb);
                        }
                    }

                    $subpost = array();
                    $subpost['subattribute_color_id'] = $post['subattribute_color_id'][$i];
                    $subpost['subattribute_color_name'] = $post['subattribute_name'][$i];
                    $subpost['subattribute_color_image'] = $sub_name;
                    $subpost['subattribute_id'] = $post['section_id'];
                    $this->store_sub($subpost);
                }
            } else {
                if ($post['property_sub_img_tmp'][$i] != "" && $sub_img['name'][$i] == "") {
                    $subpost = array();
                    $subpost['subattribute_color_id'] = $post['subattribute_color_id'][$i];
                    $subpost['subattribute_color_name'] = $post['subattribute_name'][$i];
                    $subpost['subattribute_color_image'] = $post['property_sub_img_tmp'][$i];
                    $subpost['subattribute_id'] = $post['section_id'];
                    $this->store_sub($subpost);
                }
            }
        }

        return true;
    }

    /**
     * Function subattr_diff.
     *
     * @param string $subattr_id ID.
     * @param int $section_id ID.
     *
     * @return  array
     */
    public function subattr_diff($subattr_id, $section_id)
    {
        $query = 'SELECT * FROM ' . $this->table_prefix . 'product_subattribute_color
				  WHERE subattribute_id = "' . $section_id . '"
				  AND subattribute_color_id NOT IN (\'' . $subattr_id . '\')
				  ORDER BY subattribute_color_id ASC';
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

    /**
     * Function delsubattr_diff.
     *
     * @param array $subattr_diff ID.
     *
     * @return  boolean
     */
    public function delsubattr_diff($subattr_diff)
    {
        foreach ($subattr_diff as $diff) {
            $sub_dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $diff->subattribute_color_image;

            if (file_exists($sub_dest)) {
                JFile::delete($sub_dest);
            }

            $query = 'DELETE FROM ' . $this->table_prefix . 'product_subattribute_color  WHERE subattribute_color_id = "' .
                $diff->subattribute_color_id . '"';
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }
        }

        return true;
    }

    /**
     * Check That redproductfinder is installed or not.
     *
     * @return  array
     */
    public function CheckRedProductFinder()
    {
        $query = "SELECT extension_id FROM `#__extensions` WHERE `element` LIKE '%com_redproductfinder%'";
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }

    /**
     * Get Association related to perticular Product
     *
     * @return array
     */
    public function getAssociation()
    {
        if (!$this->CheckRedProductFinder()) {
            return array();
        }

        $query = "SELECT * FROM `#__redproductfinder_associations` WHERE `product_id` ='" . $this->id . "'";
        $this->_db->setQuery($query);

        return $this->_db->loadObject();
    }

    /**
     * Show all tags that have been created
     *
     * @return array
     */
    public function Associations()
    {
        if (!$this->CheckRedProductFinder()) {
            return array();
        }

        // Get all the fields based on the limits

        $query = "SELECT a.*, p.product_name
			FROM #__redproductfinder_associations a, " . $this->table_prefix . "product p
			WHERE a.product_id = p.product_id
			ORDER BY a.ordering";
        $this->_db->setQuery($query);
        $products = $this->_db->loadObjectList();

        return $products;
    }

    /**
     * Get a multi-select list with types and tags
     *
     * @return array
     */
    public function TypeTagList()
    {
        if (!$this->CheckRedProductFinder()) {
            return array();
        }

        // 1. Get all types.
        $q = "SELECT id, type_name FROM #__redproductfinder_types where type_select!='Productfinder_datepicker' ORDER by ordering";
        $this->_db->setQuery($q);
        $types = $this->_db->loadAssocList('id');

        // 2. Go through each type and get the tags.
        if (count($types) > 0) {
            foreach ($types as $id => $type) {
                $q = "SELECT t.id, tag_name
					  FROM #__redproductfinder_tag_type j, #__redproductfinder_tags t
					  WHERE j.tag_id = t.id
					  AND j.type_id = '" . $id . "'
					  ORDER BY t.ordering";
                $this->_db->setQuery($q);
                $types[$id]['tags'] = $this->_db->loadAssocList('id');
            }
        }

        return $types;
    }

    /**
     * Get the list of selected type names for this tag
     *
     * @return array
     */
    public function AssociationTagNames()
    {
        if (!$this->CheckRedProductFinder()) {
            return array();
        }

        $q = "SELECT association_id, CONCAT(y.type_name, ':', g.tag_name) AS tag_name
			  FROM #__redproductfinder_association_tag a
			  LEFT JOIN #__redproductfinder_tags g ON a.tag_id = g.id
			  LEFT JOIN #__redproductfinder_types y ON a.type_id = y.id";
        $this->_db->setQuery($q);
        $list = $this->_db->loadObjectList();
        $sortlist = array();

        if (count($list) > 0) {
            foreach ($list as $tag) {
                $sortlist[$tag->association_id][] = $tag->tag_name;
            }
        }

        return $sortlist;
    }

    /**
     * Show all tags that have been created.
     *
     * @return array
     */
    public function Tags()
    {
        if (!$this->CheckRedProductFinder()) {
            return array();
        }

        // Get all the fields based on the limits

        $query = "SELECT t.* FROM #__redproductfinder_tags t
				  LEFT JOIN #__redproductfinder_tag_type y ON t.id = y.tag_id
				  GROUP BY t.id
				  ORDER BY t.ordering";
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

    /**
     * Get the list of selected types for this tag.
     *
     * @param int $id ID.
     *
     * @return  array
     */
    public function AssociationTags($id)
    {
        if (!$this->CheckRedProductFinder()) {
            return array();
        }

        if (!$id) {
            return array();
        } else {
            $q = "SELECT tag_id
				  FROM #__redproductfinder_association_tag
				  WHERE association_id = '" . $id . "' ";
            $this->_db->setQuery($q);

            return $this->_db->loadColumn();
        }
    }

    /**
     * Save an association.
     *
     * @param int $productId ID.
     * @param array $post $_POST.
     *
     * @return  boolean|array
     */
    public function SaveAssociations($productId, $post)
    {
        if (!$this->CheckRedProductFinder()) {
            return array();
        }

        $row = $this->getTable('associations');

        // Get the posted data.
        $association = array();
        $association['id'] = $post['association_id'];
        $association['published'] = 1;
        $association['checked_out'] = 0;
        $association['checked_out_time'] = '0000-00-00 00:00:00';
        $association['ordering'] = 1;
        $association['product_id'] = $productId;

        if (!$row->bind($association)) {
            return false;
        }

        // Save the changes.
        if (!$row->store()) {
            return false;
        } else {
            // Delete all tag type relations.
            $q = "DELETE FROM #__redproductfinder_association_tag
		  		  WHERE association_id = '" . $row->id . "' ";
            $this->_db->setQuery($q);
            $this->_db->execute();

            // Store the tag type relations.
            $tags = $this->input->get('tag_id', array(), 'array');
            $qs = $this->input->get('qs_id', array(), 'array');

            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    // Split tag to type ID and tag ID.
                    list($type_id, $tag_id) = explode('.', $tag);

                    if (empty($qs[$type_id . '.' . $tag_id])) {
                        $qs_id = 0;
                    } else {
                        $qs_id = $qs[$type_id . '.' . $tag_id];
                    }

                    $q = "INSERT IGNORE INTO #__redproductfinder_association_tag
				  		  VALUES (" . $row->id . "," . $tag_id . "," . $type_id . ",'" . $qs_id . "')";
                    $this->_db->setQuery($q);
                    $this->_db->execute();
                }
            }
        }

        $row->reorder();

        return true;
    }

    /**
     * Get all Quality Score values.
     *
     * @return array
     */
    public function getQualityScores()
    {
        if (empty($this->CheckRedProductFinder())) {
            return array();
        }

        $association = $this->getAssociation();

        if (!empty($association)) {
            $query = "SELECT CONCAT(type_id,'.',tag_id) AS qs_id, quality_score
					  FROM #__redproductfinder_association_tag WHERE association_id = '" . $association->id . "' ";
            $this->_db->setQuery($query);

            return $this->_db->loadAssocList('qs_id');
        }

        return array();
    }

    /**
     * Delete a product.
     *
     * @param array $cid ID.
     *
     * @return  array|boolean
     */
    public function RemoveAssociation($cid)
    {
        $asscid = array();

        if (empty($this->CheckRedProductFinder())) {
            return array();
        }

        $database = JFactory::getDbo();

        if (count($cid)) {
            $cids = 'product_id=' . implode(' OR product_id=', $cid);

            $q = "SELECT id FROM #__redproductfinder_associations WHERE (" . $cids . ")";
            $database->setQuery($q);
            $asso = $database->loadObjectList();

            foreach ($asso as $newasso) {
                $asscid[] = $newasso->id;
            }

            $query = "DELETE FROM #__redproductfinder_associations WHERE (" . $cids . ")";
            $database->setQuery($query);

            if (!$database->execute()) {
            } else {
                // Now remove the type associations

                $cids = 'association_id=' . implode(' OR association_id=', $asscid);
                $query = "DELETE FROM #__redproductfinder_association_tag WHERE (" . $cids . ")";
                $database->setQuery($query);
                $database->execute();
            }
        }

        return true;
    }

    /**
     * Get dependent tags.
     *
     * @param int $productId ID.
     * @param int $type_id ID.
     * @param int $tag_id ID.
     *
     * @return array
     */
    public function getDependenttag($productId = 0, $type_id = 0, $tag_id = 0)
    {
        $where = " product_id='" . $productId . "'";
        $where .= " AND type_id='" . $type_id . "'";
        $where .= " AND tag_id='" . $tag_id . "'";
        $query = "SELECT dependent_tags FROM #__redproductfinder_dependent_tag WHERE " . $where;
        $this->_db->setQuery($query);
        $rs = $this->_db->loadResult();

        return explode(",", $rs);
    }

    /**
     * Getting the list of StockRoom.
     *
     * @return array
     */
    public function StockRoomList()
    {
        $database = JFactory::getDbo();

        $q = "SELECT * FROM " . $this->table_prefix . "stockroom WHERE published = 1";
        $database->setQuery($q);
        $arrStockrooms = $database->loadObjectList();

        return $arrStockrooms;
    }

    /**
     * Getting the  StockRoom Product Quantity.
     *
     * @param int $pid ID.
     * @param int $sid ID.
     *
     * @return  int
     */
    public function StockRoomProductQuantity($pid, $sid)
    {
        $database = JFactory::getDbo();

        $q = "SELECT `quantity` FROM `" . $this->table_prefix . "product_stockroom_xref`
			  WHERE `product_id` = '" . $pid . "'
			  AND `stockroom_id` = '" . $sid . "' ";
        $database->setQuery($q);
        $quantity = $database->loadResult();

        return $quantity;
    }

    /**
     * Getting the  StockRoom Product Quantity.
     *
     * @param int $pid ID.
     * @param int $sid ID.
     * @param int $section ID.
     *
     * @return  integer
     */
    public function StockRoomAttProductQuantity($pid, $sid, $section)
    {
        $database = JFactory::getDbo();

        $q = "SELECT `quantity` FROM `" . $this->table_prefix . "product_attribute_stockroom_xref`
			  WHERE `section_id` = '" . $pid . "'
			  AND `stockroom_id` = '" . $sid . "'
			  AND section = '" . $section . "'";
        $database->setQuery($q);
        $quantity = $database->loadResult();

        return $quantity;
    }

    /**
     * StockRoomAttProductPreorderstock.
     *
     * @param int $pid ID.
     * @param int $sid ID.
     * @param int $section ID.
     *
     * @return  array
     */
    public function StockRoomAttProductPreorderstock($pid, $sid, $section)
    {
        $database = JFactory::getDbo();

        $q = "SELECT `preorder_stock`, `ordered_preorder`
			  FROM `" . $this->table_prefix . "product_attribute_stockroom_xref`
			  WHERE `section_id` = '" . $pid . "' and `stockroom_id` = '" . $sid . "'
			  AND section = '" . $section . "'";
        $database->setQuery($q);
        $preorder_stock_data = $database->loadObjectList();

        return $preorder_stock_data;
    }

    /**
     * Getting Preorder Stock Quantity.
     *
     * @param int $pid ID.
     * @param int $sid ID.
     *
     * @return  array
     */
    public function StockRoomPreorderProductQuantity($pid, $sid)
    {
        $database = JFactory::getDbo();

        $q = "SELECT `preorder_stock`, `ordered_preorder`  FROM `" . $this->table_prefix . "product_stockroom_xref`
		WHERE `product_id` = '" . $pid . "' and `stockroom_id` = '" . $sid . "' ";
        $database->setQuery($q);
        $preorder_stock_data = $database->loadObjectList();

        return $preorder_stock_data;
    }

    /**
     * Store stockroom product xref.
     *
     * @param int $pid ID.
     * @param array $post Post.
     *
     * @return  boolean
     */
    public function SaveStockroom($pid, $post)
    {
        $database = JFactory::getDbo();
        $query = "DELETE FROM " . $this->table_prefix . "product_stockroom_xref"
            . "\n  WHERE product_id = '" . $pid . "' ";

        $database->setQuery($query);

        if (!$database->execute()) {
            return false;
        } else {
            for ($i = 0, $countQuantity = count($post['quantity']); $i < $countQuantity; $i++) {
                $this->insertProductStock($pid, $post['stockroom_id'][$i], $post['quantity'][$i]);
            }
        }

        return true;
    }

    /**
     * Function attribute_empty.
     *
     * @return  boolean
     */
    public function attribute_empty()
    {
        $database = JFactory::getDbo();

        if ($this->id) {
            $attributes = \Redshop\Product\Attribute::getProductAttribute($this->id);

            for ($i = 0, $in = count($attributes); $i < $in; $i++) {
                $query = "DELETE FROM `" . $this->table_prefix . "product_attribute` WHERE `attribute_id` = '"
                    . $attributes[$i]->attribute_id . "' ";
                $database->setQuery($query);

                if ($database->execute()) {
                    $property = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attributes[$i]->attribute_id);

                    for ($j = 0, $jn = count($property); $j < $jn; $j++) {
                        $query = "DELETE FROM `" . $this->table_prefix . "product_attribute_property` WHERE `property_id` = '"
                            . $property[$j]->property_id . "' ";
                        $database->setQuery($query);

                        if ($database->execute()) {
                            $query = "DELETE FROM `" . $this->table_prefix . "product_subattribute_color` WHERE `subattribute_id` = '"
                                . $property[$j]->property_id . "' ";
                            $database->setQuery($query);
                            $database->execute();
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Remove property image.
     *
     * @param int $pid ID.
     *
     * @return  boolean
     */
    public function removepropertyImage($pid)
    {
        $query = "SELECT property_image  FROM `" . $this->table_prefix . "product_attribute_property` WHERE  property_id = '" . $pid . "' ";
        $this->_db->setQuery($query);
        $image = $this->_db->LoadObject();
        $imagename = $image->property_image;

        $imagethumbsrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/thumb/" . $imagename;

        if (JFile::exists($imagethumbsrcphy)) {
            JFile::delete($imagethumbsrcphy);
        }

        $imagesrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $imagename;

        if (JFile::exists($imagesrcphy)) {
            JFile::delete($imagesrcphy);
        }

        $query = "UPDATE `" . $this->table_prefix . "product_attribute_property` SET `property_image` = '' WHERE `property_id` = '" . $pid . "' ";
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            return false;
        }

        return true;
    }

    /**
     * Function removesubpropertyImage.
     *
     * @param int $pid ID.
     *
     * @return  boolean
     */
    public function removesubpropertyImage($pid)
    {
        $query = "SELECT subattribute_color_image
				  FROM `" . $this->table_prefix . "product_subattribute_color`
				  WHERE  subattribute_color_id = '" . $pid . "' ";
        $this->_db->setQuery($query);
        $image = $this->_db->LoadObject();
        $imagename = $image->subattribute_color_image;

        $imagethumbsrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/thumb/" . $imagename;

        if (JFile::exists($imagethumbsrcphy)) {
            JFile::delete($imagethumbsrcphy);
        }

        $imagesrcphy = REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $imagename;

        if (JFile::exists($imagesrcphy)) {
            JFile::delete($imagesrcphy);
        }

        $query = "UPDATE `" . $this->table_prefix . "product_subattribute_color`
				  SET `subattribute_color_image` = ''
				  WHERE `subattribute_color_id` = '" . $pid . "' ";
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            return false;
        }

        return true;
    }

    /**
     * Function getQuantity.
     *
     * @param string $stockroom_type Type.
     * @param int $sid ID.
     * @param int $pid ID.
     *
     * @return  array
     */
    public function getQuantity($stockroom_type, $sid, $pid)
    {
        $product = " AND product_id='" . $pid . "' ";
        $section = "";
        $stock = "";
        $table = "product";

        if ($stockroom_type != 'product') {
            $product = " AND section_id='" . $pid . "' ";
            $section = " AND section = '" . $stockroom_type . "' ";
            $table = "product_attribute";
        }

        if ($sid != 0) {
            $stock = "AND stockroom_id='" . $sid . "' ";
        }

        $query = "SELECT * FROM " . $this->table_prefix . $table . "_stockroom_xref
				  WHERE 1=1 " . $stock . $product . $section;

        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectlist();

        return $list;
    }

    /**
     * Function SaveAttributeStockroom.
     *
     * @param array $post Type.
     *
     * @return  array|boolean
     */
    public function SaveAttributeStockroom($post)
    {
        $product = " AND section_id='" . $post['section_id'] . "' ";
        $section = " AND section = '" . $post['section'] . "' ";
        $table = "product_attribute";

        for ($i = 0, $countQuantity = count($post['quantity']); $i < $countQuantity; $i++) {
            $preorder_stock = $post['preorder_stock'][$i];
            $ordered_preorder = $post['ordered_preorder'][$i];
            $sid = $post['stockroom_id'][$i];
            $quantity = $post['quantity'][$i];
            $stock_update = false;
            $list = $this->getQuantity($post['section'], $sid, $post['section_id']);

            if ($list[0]->section_id == 0) {
                $list = array();
            }

            if (count($list) > 0) {
                if ($quantity == "" && Redshop::getConfig()->get('USE_BLANK_AS_INFINITE')) {
                    $query = "DELETE FROM " . $this->table_prefix . $table . "_stockroom_xref
							  WHERE stockroom_id='" . $post['stockroom_id'][$i] . "' " . $product . $section;
                    $this->_db->setQuery($query);
                    $this->_db->execute();
                } else {
                    if (($preorder_stock < $ordered_preorder) && $preorder_stock != "" && $ordered_preorder != "") {
                        $this->app->enqueueMessage(JText::_('COM_REDSHOP_PREORDER_STOCK_NOT_ALLOWED'), 'notice');

                        return false;
                    } else {
                        $query = "UPDATE " . $this->table_prefix . $table . "_stockroom_xref
								  SET quantity='" . $quantity . "' , preorder_stock= '" . $preorder_stock . "'
								  WHERE stockroom_id='" . $sid . "'" . $product . $section;
                        $this->_db->setQuery($query);
                        $this->_db->execute();
                        $stock_update = true;
                    }
                }
            } else {
                if ($preorder_stock < $ordered_preorder && $preorder_stock != "" && $ordered_preorder != "") {
                    $msg = JText::_('COM_REDSHOP_PREORDER_STOCK_NOT_ALLOWED');
                    /** @scrutinizer ignore-deprecated */
                    JError::raiseWarning('', $msg);

                    return false;
                } else {
                    if (Redshop::getConfig()->get('USE_BLANK_AS_INFINITE')) {
                        $this->InsertStockroom(
                            $post['section_id'],
                            $post['section'],
                            $post['stockroom_id'][$i],
                            $post['quantity'][$i],
                            $preorder_stock,
                            $ordered_preorder
                        );

                        $stock_update = true;
                    } elseif ($preorder_stock != "" || $quantity != "") {
                        $this->InsertStockroom(
                            $post['section_id'],
                            $post['section'],
                            $post['stockroom_id'][$i],
                            (int)$post['quantity'][$i],
                            (int)$preorder_stock,
                            $ordered_preorder
                        );

                        $stock_update = true;
                    }
                }
            }

            if ($stock_update) {
                // For stockroom Notify Email.
                $stockroom_data = array();
                $stockroom_data['section'] = $post['section'];
                $stockroom_data['section_id'] = $post['section_id'];
                $stockroom_data['regular_stock'] = $quantity;
                $stockroom_data['preorder_stock'] = $preorder_stock;

                JPluginHelper::importPlugin('redshop_product');
                $dispatcher = RedshopHelperUtility::getDispatcher();
                $dispatcher->trigger('onAfterUpdateStock', array($stockroom_data));
            }
        }

        return true;
    }

    /**
     * Function getVatGroup.
     *
     * @return  array
     */
    public function getVatGroup()
    {
        $query = "SELECT tg.name as text, tg.id as value FROM `" . $this->table_prefix . "tax_group` as tg
				  WHERE tg.published = 1
				  ORDER BY tg.id ASC";
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

    /**
     * Save product ordering.
     *
     * @param array $cid IDs.
     * @param array $order Order.
     *
     * @return boolean
     */
    public function saveorder($cid = array(), $order = array())
    {
        // Get global category id.
        $category_id_my = $this->app->getUserStateFromRequest('category_id', 'category_id', 0);

        // Init array.
        $orderarray = array();

        for ($i = 0, $in = count($cid); $i < $in; $i++) {
            // Set product id as key AND order as value.
            $orderarray[$cid[$i]] = $order[$i];
        }

        // Sorting array using value (order).
        asort($orderarray);
        $i = 1;

        if (count($orderarray) > 0) {
            foreach ($orderarray as $productid => $order) {
                if ($order >= 0) {
                    // Update ordering.
                    $query = 'UPDATE ' . $this->table_prefix . 'product_category_xref
					 		  SET ordering = ' . (int)$i . '
					 		  WHERE product_id=' . $productid . '
					 		  AND category_id = ' . $category_id_my;
                    $this->_db->setQuery($query);
                    $this->_db->execute();
                }

                $i++;
            }
        }

        return true;
    }

    /**
     * Orderup.
     *
     * @return void
     */
    public function orderup()
    {
        $category_id_my = $this->app->getUserStateFromRequest('category_id', 'category_id', 0);
        $cid = $this->input->post->get('cid', array(), 'array');
        $cid = $cid[0];

        $q = "SELECT ordering,category_id," . $this->table_prefix . "product.product_id
			  FROM " . $this->table_prefix . "product," . $this->table_prefix . "product_category_xref ";
        $q .= "WHERE " . $this->table_prefix . "product_category_xref.product_id='" . $cid . "' ";
        $q .= "AND " . $this->table_prefix . "product_category_xref.category_id='" . $category_id_my . "' ";
        $q .= "AND " . $this->table_prefix . "product_category_xref.product_id = " . $this->table_prefix . "product.product_id";
        echo '<br/>';

        $this->_db->setQuery($q);
        $cat = $this->_db->loadObject();

        $currentpos = $cat->ordering;
        $category_id = $cat->category_id;

        $q = "SELECT " . $this->table_prefix . "product.product_id
			  FROM " . $this->table_prefix . "product, " . $this->table_prefix . "product_category_xref ";
        $q .= "WHERE " . $this->table_prefix . "product_category_xref.category_id='" . $category_id . "' ";
        $q .= "AND " . $this->table_prefix . "product_category_xref.product_id=" . $this->table_prefix . "product.product_id
			   AND category_id= '" . $category_id_my . "' ";
        $q .= "AND " . $this->table_prefix . "product_category_xref.ordering='" . intval($currentpos - 1) . "'";
        $this->_db->setQuery($q);
        $cat = $this->_db->loadObject();

        $pred = $cat->product_id;

        $q = "UPDATE " . $this->table_prefix . "product_category_xref ";
        $q .= "SET ordering=ordering-1 ";
        $q .= "WHERE product_id='" . $cid . "' AND ordering >1 AND category_id = '" . $category_id_my . "' ";
        $this->_db->setQuery($q);
        $this->_db->execute();

        $q = "UPDATE " . $this->table_prefix . "product_category_xref ";
        $q .= "SET ordering=ordering+1 ";
        $q .= "WHERE product_id='" . $pred . "' AND category_id = '" . $category_id_my . "' ";
        $this->_db->setQuery($q);
        $this->_db->execute();
    }

    /**
     * Orderdown.
     *
     * @return void
     */
    public function orderdown()
    {
        $category_id_my = $this->app->getUserStateFromRequest('category_id', 'category_id', 0);
        $cid = $this->input->post->get('cid', array(), 'array');
        $cid = $cid[0];

        $q = "SELECT ordering,category_id," . $this->table_prefix . "product.product_id
			  FROM " . $this->table_prefix . "product," . $this->table_prefix . "product_category_xref ";
        $q .= "WHERE " . $this->table_prefix . "product_category_xref.product_id='" . $cid . "' ";
        $q .= "AND " . $this->table_prefix . "product_category_xref.category_id='" . $category_id_my . "' ";
        $q .= "AND " . $this->table_prefix . "product_category_xref.product_id = " . $this->table_prefix . "product.product_id";
        $this->_db->setQuery($q);
        $cat = $this->_db->loadObject();
        $currentpos = $cat->ordering;
        $category_id = $cat->category_id;

        $q = "SELECT ordering," . $this->table_prefix . "product.product_id
			  FROM " . $this->table_prefix . "product, " . $this->table_prefix . "product_category_xref ";
        $q .= "WHERE " . $this->table_prefix . "product_category_xref.category_id='" . $category_id . "' ";
        $q .= "AND " . $this->table_prefix . "product_category_xref.product_id=" . $this->table_prefix . "product.product_id
			   AND category_id= '" . $category_id_my . "'";
        $q .= "AND ordering='" . intval($currentpos + 1) . "'";
        $this->_db->setQuery($q);
        $cat = $this->_db->loadObject();
        $succ = $cat->product_id;

        $q = "UPDATE " . $this->table_prefix . "product_category_xref ";
        $q .= "SET ordering=ordering+1 ";
        $q .= "WHERE product_id='" . $cid . "' AND category_id = '" . $category_id_my . "'  ";
        $this->_db->setQuery($q);
        $this->_db->execute();

        $q = "UPDATE " . $this->table_prefix . "product_category_xref ";
        $q .= "SET ordering=ordering-1 ";
        $q .= "WHERE product_id='" . $succ . "' AND category_id = '" . $category_id_my . "' ";
        $this->_db->setQuery($q);
        $this->_db->execute();
    }

    /**
     * Function getDiscountCalcData.
     *
     * @return array
     */
    public function getDiscountCalcData()
    {
        $query = "SELECT * FROM `" . $this->table_prefix . "product_discount_calc`
				  WHERE product_id = '" . $this->id . "' ORDER BY area_start ";

        return $this->_getList($query);
    }

    /**
     * Function getDiscountCalcDataExtra.
     *
     * @return array
     */
    public function getDiscountCalcDataExtra()
    {
        $query = "SELECT * FROM `" . $this->table_prefix . "product_discount_calc_extra`
				  WHERE product_id = '" . $this->id . "' ORDER BY option_name ";

        return $this->_getList($query);
    }

    /**
     * Product subscription detail.
     *
     * @return array
     */
    public function getSubscription()
    {
        $query = "SELECT * FROM `" . $this->table_prefix . "product_subscription`
				  WHERE product_id = '" . $this->id . "' order by subscription_id";

        return $this->_getList($query);
    }

    /**
     * Function getSubscriptionrenewal.
     *
     * @return array
     */
    public function getSubscriptionrenewal()
    {
        $query = "SELECT * FROM `" . $this->table_prefix . "subscription_renewal`
				  WHERE product_id ='" . $this->id . "' ";

        return $this->_getList($query);
    }

    /**
     * Function getAttributeSetList.
     *
     * @return array
     */
    public function getAttributeSetList()
    {
        $query = "SELECT attribute_set_id as value,	attribute_set_name as text FROM `" . $this->table_prefix . "attribute_set`
				  WHERE published  = 1";
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

    /**
     *  Get serial numbers for downloadable products.
     *
     * @param bool $only_used IDs.
     *
     * @return  array
     */
    public function getProdcutSerialNumbers($only_used = false)
    {
        $usedCond = $this->_db->qn('is_used') . ' = 0';

        if ($only_used) {
            $usedCond = $this->_db->qn('is_used') . ' = 1';
        }

        $query = $this->_db->getQuery(true)
            ->select('*')
            ->from($this->_db->qn($this->table_prefix . "product_serial_number"))
            ->where($this->_db->qn('product_id') . ' = ' . $this->id)
            ->where($usedCond);

        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

    /**
     *  Function deleteProdcutSerialNumbers.
     *
     * @param int $serial_id ID.
     *
     * @return  boolean
     */
    public function deleteProdcutSerialNumbers($serial_id)
    {
        $query = "DELETE FROM " . $this->table_prefix . "product_serial_number
				  WHERE serial_id = '" . $serial_id . "'";
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            return false;
        }

        return true;
    }

    /**
     *  Function deleteProdcutSerialNumbers.
     *
     * @param int $id ID.
     * @param string $type ID.
     *
     * @return  array
     */
    public function getImageInfor($id, $type)
    {
        $image_media = 'SELECT * FROM ' . $this->table_prefix . 'media
						WHERE section_id = "' . $id . '"
						AND media_section = "' . $type . '" ';
        $this->_db->setQuery($image_media);

        return $this->_db->loadObjectlist();
    }

    /**
     *  Function copyadditionalImage.
     *
     * @param array $data Data.
     *
     * @return  boolean
     */
    public function copyadditionalImage($data, $isMainImage = false, $mainImageName = "")
    {
        $src_image = $data['media_name'];

        if ($isMainImage) {
            $new_imgname = $mainImageName;
        } else {
            $old_imgname = strstr($data['media_name'], '_') ? strstr($data['media_name'], '_') : $data['media_name'];
            $new_imgname = RedshopHelperMedia::cleanFileName($old_imgname);
        }

        $data['media_name'] = $new_imgname;
        $data['media_id '] = 0;
        $rowmedia = $this->getTable('media_detail');

        if (!$rowmedia->bind($data)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        if (!$rowmedia->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        if (!$isMainImage) {
            $section = $data['media_section'];
            $path = $section . '/' . $src_image;
            $this->copy_image_additionalimage_from_path($path, $data['media_section']);
        }

        return true;
    }

    /**
     *  Function copy_image_additionalimage_from_path.
     *
     * @param string $imagePath Image path.
     * @param int $section ID.
     *
     * @return  string
     */
    public function copy_image_additionalimage_from_path($imagePath, $section)
    {
        $src = REDSHOP_FRONT_IMAGES_RELPATH . $imagePath;

        $imgname = basename($imagePath);
        $imgname = strstr($imgname, '_') ? strstr($imgname, '_') : $imgname;
        $propertyimage = RedshopHelperMedia::cleanFileName($imgname);
        $dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $propertyimage;

        copy($src, $dest);

        return $propertyimage;
    }

    /**
     *  Function copyAttributeSetAttribute.
     *
     * @param int $attribute_set_id ID.
     * @param int $productId ID.
     *
     * @return  array
     */
    public function copyAttributeSetAttribute($attribute_set_id, $productId)
    {
        $query = 'SELECT * FROM ' . $this->table_prefix . 'product_attribute WHERE attribute_set_id ="' . $attribute_set_id . '" ';
        $this->_db->setQuery($query);
        $attribute = $this->_db->loadObjectList();

        for ($att = 0, $countAttribute = count($attribute); $att < $countAttribute; $att++) {
            $attpost = array();
            $attpost['attribute_id'] = 0;
            $attpost['attribute_name'] = $attribute[$att]->attribute_name;
            $attpost['attribute_required'] = $attribute[$att]->attribute_required;
            $attpost['product_id'] = $productId;
            $attpost['ordering'] = $attribute[$att]->ordering;
            $attpost['allow_multiple_selection'] = $attribute[$att]->allow_multiple_selection;
            $attpost['hide_attribute_price'] = $attribute[$att]->hide_attribute_price;
            $attpost['display_type'] = $attribute[$att]->display_type;
            $attpost['attribute_published'] = $attribute[$att]->attribute_published;
            $attrow = $this->store_attr($attpost);
            $attributeId = $attrow->attribute_id;

            $query = 'SELECT * FROM `' . $this->table_prefix . 'product_attribute_property`
					  WHERE `attribute_id` = "' . $attribute[$att]->attribute_id . '" ';
            $this->_db->setQuery($query);
            $att_property = $this->_db->loadObjectList();

            for ($prop = 0, $countProperty = count($att_property); $prop < $countProperty; $prop++) {
                $listImages = $this->getImageInfor($att_property[$prop]->property_id, 'property');
                $listStockroomData = $this->GetStockroomData($att_property[$prop]->property_id, 'property');
                $listAttributepriceData = $this->GetAttributepriceData($att_property[$prop]->property_id, 'property');

                if ($att_property[$prop]->property_image) {
                    $image_split = $att_property[$prop]->property_image;

                    // Make the filename unique.
                    $filename = RedshopHelperMedia::cleanFileName($image_split);
                    $att_property[$prop]->property_image = $filename;
                    $src = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $image_split;
                    $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $filename;
                    copy($src, $dest);
                }

                if ($att_property[$prop]->property_main_image) {
                    $prop_main_img = $att_property[$prop]->property_main_image;
                    $image_split = $att_property[$prop]->property_main_image;
                    $image_split = explode('_', $image_split);
                    $image_split = $image_split[1];

                    // Make the filename unique.
                    $filename = RedshopHelperMedia::cleanFileName($image_split);
                    $att_property[$prop]->property_main_image = $filename;
                    $src = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $prop_main_img;
                    $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $filename;
                    copy($src, $dest);
                }

                $proppost = array();
                $proppost['property_id'] = 0;
                $proppost['attribute_id'] = $attributeId;
                $proppost['property_name'] = $att_property[$prop]->property_name;
                $proppost['property_price'] = $att_property[$prop]->property_price;
                $proppost['oprand'] = $att_property[$prop]->oprand;
                $proppost['property_image'] = $att_property[$prop]->property_image;
                $proppost['property_main_image'] = $att_property[$prop]->property_main_image;
                $proppost['ordering'] = $att_property[$prop]->ordering;
                $proppost['setdefault_selected'] = $att_property[$prop]->setdefault_selected;
                $proppost['setrequire_selected'] = $att_property[$prop]->setrequire_selected;
                $proppost['setdisplay_type'] = $att_property[$prop]->setdisplay_type;
                $proppost['extra_field'] = $att_property[$prop]->extra_field;
                $proppost['property_published'] = $att_property[$prop]->property_published;
                $proppost['property_number'] = $att_property[$prop]->property_number;
                $proprow = $this->store_pro($proppost);
                $propertyId = $proprow->property_id;

                for ($ls = 0, $countStockroom = count($listStockroomData); $ls < $countStockroom; $ls++) {
                    $this->InsertStockroom($propertyId, 'property', $listStockroomData[$ls]->stockroom_id, $listStockroomData[$ls]->quantity, 0, 0);
                }

                $countAttributePrice = count($listAttributepriceData);

                for ($lp = 0; $lp < $countAttributePrice; $lp++) {
                    $this->InsertAttributeprice(
                        $propertyId,
                        'property',
                        $listAttributepriceData[$lp]->product_price,
                        $listAttributepriceData[$lp]->product_currency,
                        $listAttributepriceData[$lp]->shopper_group_id,
                        $listAttributepriceData[$lp]->price_quantity_start,
                        $listAttributepriceData[$lp]->price_quantity_end,
                        $listAttributepriceData[$lp]->discount_price,
                        $listAttributepriceData[$lp]->discount_start_date,
                        $listAttributepriceData[$lp]->discount_end_date
                    );
                }

                for ($li = 0, $countImage = count($listImages); $li < $countImage; $li++) {
                    $mImages = array();
                    $mImages['media_name'] = $listImages[$li]->media_name;
                    $mImages['media_alternate_text'] = $listImages[$li]->media_alternate_text;
                    $mImages['media_section'] = 'property';
                    $mImages['section_id'] = $propertyId;
                    $mImages['media_type'] = 'images';
                    $mImages['media_mimetype'] = $listImages[$li]->media_mimetype;
                    $mImages['published'] = $listImages[$li]->published;
                    $this->copyadditionalImage($mImages);
                }

                $query = 'SELECT * FROM ' . $this->table_prefix . 'product_subattribute_color
						  WHERE `subattribute_id` =  "' . $att_property[$prop]->property_id . '" ';
                $this->_db->setQuery($query);
                $subatt_property = $this->_db->loadObjectList();
                $countSuboproperty = count($subatt_property);

                for ($subprop = 0; $subprop < $countSuboproperty; $subprop++) {
                    $listsubpropImages = $this->getImageInfor($subatt_property[$subprop]->subattribute_color_id, 'subproperty');
                    $listSubStockroomData = $this->GetStockroomData($subatt_property[$subprop]->subattribute_color_id, 'subproperty');
                    $listSubAttributepriceData = $this->GetAttributepriceData($subatt_property[$subprop]->subattribute_color_id, 'subproperty');

                    if ($subatt_property[$subprop]->subattribute_color_image) {
                        $image_split = $subatt_property[$subprop]->subattribute_color_image;

                        // Make the filename unique.
                        $filename = RedshopHelperMedia::cleanFileName($image_split);
                        $subatt_property[$subprop]->subattribute_color_image = $filename;
                        $src = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $image_split;
                        $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $filename;
                        copy($src, $dest);
                    }

                    if ($subatt_property[$subprop]->subattribute_color_main_image) {
                        $sub_main_img = $subatt_property[$subprop]->subattribute_color_main_image;
                        $image_split = $subatt_property[$subprop]->subattribute_color_main_image;
                        $image_split = explode('_', $image_split);
                        $image_split = $image_split[1];

                        // Make the filename unique.
                        $filename = RedshopHelperMedia::cleanFileName($image_split);

                        $subatt_property[$subprop]->subattribute_color_main_image = $filename;
                        $src = REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $sub_main_img;
                        $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $filename;
                        copy($src, $dest);
                    }

                    $subpost = array();
                    $subpost['subattribute_color_id'] = 0;
                    $subpost['subattribute_color_name'] = $subatt_property[$subprop]->subattribute_color_name;
                    $subpost['subattribute_color_title'] = htmlspecialchars($subatt_property[$subprop]->subattribute_color_title);
                    $subpost['subattribute_color_price'] = $subatt_property[$subprop]->subattribute_color_price;
                    $subpost['oprand'] = $subatt_property[$subprop]->oprand;
                    $subpost['ordering'] = $subatt_property[$subprop]->ordering;
                    $subpost['subattribute_color_image'] = $subatt_property[$subprop]->subattribute_color_image;
                    $subpost['subattribute_id'] = $propertyId;
                    $subpost['setdefault_selected'] = $subatt_property[$subprop]->setdefault_selected;
                    $subpost['subattribute_color_main_image'] = $subatt_property[$subprop]->subattribute_color_main_image;
                    $subpost['subattribute_color_number'] = $subatt_property[$subprop]->subattribute_color_number;
                    $subpost['extra_field'] = $subatt_property[$subprop]->extra_field;
                    $subpost['subattribute_published'] = $subatt_property[$subprop]->subattribute_published;
                    $subrow = $this->store_sub($subpost);
                    $subPropertyId = $subrow->subattribute_color_id;
                    $countSubPropertyImage = count($listsubpropImages);

                    for ($lsi = 0; $lsi < $countSubPropertyImage; $lsi++) {
                        $smImages = array();
                        $smImages['media_name'] = $listsubpropImages[$lsi]->media_name;
                        $smImages['media_alternate_text'] = $listsubpropImages[$lsi]->media_alternate_text;
                        $smImages['media_section'] = 'subproperty';
                        $smImages['section_id'] = $subPropertyId;
                        $smImages['media_type'] = 'images';
                        $smImages['media_mimetype'] = $listsubpropImages[$lsi]->media_mimetype;
                        $smImages['published'] = $listsubpropImages[$lsi]->published;
                        $this->copyadditionalImage($smImages);
                    }

                    $countSubStockroom = count($listSubStockroomData);

                    for ($lss = 0; $lss < $countSubStockroom; $lss++) {
                        $this->InsertStockroom(
                            $subPropertyId,
                            'subproperty',
                            $listSubStockroomData[$lss]->stockroom_id,
                            $listSubStockroomData[$lss]->quantity,
                            0,
                            0
                        );
                    }

                    $countSubAttributePrice = count($listSubAttributepriceData);

                    for ($lsp = 0; $lsp < $countSubAttributePrice; $lsp++) {
                        $this->InsertAttributeprice(
                            $subPropertyId,
                            'subproperty',
                            $listSubAttributepriceData[$lsp]->product_price,
                            $listSubAttributepriceData[$lsp]->product_currency,
                            $listSubAttributepriceData[$lsp]->shopper_group_id,
                            $listSubAttributepriceData[$lsp]->price_quantity_start,
                            $listSubAttributepriceData[$lsp]->price_quantity_end,
                            $listSubAttributepriceData[$lsp]->discount_price,
                            $listSubAttributepriceData[$lsp]->discount_start_date,
                            $listSubAttributepriceData[$lsp]->discount_end_date
                        );
                    }
                }
            }
        }
    }

    /**
     * Function GetStockroomData.
     *
     * @param int $section_id ID.
     * @param string $name ID.
     *
     * @return  array
     */
    public function GetStockroomData($section_id, $name)
    {
        $query = 'SELECT * FROM ' . $this->table_prefix . 'product_attribute_stockroom_xref
				  WHERE `section_id` =  "' . $section_id . '"
				  AND section="' . $name . '" ';
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectList();

        return $list;
    }

    /**
     * Function insertProductStock.
     *
     * @param int $productId product_id
     * @param int $stockroom_id stockroom_id
     * @param int $quantiy quantiy
     * @param int $preorder_stock preorder_stock
     * @param int $ordered_preorder ordered_preorder
     *
     * @return  boolean
     */
    public function insertProductStock($productId, $stockroom_id, $quantiy = 0, $preorder_stock = 0, $ordered_preorder = 0)
    {
        $query = 'INSERT INTO ' . $this->table_prefix . 'product_stockroom_xref (product_id,stockroom_id,quantity,preorder_stock,ordered_preorder)
				  VALUE("' . $productId . '","' . $stockroom_id . '","' . $quantiy . '","' . $preorder_stock . '","' . $ordered_preorder . '")';
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        // For stockroom Notify Email.
        $stockroom_data = array();
        $stockroom_data['section'] = "product";
        $stockroom_data['section_id'] = $productId;
        $stockroom_data['regular_stock'] = $quantiy;
        $stockroom_data['preorder_stock'] = $preorder_stock;

        JPluginHelper::importPlugin('redshop_product');
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $dispatcher->trigger('onAfterUpdateStock', array($stockroom_data));

        return true;
    }

    /**
     * Function InsertStockroom.
     *
     * @param int $section_id section_id
     * @param string $name name
     * @param int $stockroom_id stockroom_id
     * @param int $quantiy quantiy
     * @param int $preorder_stock preorder_stock
     * @param int $ordered_preorder ordered_preorder
     *
     * @return boolean
     */
    public function InsertStockroom($section_id, $name, $stockroom_id, $quantiy, $preorder_stock, $ordered_preorder)
    {
        $query = 'INSERT INTO ' . $this->table_prefix . 'product_attribute_stockroom_xref
				  (section_id,section,stockroom_id,quantity,preorder_stock, ordered_preorder)
				  VALUES ("' . $section_id . '",
						  "' . $name . '",
						  "' . $stockroom_id . '",
						  "' . $quantiy . '",
						  "' . $preorder_stock . '",
						  "' . $ordered_preorder . '")';
        $this->_db->setQuery($query);

        if (!$this->_db->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        return true;
    }

    /**
     * Function GetAttributepriceData.
     *
     * @param int $section_id section_id
     * @param string $name name
     *
     * @return  array
     */
    public function GetAttributepriceData($section_id, $name)
    {
        $query = 'SELECT * FROM ' . $this->table_prefix . 'product_attribute_price
				  WHERE `section_id` =  "' . $section_id . '" and section="' . $name . '" ';
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

    /**
     * Function InsertAttributeprice.
     *
     * @param int $section_id section_id
     * @param string $name name
     * @param float $product_price product_price
     * @param string $product_currency product_currency
     * @param int $shopper_group_id shopper_group_id
     * @param int $price_quantity_start price_quantity_start
     * @param int $price_quantity_end price_quantity_end
     * @param float $discount_price discount_price
     * @param string $discount_start_date discount_start_date
     * @param string $discount_end_date discount_end_date
     *
     * @return boolean
     */
    public function InsertAttributeprice($section_id, $name, $product_price, $product_currency, $shopper_group_id,
                                         $price_quantity_start, $price_quantity_end, $discount_price, $discount_start_date, $discount_end_date
    )
    {
        $row = $this->getTable('product_attribute_price_detail');
        $post = array();
        $post['price_id'] = 0;
        $post['section_id'] = $section_id;
        $post['section'] = $name;
        $post['product_price'] = $product_price;
        $post['product_currency'] = $product_currency;
        $post['cdate'] = time();
        $post['shopper_group_id'] = $shopper_group_id;
        $post['price_quantity_start'] = $price_quantity_start;
        $post['price_quantity_end'] = $price_quantity_end;
        $post['discount_price'] = $discount_price;
        $post['discount_start_date'] = $discount_start_date;
        $post['discount_end_date'] = $discount_end_date;

        if (!$row->bind($post)) {
            return false;
        }

        if (!$row->store()) {
            return false;
        }

        return true;
    }

    /**
     * Method to checkout/lock the product_detail.
     *
     * @param int $uid User ID of the user checking the helloworl detail out.
     *
     * @return  boolean  True on success
     *
     * @since   1.5
     */
    public function checkout($uid = null)
    {
        if ($this->id) {
            // Make sure we have a user id to checkout the article with.
            if (is_null($uid)) {
                $user = JFactory::getUser();
                $uid = (int)$user->get('id');
            }

            // Lets get to it and checkout the thing.
            $product_detail = $this->getTable('product_detail');

            if (!$product_detail->checkout($uid, $this->id)) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Method to checkin a row.
     *
     * @param integer $pk The numeric id of the primary key.
     *
     * @return  boolean|integer  False on failure or error, true otherwise.
     *
     * @since   1.6
     */
    public function checkin($pks = array())
    {
        $pks = (array)$pks;
        $table = $this->getTable('product_detail');
        $count = 0;

        if (empty($pks)) {
            $pks = array((int)$this->getState($this->getName() . '.id'));
        }

        // Check in all items.
        foreach ($pks as $pk) {
            if ($table->load($pk)) {
                if ($table->checked_out > 0) {
                    if (!$this->doCheckIn($pk)) {
                        return false;
                    }

                    $count++;
                }
            } else {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $table->getError());

                return false;
            }
        }

        return $count;
    }

    public function doCheckIn($pk = null)
    {
        // Only attempt to check the row in if it exists.
        if ($pk) {
            $user = JFactory::getUser();

            // Get an instance of the row to checkin.
            $table = $this->getTable('product_detail');

            if (!$table->load($pk)) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $table->getError());

                return false;
            }

            // If there is no checked_out or checked_out_time field, just return true.
            if (!property_exists($table, 'checked_out') || !property_exists($table, 'checked_out_time')) {
                return true;
            }

            // Check if this is the user having previously checked out the row.
            if ($table->checked_out > 0 && $table->checked_out != $user->get('id') && !$user->authorise('core.admin', 'com_checkin')) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(JText::_('JLIB_APPLICATION_ERROR_CHECKIN_USER_MISMATCH'));

                return false;
            }

            // Attempt to check the row in.
            if (!$table->checkin($pk)) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $table->getError());

                return false;
            }
        }

        return true;
    }

    /**
     * Tests if product_detail is checked out.
     *
     * @param int $uid A user id.
     *
     * @return  boolean  True if checked out.
     *
     * @since   1.5
     */
    public function isCheckedOut($uid = 0)
    {
        if ($this->_loadData()) {
            if ($uid) {
                return ($this->data->checked_out && $this->data->checked_out != $uid);
            } else {
                return $this->data->checked_out;
            }
        }

        return false;
    }

    /**
     * Function delete_subprop.
     *
     * @param int $sp sp
     * @param int $subattribute_id subattribute_id
     *
     * @return  void
     */
    public function delete_subprop($sp, $subattribute_id)
    {
        $subPropertyList = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $subattribute_id, true);

        if ($sp) {
            $subproperty = RedshopHelperProduct_Attribute::getAttributeSubProperties($sp, $subattribute_id, true);
        } else {
            $subproperty = $subPropertyList;
        }

        for ($j = 0, $jn = count($subproperty); $j < $jn; $j++) {
            $query = "DELETE FROM `" . $this->table_prefix . "product_subattribute_color`
					  WHERE `subattribute_id` = '" . $subattribute_id . "'
					  AND subattribute_color_id= '" . $subproperty[$j]->subattribute_color_id . "'";
            $this->_db->setQuery($query);
            $this->_db->execute();

            if (isset($subproperty[$j]->subattribute_color_image)
                && $subproperty[$j]->subattribute_color_image) {
                $this->delete_image($subproperty[$j]->subattribute_color_image, 'subcolor');
            }
        }

        if (count($subPropertyList) <= 1) {
            $query = "UPDATE `" . $this->table_prefix . "product_attribute_property`
						SET `setrequire_selected` = '0'
						WHERE `property_id` = " . (int)$subattribute_id;
            $this->_db->setQuery($query);
            $this->_db->execute();
        }
    }

    /**
     * Function delete_prop.
     *
     * @param int $attributeId attribute_id
     * @param int $propertyId property_id
     *
     * @return  void
     */
    public function delete_prop($attributeId, $propertyId)
    {
        $propertyList = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attributeId, 0, '', 0, 0, true);

        if ($propertyId) {
            $property = RedshopHelperProduct_Attribute::getAttributeProperties($propertyId, $attributeId, 0, '', 0, 0, true);
        } else {
            $property = $propertyList;
        }

        for ($j = 0, $jn = count($property); $j < $jn; $j++) {
            $propertyId = $property[$j]->property_id;
            $query = "DELETE FROM `" . $this->table_prefix . "product_attribute_property`
					  WHERE `attribute_id`='" . $attributeId . "'
					  AND `property_id` = '" . $property[$j]->property_id . "' ";
            $this->_db->setQuery($query);

            if ($this->_db->execute()) {
                if (isset($property[$j]->property_image) && $property[$j]->property_image) {
                    $this->delete_image($property[$j]->property_image, 'product_attributes');
                }

                $this->delete_subprop(0, $propertyId);
            }
        }

        if (count($propertyList) <= 1) {
            $query = "UPDATE `" . $this->table_prefix . "product_attribute`
						SET `attribute_required` = '0'
						WHERE `attribute_id` = " . (int)$attributeId;
            $this->_db->setQuery($query);
            $this->_db->execute();
        }

        JFactory::getApplication()->close();
    }

    /**
     * Function delete_attibute.
     *
     * @param int $productId attribute_id
     * @param int $attributeId property_id
     * @param int $attribute_set_id attribute_set_id
     *
     * @return  void
     */
    public function delete_attibute($productId, $attributeId, $attribute_set_id)
    {

        if (empty($attribute_set_id) && empty($productId)) {
            return;
        }

        if ($attributeId) {
            $attributes = array();
            $attributes[0] = new stdClass;
            $attributes[0]->attribute_id = $attributeId;
        } else {
            if ($productId) {
                $attributes = \Redshop\Product\Attribute::getProductAttribute($productId);
            } else {
                $attributes = \Redshop\Product\Attribute::getProductAttribute(0, $attribute_set_id);
            }
        }

        if ($productId) {
            $and = "`product_id`='" . $productId . "'";
        } else {
            $and = "`attribute_set_id`='" . $attribute_set_id . "'";
        }

        for ($i = 0, $in = count($attributes); $i < $in; $i++) {
            $query = "DELETE FROM `" . $this->table_prefix . "product_attribute`
					  WHERE " . $and . " and `attribute_id` = '" . $attributes[$i]->attribute_id . "' ";
            $this->_db->setQuery($query);

            if ($this->_db->execute()) {
                $this->delete_prop($attributes[$i]->attribute_id, 0);
            }
        }
    }

    /**
     * Function delete_image.
     *
     * @param string $imagename imagename
     * @param int|string $section section
     *
     * @return  void
     */
    public function delete_image($imagename, $section)
    {
        $imagesrcphy = REDSHOP_FRONT_IMAGES_RELPATH . $section . "/" . $imagename;

        if (JFile::exists($imagesrcphy)) {
            JFile::delete($imagesrcphy);
        }
    }

    /**
     * Function copy_image.
     *
     * @param array $imageArray imageArray
     * @param string $section section
     * @param int $section_id section_id
     *
     * @return  string
     */
    public function copy_image($imageArray, $section, $section_id)
    {
        $src = $imageArray['tmp_name'];
        $imgname = RedshopHelperMedia::cleanFileName($imageArray['name']);
        $propertyimage = $section_id . '_' . $imgname;
        $dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $propertyimage;
        copy($src, $dest);

        return $propertyimage;
    }

    /**
     * Function copy_image_from_path.
     *
     * @param string $imagePath imagePath
     * @param string $section section
     * @param int $section_id section_id
     *
     * @return  string
     */
    public function copy_image_from_path($imagePath, $section, $section_id = 0)
    {
        if (strpos($imagePath, "components/com_redshop/assets/images/") !== -1) {
            $imagePath = str_replace("components/com_redshop/assets/images/", "", $imagePath);
        }

        $src = REDSHOP_FRONT_IMAGES_RELPATH . $imagePath;
        $imgname = RedshopHelperMedia::cleanFileName($imagePath);
        $propertyimage = $section_id . '_' . basename($imgname);
        $dest = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/' . $propertyimage;
        copy($src, $dest);

        return $propertyimage;
    }

    /**
     * Function checkVirtualNumber.
     *
     * @param int $productId product_id
     * @param array $vpnArray vpnArray
     *
     * @return  boolean
     */
    public function checkVirtualNumber($productId = 0, $vpnArray = array())
    {
        if (count($vpnArray) > 0) {
            $strVPN = "'" . implode("','", $vpnArray) . "'";
            $query = "SELECT COUNT(product_number) FROM `" . $this->table_prefix . "product` "
                . "WHERE product_number IN (" . $strVPN . ") ";
            $this->_db->setQuery($query);
            $there = $this->_db->loadResult();

            if ($there > 0) {
                return true;
            }

            $query = "SELECT ap.property_number AS number "
                . "FROM " . $this->table_prefix . "product_attribute_property AS ap "
                . "LEFT JOIN " . $this->table_prefix . "product_attribute AS a ON a.attribute_id=ap.attribute_id "
                . "WHERE a.product_id!='" . $productId . "' "
                . "AND ap.property_number IN (" . $strVPN . ") "
                . "UNION "
                . "SELECT sp.subattribute_color_number AS number FROM " . $this->table_prefix . "product_subattribute_color AS sp "
                . "LEFT JOIN " . $this->table_prefix . "product_attribute_property AS ap ON ap.property_id=sp.subattribute_id "
                . "LEFT JOIN " . $this->table_prefix . "product_attribute AS a ON a.attribute_id=ap.attribute_id "
                . "WHERE a.product_id!='" . $productId . "' "
                . "AND sp.subattribute_color_number IN (" . $strVPN . ") ";

            $this->_db->setQuery($query);
            $list = $this->_db->loadObjectList();

            if (count($list) > 0) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Function getChildProducts.
     *
     * @return stdClass
     */
    public function getChildProducts()
    {
        $products = $this->getAllChildProductArrayList(0, $this->id);
        $productId = $product_name = array();

        for ($i = 0, $in = count($products); $i < $in; $i++) {
            $product = $products[$i];
            $productId[] = $product->product_id;
            $product_name[] = $product->product_name;
        }

        $prod = new stdClass;
        $prod->name = $product_name;
        $prod->id = $productId;

        return $prod;
    }

    /**
     * Function getAllChildProductArrayList
     *
     * @param int $childid childid
     * @param int $parentid parentid
     *
     * @return mixed
     */
    public function getAllChildProductArrayList($childid = 0, $parentid = 0)
    {
        $info = RedshopHelperProduct::getChildProduct($parentid);

        if (empty(static::$childproductlist)) {
            for ($i = 0, $in = count($info); $i < $in; $i++) {
                if ($childid != $info[$i]->product_id) {
                    static::$childproductlist[] = $info[$i];
                    $this->getAllChildProductArrayList($childid, $info[$i]->product_id);
                }
            }
        }

        return static::$childproductlist;
    }

    /**
     * Function removeAccessory.
     *
     * @param int $accessoryId accessory_id
     * @param int $category_id category_id
     * @param int $child_product_id child_product_id
     *
     * @return boolean
     */
    public function removeAccessory($accessoryId, $category_id = 0, $child_product_id = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->delete($db->qn('#__redshop_product_accessory'));

        if ($category_id != 0) {
            $query->where('category_id = ' . (int)$category_id);
        } else {
            $query->where('accessory_id = ' . (int)$accessoryId);
        }

        if ($child_product_id != 0) {
            $query->where('child_product_id = ' . (int)$child_product_id);
        }

        if (!$db->setQuery($query)->execute()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        } else {
            return true;
        }
    }

    /**
     * Function ResetPreOrderStockroomQuantity.
     *
     * @param string $stockroom_type stockroom_type
     * @param int $sid section_id
     * @param int $pid product_id
     *
     * @return  void
     */
    public function ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid)
    {
        $product = " AND product_id='" . $pid . "' ";
        $section = "";
        $table = "product";

        if ($stockroom_type != 'product') {
            $product = " AND section_id='" . $pid . "' ";
            $section = " AND section = '" . $stockroom_type . "' ";
            $table = "product_attribute";
        }

        $query = "UPDATE " . $this->table_prefix . $table . "_stockroom_xref
				  SET preorder_stock='0' , ordered_preorder= '0'
				  WHERE stockroom_id='" . $sid . "'" . $product . $section;

        if ($query != "") {
            $this->_db->setQuery($query);
            $this->_db->execute();
        }
    }

    /**
     * Function update_attr_property_image.
     *
     * @param int $propertyId property_id
     * @param string $propertyimage property_image
     * @param string $property_main_image property_main_image
     *
     * @return  void
     */
    public function update_attr_property_image($propertyId, $propertyimage, $property_main_image)
    {
        $query = "UPDATE " . $this->table_prefix . "product_attribute_property
				  SET property_image='" . $propertyimage . "' , property_main_image= '" . $property_main_image . "'
				  WHERE property_id='" . $propertyId . "'";

        $this->_db->setQuery($query)->execute();
    }

    /**
     * Function update_subattr_image.
     *
     * @param int $subPropertyId subproperty_id
     * @param string $subattribute_color_image subattribute_color_image
     *
     * @return  void
     */
    public function update_subattr_image($subPropertyId, $subattribute_color_image, $subattribute_color_main_image)
    {
        $query = "UPDATE " . $this->table_prefix . "product_subattribute_color
				  SET subattribute_color_image='" . $subattribute_color_image . "' , subattribute_color_main_image= '" . $subattribute_color_main_image . "'
				  WHERE subattribute_color_id='" . $subPropertyId . "'";

        $this->_db->setQuery($query)->execute();
    }

    /**
     * Function copyDiscountCalcdata.
     *
     * @param int $old_product_id old_product_id
     * @param int $new_product_id new_product_id
     * @param string $discount_calc_method discount_calc_method
     *
     * @return  boolean
     * @throws  Exception
     */
    public function copyDiscountCalcdata($old_product_id, $new_product_id, $discount_calc_method)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_product_discount_calc'))
            ->where($db->qn('product_id') . ' = ' . (int)$old_product_id);

        $list = $db->setQuery($query)->loadObjectList();

        for ($i = 0, $in = count($list); $i < $in; $i++) {
            $discount_calc_unit = $list[$i]->discount_calc_unit;
            $area_start = $list[$i]->area_start;
            $area_end = $list[$i]->area_end;
            $area_price = $list[$i]->area_price;

            $unit = \Redshop\Helper\Utility::getUnitConversation("m", $discount_calc_unit);

            // Replace comma with dot.
            $new_area_start = str_replace(",", ".", $area_start);
            $new_area_end = str_replace(",", ".", $area_end);

            if ($discount_calc_method == 'volume') {
                $calcunit = pow($unit, 3);
            } elseif ($discount_calc_method == 'area') {
                $calcunit = pow($unit, 2);
            } else {
                $calcunit = $unit;
            }

            // Updating value.

            $converted_area_start = $new_area_start * $calcunit;
            $converted_area_end = $new_area_end * $calcunit;

            // End

            $calcrow = $this->getTable('product_discount_calc');
            $calcrow->load();
            $calcrow->discount_calc_unit = $discount_calc_unit;
            $calcrow->area_start = $new_area_start;
            $calcrow->area_end = $new_area_end;
            $calcrow->area_price = $area_price;
            $calcrow->area_start_converted = $converted_area_start;
            $calcrow->area_end_converted = $converted_area_end;
            $calcrow->product_id = $new_product_id;

            if (!$calcrow->check()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            if (!$calcrow->store()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }
        }

        // Discount calc extra data
        $query_extra = "Select * FROM `" . $this->table_prefix . "product_discount_calc_extra` WHERE product_id='" . $old_product_id . "' ";
        $this->_db->setQuery($query_extra);
        $list_extra = $this->_db->loadObjectList();

        for ($i = 0, $in = count($list_extra); $i < $in; $i++) {
            $pdc_option_name = $list_extra[$i]->option_name;
            $pdc_price = $list_extra[$i]->price;
            $pdc_oprand = $list_extra[$i]->oprand;

            if (trim($pdc_option_name) != "") {
                $pdcextrarow = $this->getTable('product_discount_calc_extra');
                $pdcextrarow->load();
                $pdcextrarow->pdcextra_id = 0;
                $pdcextrarow->option_name = $pdc_option_name;
                $pdcextrarow->oprand = $pdc_oprand;
                $pdcextrarow->price = $pdc_price;
                $pdcextrarow->product_id = $new_product_id;

                if (!$pdcextrarow->store()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Store product from webservice
     *
     * @param array $data Data from the request
     *
     * @return  boolean|integer
     * @throws  Exception
     */
    public function saveWS($data)
    {
        $row = $this->store($data);

        if ($row) {
            return $row->product_id;
        }

        return false;
    }

    /**
     * Get product attributes for the getAttribute webservice
     *
     * @param string $productNumber Product number of the product
     *
     * @return array|boolean
     *
     */
    public function getAttributesWS($productNumber)
    {
        $result = null;
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('product_id')
            ->from($db->qn('#__redshop_product'))
            ->where($db->qn('product_number') . ' = ' . $db->q($productNumber));
        $db->setQuery($query);

        $productId = $db->loadResult();

        if ($productId) {
            $this->id = $productId;

            $result = $this->getAttributes();
        }

        return $result;
    }

    /**
     * Method for store media.
     *
     * @param object $row Product data
     * @param string $mediaField Media field name
     *
     * @return  boolean|integer       Id of media row if success. False otherwise.
     * @throws  Exception
     *
     * @since   2.1.0
     */
    protected function storeMedia($row, $mediaField = 'product_full_image')
    {
        $input = JFactory::getApplication()->input;
        $dropzone = $input->post->get('dropzone', array(), 'array');
        $dropzone = isset($dropzone[$mediaField]) ? $dropzone[$mediaField] : null;

        $dropzoneAlternateText = $input->post->get('dropzone_alternate_text', array(), '');
        $dropzoneAlternateText = isset($dropzoneAlternateText[$mediaField]) ? $dropzoneAlternateText[$mediaField] : null;

        if (null === $dropzone || empty($dropzone)) {
            return false;
        }

        $mediaId = false;

        foreach ($dropzone as $key => $value) {
            /** @var RedshopTableMedia $mediaTable */
            $mediaTable = JTable::getInstance('Media', 'RedshopTable');

            if (strpos($key, 'media-') !== false) {
                $mediaTable->load(str_replace('media-', '', $key));

                // Delete old image.
                $oldMediaFile = JPath::clean(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $mediaTable->media_name);

                if (JFile::exists($oldMediaFile)) {
                    JFile::delete($oldMediaFile);
                }

                if (empty($value)) {
                    $mediaTable->delete();

                    continue;
                }
            } else {
                if (!$mediaTable->load(array(
                    'media_name' => $row->product_full_image,
                    'media_section' => 'product',
                    'section_id' => $row->product_id,
                    'media_type' => 'images'
                ))) {
                    if (!empty($row->product_id)) {
                        $mediaTable->set('section_id', $row->product_id);
                    }

                    $mediaTable->set('media_section', 'product');
                }
            }

            if (!JFile::exists(JPATH_ROOT . '/' . $value)) {
                continue;
            }

            $alternateText = isset($dropzoneAlternateText[$key]) ? $dropzoneAlternateText[$key] : $row->product_name;

            $mediaTable->set('media_alternate_text', $alternateText);
            $mediaTable->set('media_type', 'images');
            $mediaTable->set('published', 1);

            // Copy new image for this media
            $fileName = md5(basename($value)) . '.' . JFile::getExt($value);
            $file = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $fileName;

            JFile::move(JPATH_ROOT . '/' . $value, $file);

            $mediaTable->set('media_name', $fileName);

            if (!$mediaTable->store()) {
                continue;
            }

            $mediaId = (int)$mediaTable->media_id;
            $row->{$mediaField} = $fileName;

            // Optimize image
            $factory = new \ImageOptimizer\OptimizerFactory;
            $optimizer = $factory->get();
            $optimizer->optimize($file);
        }

        return $mediaId;
    }
}
