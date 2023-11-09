<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Account Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerAccount extends RedshopController
{
    /**
     * Method to edit created Tag
     *
     * @return  void
     *
     * @throws  Exception
     */
    public function editTag()
    {
        $app = JFactory::getApplication();

        /** @var RedshopModelAccount $model */
        $model = $this->getModel('account');

        if ($model->editTag($app->input->post->getArray())) {
            $app->enqueueMessage(Text::_('COM_REDSHOP_TAG_EDITED_SUCCESSFULLY'));
        } else {
            $app->enqueueMessage(Text::_('COM_REDSHOP_ERROR_EDITING_TAG'));
        }

        $this->setRedirect(
            Redshop\IO\Route::_(
                'index.php?option=com_redshop&view=account&layout=mytags&Itemid=' . $app->input->getInt('Itemid'),
                false
            )
        );
    }

    /**
     * Method to send created wishlist
     *
     * @return void
     * @throws Exception
     */
    public function sendWishlist()
    {
        $input      = JFactory::getApplication()->input->post;
        $itemId     = $input->get('Itemid');
        $wishListId = $input->get('wishlist_id');

        if ($input->get('emailto') == "") {
            $msg = Text::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_TO');
        } elseif ($input->get('sender') == "") {
            $msg = Text::_('COM_REDSHOP_PLEASE_ENTER_SENDER_NAME');
        } elseif ($input->get('email') == "") {
            $msg = Text::_('COM_REDSHOP_PLEASE_ENTER_SENDER_EMAIL');
        } elseif ($input->get('subject') == "") {
            $msg = Text::_('COM_REDSHOP_PLEASE_ENTER_SUBJECT');
        } elseif (Redshop\Account\Wishlist::send($input->getArray())) {
            $msg = Text::_('COM_REDSHOP_SEND_SUCCESSFULLY');
        } else {
            $msg = Text::_('COM_REDSHOP_ERROR_SENDING');
        }

        $url = 'index.php?option=com_redshop&view=account&layout=mywishlist&mail=0&window=1&tmpl=component'
            . '&wishlist_id=' . $wishListId . '&Itemid' . $itemId;

        $this->setRedirect(Redshop\IO\Route::_($url, false), $msg);
    }

    /**
     * Method to subscribe newsletter
     *
     * @return  void
     * @throws  Exception
     */
    public function newsletterSubscribe()
    {
        RedshopHelperNewsletter::subscribe(0, array(), true);

        $itemId = JFactory::getApplication()->input->getInt('Itemid');
        $this->setRedirect(
            Redshop\IO\Route::_("index.php?option=com_redshop&view=account&Itemid=" . $itemId, false),
            Text::_('COM_REDSHOP_SUBSCRIBE_SUCCESS')
        );
    }

    /**
     *  Method to unsubscribe newsletter
     *
     * @return void
     * @throws Exception
     */
    public function newsletterUnsubscribe()
    {
        $user   = Factory::getApplication()->getIdentity();
        $itemId = JFactory::getApplication()->input->getInt('Itemid');

        RedshopHelperNewsletter::removeSubscribe($user->email);
        $msg = Text::_('COM_REDSHOP_CANCLE_SUBSCRIPTION');

        $this->setRedirect(Redshop\IO\Route::_("index.php?option=com_redshop&view=account&Itemid=" . $itemId, false), $msg);
    }

    /**
     * Method to delete account user
     *
     * @return void
     *
     * @since  2.1.2
     */
    public function deleteAccount()
    {
        $app    = JFactory::getApplication();
        $userId = Factory::getApplication()->getIdentity()->id;

        /**
         * @var RedshopModelAccount $model ;
         */
        $model  = $this->getModel('account');
        $itemId = JFactory::getApplication()->input->getInt('Itemid');

        if ($model->deleteAccount($userId)) {
            // Prepare the logout options.
            $options = array(
                'clientid' => $app->get('shared_session', '0') ? null : 0,
            );

            $app->logout(null, $options);

            $app->enqueueMessage(Text::_('COM_REDSHOP_ACCOUNT_DELETED_SUCCESSFULLY'));
            $app->redirect(
                Redshop\IO\Route::_('index.php?option=com_users&view=login', false)
            );
        } else {
            $this->setRedirect(
                Redshop\IO\Route::_("index.php?option=com_redshop&view=account&Itemid=" . $itemId, false),
                Text::_('COM_REDSHOP_ACCOUNT_DELETED_FAIL')
            );
        }
    }
}
