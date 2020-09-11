<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class newsletterModelnewsletter
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelNewsletter extends RedshopModel
{
    /**
     * @var null|string
     */
    public $_table_prefix = null;

    /**
     * @var JDatabaseDriver|null
     */
    public $_db = null;

    /**
     * Constructor
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->_db           = JFactory::getDbo();
        $this->_table_prefix = '#__redshop_';
        $subId               = JFactory::getApplication()->input->getInt('sid', '');

        if ($subId) {
            $this->confirmSubscribe($subId);
        }
    }

    /**
     * Check is email is subscription
     *
     * @param   integer  $subscriptionId  Subscribe ID
     *
     * @return  void
     * @throws  Exception
     */
    public function confirmSubscribe($subscriptionId)
    {
        $app   = JFactory::getApplication();
        $db    = $this->getDbo();
        $query = $db->getQuery(true)
            ->update($db->qn('#__redshop_newsletter_subscription'))
            ->set($db->qn('published') . ' = 1')
            ->where($db->qn('id') . ' = ' . (int)$subscriptionId);
        $db->setQuery($query)->execute();

        $app->redirect(
            Redshop\IO\Route::_(JUri::root() . 'index.php?option=com_redshop&view=newsletter'),
            JText::_('COM_REDSHOP_MESSAGE_CONFIRMED_SUBSCRIBE')
        );
    }

    /**
     * Check is email is subscription
     *
     * @param   string  $email  Email
     *
     * @return  boolean
     * @throws  Exception
     */
    public function checkSubscriptionByEmail($email)
    {
        $app  = JFactory::getApplication();
        $db   = $this->getDbo();
        $link = JUri::root() . 'index.php?option=com_redshop&view=newsletter';

        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->qn('#__redshop_newsletter'));
        $count = (int)$db->setQuery($query)->loadResult();

        if (!$count) {
            // If there are no newsletter, redirect with message.
            $app->redirect(Redshop\IO\Route::_($link), JText::_('COM_REDSHOP_NEWSLETTER_NOT_AVAILABLE'));
        }

        $user = JFactory::getUser();

        if ($user->id) {
            $email = $user->email;
        }

        $query->clear()
            ->select($db->qn('id'))
            ->from($db->qn('#__redshop_newsletter_subscription'))
            ->where($db->qn('email') . ' = ' . $db->quote($email))
            ->where($db->qn('newsletter_id') . ' = ' . Redshop::getConfig()->getInt('DEFAULT_NEWSLETTER'))
            ->where($db->qn('user_id') . ' = ' . $user->id);

        $hasSubscribed = $db->setQuery($query)->loadResult();

        if ($hasSubscribed) {
            return true;
        }

        return false;
    }
}
