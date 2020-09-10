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
     * @return stdClass
     * @since __DEPLOY_VERSION__
     */
    public function onValidate($data) {
        $result = new \stdClass;
        $result->errorMessage = [];
        $result->isValid = true;

        switch ($data['promotion_type']) {
            case 'amount_product':
                if (isset($data['from_date'])
                    && isset($data['to_date'])
                    && $data['from_date'] > $data['to_date']) {
                    $result->errorMessage[] = \JText::_('PLG_RESDSHOP_PROMOTION_PROMOTION_VALIDATE_DATE_RANGE');
                }

                if (empty($data['condition_amount']) || (int) $data['condition_amount'] < 0 ) {
                    $result->errorMessage[] = \JText::_('PLG_RESDSHOP_PROMOTION_PROMOTION_VALIDATE_CONDITION_QUANTITY');;
                }

                break;
            case 'volume_order':
                if (empty($data['order_volume']) || (int) $data['order_volume'] < 0) {
                    $result->errorMessage[] = \JText::_('PLG_RESDSHOP_PROMOTION_PROMOTION_VALIDATE_VOLUME_ORDER');;
                }

                break;
            default:
                if (empty($data['award_amount']) || (int) $data['award_amount'] < 0) {
                    $result->errorMessage[] = \JText::_('PLG_RESDSHOP_PROMOTION_PROMOTION_VALIDATE_AWARD_QUANTITY');;
                }
        }

        if (count($result->errorMessage) > 0) {
            $result->isValid = false;
        }

        return $result;
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
            $data = Helper::encrypt($data);
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
                $data = Helper::decrypt($promotion['data'], true);
            } catch (Exception $e) {
                $data = [];
            }
        }

        return array_merge($post, $data);
    }

    /**
     * @since __DEPLOY_VESION__
     */
    public function onApply() {
        # Step1: load Promotion into $cart['promotion']
        Step::loadPromotions();

        # Step2: Apply promotions
        Step::checkAndApplyPromotion();
    }
}