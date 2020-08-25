<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Media
 *
 * @since  2.0.3
 */
class RedshopHelperNewsletter
{
    /**
     * Method for add an subscriber for Newsletter
     *
     * @param   int      $userId    ID of user.
     * @param   array    $data      Data of subscriber
     * @param   boolean  $sendMail  True for send mail.
     * @param   null     $isNew     Capability for old method.
     *
     * @return  boolean             True on success. False otherwise.
     *
     * @throws  Exception
     * @since   2.0.3
     *
     */
    public static function subscribe($userId = 0, $data = array(), $sendMail = false, $isNew = null)
    {
	    $db   = JFactory::getDbo();
        $newsletter = 1;
        $userId     = (int)$userId;
        $user       = JFactory::getUser();

        if (Redshop::getConfig()->get('DEFAULT_NEWSLETTER') > 0) {
            $newsletter = Redshop::getConfig()->get('DEFAULT_NEWSLETTER');
        }

        if (!$userId) {
            $userId = $user->id;
        }

        if (empty($data)) {
            if (!$user->guest) {
                $data['user_id']  = $user->id;
                $data['username'] = $user->username;
                $data['email']    = $user->email;
                $data['name']     = $user->name . " (" . $user->username . ")";
            } else {
                $redshopUser = RedshopHelperUser::getUserInformation();

                $data['user_id']  = $redshopUser->user_id;
                $data['username'] = $redshopUser->username;
                $data['email']    = $redshopUser->user_email;
                $data['name']     = $redshopUser->firstname . ' ' . $redshopUser->lastname;
            }
        } else {
            $data['user_id'] = $userId;

            if (isset($data['username'])) {
                $data['name'] = $data['username'];
            }

            if ($user->id && $user->email == $data['email']) {
                $data['name'] = $user->name . " (" . $user->username . ")";
            }
        }

        $data['date']          = time();
        $data['newsletter_id'] = $newsletter;
        $data['published']     = 1;

        $needSendMail = Redshop::getConfig()->getBool('NEWSLETTER_CONFIRMATION') && $sendMail ? true : false;

        if ($needSendMail) {
            $data['published'] = 0;
        }

	    $query = $db->getQuery(true)
		    ->insert($db->qn('#__redshop_newsletter_subscription'))
		    ->columns(
			    array(
				    $db->quoteName('user_id'), $db->quoteName('date'),
				    $db->quoteName('newsletter_id'), $db->quoteName('name'),
				    $db->quoteName('email'), $db->quoteName('published')
			    )
		    )
		    ->values($db->quote(abs($data['user_id'])) . ','
		             . $db->quote(date('Y-m-d H:i:s',$data['date']))
		             . ',' . $db->quote($data['newsletter_id'])
		             . ',' . $db->quote($data['name'])
		             . ',' . $db->quote($data['email'])
		             . ',' . $db->quote(1)
		    );

	    $result = $db->setQuery($query)->execute();

	    if ($result) {
		    $query = $db->getQuery(true)
			    ->select('id')
			    ->from($db->qn('#__redshop_newsletter_subscription'))
			    ->where($db->qn('user_id') . ' = ' . $db->q(abs($data['user_id'])));

		    $subId = $db->setQuery($query)->loadResult();
	    }

        if ($needSendMail) {
            Redshop\Mail\Newsletter::sendConfirmationMail($subId);
        }

        return true;
    }

    /**
     * Method for un-subscribe email from newsletter
     *
     * @param   string  $email  Email
     *
     * @return  boolean
     *
     * @since   2.0.7
     */
    public static function removeSubscribe($email = "")
    {
        $db   = JFactory::getDbo();
        $user = JFactory::getUser();

        // Skip if user is guest and empty email.
        if (empty($email) && $user->guest) {
            return true;
        }

        $query = $db->getQuery(true)
            ->delete($db->qn('#__redshop_newsletter_subscription'));

        if (!$user->guest) {
            $email = $user->email;
            $query->where($db->qn('user_id') . ' = ' . $user->id);
        }

        $query->where($db->qn('email') . ' = ' . $db->quote($email));

        if (Redshop::getConfig()->get('DEFAULT_NEWSLETTER') != '') {
            $query->where(
                $db->qn('newsletter_id') . ' = ' . $db->quote(Redshop::getConfig()->get('DEFAULT_NEWSLETTER'))
            );
        }

        $db->setQuery($query)->execute();

        Redshop\Mail\Newsletter::sendCancellationMail($email);

        return true;
    }
}
