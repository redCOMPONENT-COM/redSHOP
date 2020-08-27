<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Model Countries
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelNewsletters extends RedshopModelList
{
    /**
     * Construct class
     *
     * @since __DEPLOY_VERSION__
     */
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'n.id',
                'name',
                'n.name'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param string $ordering  An optional ordering field.
     * @param string $direction An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function populateState($ordering = 'n.id', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param string $id A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('distinct(n.id),n.*')
            ->from($db->qn('#__redshop_newsletter', 'n'));

        $search = $this->getState('filter.search');

        if ( ! empty($search)) {
            $query->where($db->qn('n.name') . ' LIKE ' . $db->q('%' . $search . '%'));
        }

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', 's.id');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function noOfSubscribers($newsletterId)
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('count(*)')
            ->from($db->qn('#__redshop_newsletter_subscription'))
            ->where($db->qn('newsletter_id') . ' = ' . $db->q($newsletterId))
            ->where($db->qn('published') . ' = 1');

        return $db->setQuery($query)->loadResult();
    }

    /**
     * Method for list all subscribers
     *
     * @param integer $newsletterId ID of newsletter
     *
     * @return  array
     *
     * @since   __DEPLOY_VERSION__
     */
    public function listAllSubscribers($newsletterId = 0)
    {
        $input = JFactory::getApplication()->input;

        $newsletterId = $input->getInt('newsletter_id', $newsletterId);

        $db    = $this->getDbo();
        $query = $db->getQuery(true)
            ->select($db->qn(array('uf.firstname', 'uf.lastname', 'u.username')))
            ->select('ns.*')
            ->from($db->qn('#__redshop_newsletter_subscription', 'ns'))
            ->leftJoin(
                $db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('ns.user_id')
            )
            ->leftJoin($db->qn('#__users', 'u') . ' ON ' . $db->qn('u.id') . ' = ' . $db->qn('ns.user_id'))
            ->where($db->qn('ns.newsletter_id') . ' = ' . (int)$newsletterId)
            ->where($db->qn('ns.published') . ' = 1');

        $zipStart      = $input->getString('zipstart', '');
        $zipEnd        = $input->getString('zipend', '');
        $filterCity    = $input->getString('cityfilter', '');
        $startDate     = $input->getString('start_date', '');
        $endDate       = $input->getString('end_date', '');
        $filterCountry = $input->get('country', [], 'array');

        // Filter: Country
        if ( ! empty($filterCountry)) {
            $query->where(
                $db->qn('uf.country_code') . ' IN (' . implode(
                    ',',
                    /** @scrutinizer ignore-type */
                    $db->q($filterCountry)
                ) . ')'
            );
        }

        // Filter: Start date and end date
        if ( ! empty($startDate) && ! empty($endDate)) {
            $query->where(
                'CAST(' . $db->qn('u.registerDate') . ' AS datetime) '
                . 'BETWEEN ' . $db->quote($startDate) . ' AND ' . $db->quote($endDate)
            );
        }

        // Filter: zip code start
        if ( ! empty($zipStart)) {
            $query->where($db->qn('uf.zipcode') . ' LIKE ' . $db->quote($zipStart . '%'));
        }

        // Filter: zip code start and end
        if ( ! empty($zipStart) && ! empty($zipEnd)) {
            $query->where(
                '(' . $db->qn('uf.zipcode') . ' LIKE ' . $db->quote($zipStart . '%')
                . ' OR ' . $db->qn('uf.zipcode') . ' LIKE ' . $db->quote($zipEnd . '%') . ')'
            );
        }

        // Filter: city
        if ( ! empty($filterCity)) {
            $cityQuery    = $db->getQuery(true)
                ->select($db->qn('id'))
                ->from($db->qn('#__redshop_fields'))
                ->where($db->qn('name') . ' = ' . $db->quote('field_city'));
            $cityFieldIds = $db->setQuery($cityQuery)->loadRow();

            $query->leftJoin(
                $db->qn('#__redshop_fields_data', 'f') . ' ON ' . $db->qn('f.itemid') . ' = ' . $db->qn(
                    'uf.users_info_id'
                )
            )
                ->where($db->qn('uf.address_type') . ' = ' . $db->quote('BT'))
                ->where($db->qn('f.section') . ' = 7')
                ->where($db->qn('f.data_txt') . ' LIKE ' . $db->quote($filterCity . '%'));

            if ($cityFieldIds) {
                $query->where($db->qn('f.fieldid') . ' IN (' . implode(',', $cityFieldIds) . ')');
            }
        } else {
            $query->select($db->qn('uf.address_type'))
                ->where(
                    '(' . $db->qn('uf.address_type') . ' = ' . $db->quote('BT') . ' OR ' . $db->qn(
                        'uf.address_type'
                    ) . ' IS NULL)'
                );

            $shopperGroupFilter = $input->get('shoppergroups', [], 'array');

            if ( ! empty($shopperGroupFilter)) {
                $query->where($db->qn('uf.shopper_group_id') . ' IN (' . implode(',', $shopperGroupFilter) . ')');
            }
        }

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Method get country
     *
     * @return array|mixed
     */
    public function getCountry()
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('country_3_code as value, country_name as text')
            ->from($db->qn('#__redshop_country'));

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Method get product
     *
     * @return array|mixed
     */
    public function getProduct()
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('product_name as text, product_id as value')
            ->from($db->qn('#__redshop_product'))
            ->order($db->qn('product_id'));

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Method get shopper group
     *
     * @return array|mixed
     */
    public function getShopperGroup()
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('id as value,name as text')
            ->from($db->qn('#__redshop_shopper_group'));

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Method get order user
     *
     * @param integer $uid
     *
     * @return int
     * @throws \Exception
     */
    public function orderUser($uid)
    {
        $jInput       = JFactory::getApplication()->input;
        $number_order = $jInput->getInt('number_order', 0);
        $oprand       = $jInput->getCmd('oprand', 'select');
        $start        = $jInput->get('total_start', '');
        $end          = $jInput->get('total_end', '');
        $orderTotal   = '';
        $db           = $this->_db;

        if ($start != '' && $end != '') {
            $orderTotal = " or order_total between " . $start . " and " . $end;
        }

        switch ($oprand) {
            case 'more':
                $cond = '>=' . $number_order;
                break;
            case 'less':
                $cond = '<=' . $number_order;
                break;
            case 'select':
                $cond = "=" . $this->_db->quote('');
                break;
            case 'equally':
            default:
                $cond = '=' . $number_order;
                break;
        }

        $query = $db->getQuery(true)
            ->select(' COUNT(*) AS total,order_total')
            ->from('#__redshop_orders')
            ->group('user_id')
            ->having('total ' . $cond . $orderTotal)
            ->having('user_id' . ' = ' . $uid);

        $result = $db->setQuery($query)->loadResult();

        if ($result || ($start == '' && $end == '' && $oprand == 'select')) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @param $uid
     *
     * @return int
     * @throws \Exception
     */
    public function category($uid)
    {
        $return     = 1;
        $categories = JFactory::getApplication()->input->get('product_category');

        if ( ! empty($categories)) {
            $db    = $this->_db;
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->qn('#__redshop_product_category_xref', 'pcx'))
                ->leftJoin($db->qn('#__redshop_order_item', 'oi') . ' ON pcx.product_id = oi.product_id')
                ->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON o.order_id = oi.order_id')
                ->where($db->qn('o.user_id') . ' = ' . $db->q($uid))
                ->where($db->qn('category_id') . ' IN (' . implode("','", $categories) . ')');

            $result = $db->setQuery($query)->loadObjectlist();

            if (count($result) <= 0) {
                $return = 0;
            }
        }

        return $return;
    }

    /**
     * @param integer $userId
     *
     * @return integer
     * @throws \Exception
     */
    public function product($userId)
    {
        $return  = 1;
        $product = JFactory::getApplication()->input->get('product');

        if ( ! empty($product)) {
            $db    = $this->_db;
            $query = $db->getQuery(true)
                ->select('o.*')
                ->from($db->qn('#__redshop_orders', 'o'))
                ->leftJoin($db->qn('__redshop_order_item', 'oi') . ' ON o.order_id=oi.order_id')
                ->where($db->qn('o.user_id') . ' = ' . $db->qn($userId))
                ->where($db->qn('product_id') . ' IN (' . implode("','", $product) . ')');

            $result = $db->setQuery($query)->loadObjectList();

            if (count($result) <= 0) {
                $return = 0;
            }
        }

        return $return;
    }


    /**
     * @param array $subscriberIds
     * @param array $userid
     * @param array $username
     *
     * @return array
     * @throws \Exception
     */
    public function newsletterEntry($subscriberIds = [], $userid = [], $username = [])
    {
        $newsletterId = JFactory::getApplication()->input->getInt('newsletter_id');

        $url = JUri::root();

        $mailfrom = JFactory::getConfig()->get('mailform');
        $fromname = JFactory::getConfig()->get('fromname');

        if (Redshop::getConfig()->get('NEWS_MAIL_FROM') != "") {
            $mailfrom = Redshop::getConfig()->get('NEWS_MAIL_FROM');
        }

        if (Redshop::getConfig()->get('NEWS_FROM_NAME') != "") {
            $fromname = Redshop::getConfig()->get('NEWS_FROM_NAME');
        }

        // Getting newsletter content
        $newsletterContent = $this->getNewsletterContent($newsletterId);

        $subject            = "";
        $newsletterBody     = "";
        $newsletterTemplate = "";

        if (count($newsletterContent) > 0) {
            $subject                = $newsletterContent[0]->subject;
            $newsletterBody         = $newsletterContent[0]->body;
            $newsletterTemplateData = RedshopHelperTemplate::getTemplate(
                'newsletter',
                $newsletterContent[0]->template_id
            );
            $newsletterTemplate     = $newsletterTemplateData[0]->template_desc;
        }

        $o       = new stdClass;
        $o->text = $newsletterBody;
        JPluginHelper::importPlugin('content');
        $x = [];
        RedshopHelperUtility::getDispatcher()->trigger('onPrepareContent', array(&$o, &$x, 1));
        $newsletterTemplate2 = $o->text;

        $content = str_replace("{data}", $newsletterTemplate2, $newsletterTemplate);

        $products     = $this->getProductIdList();
        $imgWidth     = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE');
        $imgHeight    = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT');
        $sizeSwapping = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');

        foreach ($products as $product) {
            $productId = $product->product_id;

            if (strpos($content, '{redshop:' . $productId . '}') !== false) {
                $content = str_replace('{redshop:' . $productId . '}', "", $content);
            }

            if (strpos($content, '{Newsletter Products:' . $productId . '}') === false) {
                continue;
            }

            $newsProductBody = $this->getNewsletterProductsContent();
            $tmpTemplate     = $newsProductBody[0]->template_desc;

            $thumbImage = "";

            if ( ! empty($product->product_full_image)) {
                $thumbUrl   = RedshopHelperMedia::getImagePath(
                    $product->product_full_image,
                    '',
                    'thumb',
                    'product',
                    $imgWidth,
                    $imgHeight,
                    $sizeSwapping
                );
                $thumbImage = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/"
                    . $product->product_full_image . "' title='' rel=\"lightbox[product7]\">";
                $thumbImage .= "<img id='main_image' src='" . $thumbUrl . "'>";
                $thumbImage .= "</a>";
            }

            $tmpTemplate = str_replace("{product_thumb_image}", $thumbImage, $tmpTemplate);
            $tmpTemplate = str_replace(
                "{product_price}",
                RedshopHelperProductPrice::formattedPrice($product->product_price),
                $tmpTemplate
            );
            $tmpTemplate = str_replace("{product_name}", $product->product_name, $tmpTemplate);
            $tmpTemplate = str_replace("{product_desc}", $product->product_desc, $tmpTemplate);
            $tmpTemplate = str_replace("{product_s_desc}", $product->product_s_desc, $tmpTemplate);

            $content = str_replace("{Newsletter Products:" . $productId . "}", $tmpTemplate, $content);
        }

        // Replacing the Text library texts
        $content = RedshopHelperText::replaceTexts($content);
        Redshop\Mail\Helper::imgInMail($content);

        $subscribers = [];
        $db          = JFactory::getDbo();
        $query       = $db->getQuery(true);
        $columns     = $db->qn(array('newsletter_id', 'subscription_id', 'subscriber_name', 'user_id', 'read', 'date'));
        $today       = time();

        foreach ($subscriberIds as $index => $subscriberId) {
            $subscriber = $this->subscribersinfo($subscriberId);

            if (empty($subscriber)) {
                continue;
            }

            $subscriber = $subscriber[0];

            $subscribeEmail = trim($subscriber->email);

            if (empty($subscribeEmail)) {
                continue;
            }

            $unSubscribeLink = $url . 'index.php?option=com_redshop&view=newsletters&task=unsubscribe&email1=' . $subscribeEmail;
            $values          = array($newsletterId, $subscriberId, $username[$index], $userid[$index], 0, $today);

            $query->clear()
                ->insert($db->qn('#__redshop_newsletter_tracker'))
                ->columns($columns)
                ->values(implode(',', $values));
            $db->setQuery($query)->execute();

            $message = '<img src="' . $url . 'index.php?option=com_redshop&view=newsletters&task=tracker&tmpl=component&tracker_id='
                . $db->insertid() . '" style="display:none;" />';

            // Replacing the tags with the values
            $message .= str_replace("{username}", $subscriber->username, $content);
            $message = str_replace("{email}", $subscribeEmail, $message);

            $unSubscribeLink = "<a href='" . $unSubscribeLink . "'>" . JText::_('COM_REDSHOP_UNSUBSCRIBE') . "</a>";
            $message         = str_replace("{unsubscribe_link}", $unSubscribeLink, $message);

            $subscribers[$index] = (int)JFactory::getMailer()->sendMail(
                $mailfrom,
                $fromname,
                $subscribeEmail,
                $subject,
                $message,
                true
            );
        }

        return $subscribers;
    }

    /**
     * Method get newsletter content
     *
     * @param integer $newsletterId
     *
     * @return array|mixed
     */
    public function getNewsletterContent($newsletterId)
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('n.template_id,n.body,n.subject')
            ->from($db->qn('#__redshop_newsletter', 'n'))
            ->leftJoin($db->qn('#__redshop_template', 'nt') . ' ON n.template_id=nt.id')
            ->where($db->qn('n.published') . ' = 1')
            ->where($db->qn('n.id') . ' = ' . $db->q($newsletterId));

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Method get product list
     *
     * @return array|mixed
     */
    public function getProductIdList()
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_product'))
            ->where($db->qn('published') . ' = 1');

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Method subscribers info
     *
     * @param integer $subscriberId
     *
     * @return array|mixed
     */
    public function subscribersInfo($subscriberId)
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('IFNULL(u.email,s.email) AS email,IFNULL(u.username,s.name) AS username')
            ->from($db->qn('#__redshop_newsletter_subscription', 's'))
            ->leftJoin($db->qn('#__users', 'u') . ' ON  u.id=s.user_id')
            ->where($db->qn('s.id') . ' = ' . $db->q($subscriberId))
            ->where($db->qn('published') . ' = 1');

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Method get newsletter subscriber
     *
     * @param integer $newsletterId
     * @param integer $subscriptionId
     *
     * @return array|mixed
     */
    public function getNewsletterSubscriber($newsletterId, $subscriptionId)
    {
        $db    = $this->_db;
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_newsletter_subscription'))
            ->where($db->qn('newsletter_id') . ' = ' . $db->q($newsletterId))
            ->where($db->qn('id') . ' = ' . $db->q($subscriptionId));

        return $db->setQuery($query)->loadObjectList();
    }

    public function getNewsletterProductsContent()
    {
        $db = $this->_db;
        $query = $db->getQuery(true)
            ->select('template_desc')
            ->from($db->qn('#__redshop_template'))
            ->where($db->qn('section') . ' = ' . $db->q('newsletter_product'));

        return $db->setQuery($query)->loadObjectList();
    }
}