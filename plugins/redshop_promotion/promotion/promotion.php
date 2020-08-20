<?php
/**
 * @package     redSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
require_once ( __DIR__ .'/helpers/Step.php');

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
    protected $table;
    protected $form;
    protected $layoutFolder;

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

        $this->loadLanguage();
        $this->app = JFactory::getApplication();
        $this->db = JFactory::getDbo();
        $this->query = $this->db->getQuery(true);
        $this->table = '#__redshop_promotion';
        $this->layoutFolder = JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name . '/layouts';
        $this->form = JForm::getInstance("promotions", __DIR__ . "/forms/promotion.xml", []);
    }

    /**
     * @param $cartItem
     *
     * @return false|mixed
     *
     * @since __DEPLOY_VERSION__
     */
    public function isProductAwardByPromotion($cartItem) {
        return $cartItem['isPromotionAward'] ?? false;
    }

    /**
     * @param $data
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public function onSavePromotion($data) {
        $result = $data['jform'] ?? [];

        if (!empty($result)) {
            unset($data['jform']);
            $data = base64_encode(json_encode($data));
            $result['data'] = $data;
        }

        return $result;
    }

    /**
     * @param $id
     * @return mixed|null
     * @since __DEPLOY_VERSION__
     */
    public function onLoadPromotion($id) {
        $this->query->clear()
            ->select('*')
            ->from($this->db->qn($this->table))
            ->where($this->db->qn('id') . ' = ' . $this->db->q($id));
        return $this->db->setQuery($this->query)->loadAssoc();
    }

    public function onDeletePromotion() {

    }

    /**
     * @param $cart
     * @param bool $saveSession
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public function onProcessPromotion($cart, $saveSession = false) {
        return $cart;
    }

    /**
     * @return string
     * @since __DEPLOY_VERSION__
     */
    public function onRenderBackEndLayoutConditions() {
        $post = $this->prepareData();
        $promotionId = JFactory::getApplication()->input->getInt('id', 0);
        $layout = new JLayoutFile('conditions', $this->layoutFolder);

        return $layout->render(['form' => $this->form, 'post' => $post, 'promotionId' => $promotionId]);
    }

    /**
     * @return string
     * @since __DEPLOY_VERSION__
     */
    public function onRenderBackEndLayoutAwards() {
        $post = $this->prepareData();
        $promotionId = JFactory::getApplication()->input->getInt('id', 0);
        $layout = new JLayoutFile('awards', $this->layoutFolder);

        return $layout->render(['form' => $this->form, 'post' => $post, 'promotionId' => $promotionId]);
    }

    /**
     * @return array
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
    protected function prepareData() {
        $post = \Redshop\IO\Input::getArray('post');
        $promotionId = \Redshop\IO\Input::get('id', 0, 'INT');
        $promotion = $this->onLoadPromotion($promotionId);
        $data = [];

        if (!empty($promotion['data'])) {
            try {
                $data = json_decode(base64_decode($promotion['data']), true);
            } catch (Exception $e) {
                $data = [];
            }
        }

        return array_merge($post, $data);
    }

    /**
     * @param $cart
     * @since __DEPLOY_VESION__
     */
    public function onApply() {
        # Step1: load Promotion into $cart['promotion']
        Step::loadPromotions();

        # Step2: Apply promotions
        Step::checkAndApplyPromotion();
    }
}