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
        $post = JFactory::getApplication()->input->post->getArray();
        $promotionId = JFactory::getApplication()->input->getInt('id', 0);
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
        $this->loadPromotions();

        # Step2: Apply promotions
        $this->checkAndApplyPromotion();
    }

    /**
     * @param $cart
     * @return array|mixed
     * @since  __DEPLOY_VERSION__
     */
    protected function loadPromotions(){
        $cart = \Redshop\Cart\Helper::getCart();
        $cart['promotions'] = $cart['promotions']?? [];
        $db = \Joomla\CMS\Factory::getDbo();

        if (!count($cart['promotions'])) {
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->qn($this->table))
                ->where($db->qn('published') . ' = ' . $db->q('1'))
                ->where($db->qn('type') . ' = ' . $db->q('promotion'));

            $promotions =  $db->setQuery($query)->loadObjectList();

            for ($i = 0; $i < count($promotions); $i++) {
                $promotions[$i]->data = base64_decode($promotions[$i]->data);
                $promotions[$i]->data = json_decode($promotions[$i]->data);
                $promotions[$i]->isApplied = false;

                $flag = true;

                for ($n = 0; $n < count($cart['promotions']); $n++) {
                    if ($promotions[$i]->id == $cart['promotions'][$n]->id) {
                        $flag = false;
                    }
                }

                if ($flag == true) {
                    $cart['promotions'][] = $promotions[$i];
                }
            }
        }

        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * @param $promotion
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    protected function checkAndApplyPromotion(){
        $result = false;

        # Step 1: get prepared promotions objects loaded into Cart.
        $cart = \Redshop\Cart\Helper::getCart();

        $cart['promotions'] = $cart['promotions'] ?? [];
        $promotions =& $cart['promotions'];

        # Step 2: Validate is it pass or fail condition to continue
        $isFail = empty($cart) || empty($cart['idx']) || ($cart['idx'] == 0) || !count($promotions);

        if ($isFail) {
            return $result;
        }

        # Step 3: Each promotion is try to apply
        //foreach ($promotions as &$promotion) {
        for($i = 0; $i < count($promotions); $i++) {
            Step::applyPromotion($promotions[$i], $cart);
        }

        # Step: Store cart back to session
        \Redshop\Cart\Helper::setCart($cart);
    }
}