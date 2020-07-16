<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Zipcodes controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       __DEPLOY_VERSION__
 */
class RedshopControllerNewsletters extends RedshopControllerAdmin
{
    /**
     * Method send newsletter preview
     *
     * @throws \Exception
     */
    public function sendNewsletterPreview()
    {
        $this->getView('newsletters', 'preview');
        parent::display();
    }

    /**
     *  Method send newsletter
     */
    public function sendNewsletter()
    {
        $session = JFactory::getSession();

        $cid      = $this->input->post->get('cid', array(0), 'array');
        $userid   = $this->input->post->get('userid', array(0), 'array');
        $username = $this->input->post->get('username', array(0), 'array');

        $newsletter_id = $this->input->get('newsletter_id');

        $tmpcid      = array_chunk($cid, Redshop::getConfig()->get('NEWSLETTER_MAIL_CHUNK'));
        $tmpuserid   = array_chunk($userid, Redshop::getConfig()->get('NEWSLETTER_MAIL_CHUNK'));
        $tmpusername = array_chunk($username, Redshop::getConfig()->get('NEWSLETTER_MAIL_CHUNK'));

        $session->set('subscribers', $tmpcid);
        $session->set('subscribersuid', $tmpuserid);
        $session->set('subscribersuname', $tmpusername);
        $session->set('incNo', 1);

        $this->setRedirect(
            'index.php?option=com_redshop&view=newsletters&layout=previewlog&newsletter_id=' . $newsletter_id
        );
    }

    /**
     * Method send recursive newsletter
     *
     * @return  void
     * @throws  Exception
     */
    public function sendRecursiveNewsletter()
    {
        $session      = JFactory::getSession();
        $newsletterId = $this->input->get('newsletter_id');

        /** @var RedshopModelNewsletters $model */
        $model = $this->getModel('newsletters');

        $subscribers      = $session->get('subscribers');
        $subscribersuid   = $session->get('subscribersuid');
        $subscribersuname = $session->get('subscribersuname');
        $incNo            = $session->get('incNo');

        $cid      = array();
        $user_id  = array();
        $username = array();

        if (!empty($subscribers)) {
            $cid = $subscribers[0];
            unset($subscribers[0]);
            $subscribers = array_merge(array(), $subscribers);
        }

        if (!empty($subscribersuid)) {
            $user_id = $subscribersuid[0];
            unset($subscribersuid[0]);
            $subscribersuid = array_merge(array(), $subscribersuid);
        }

        if (!empty($subscribersuname)) {
            $username = $subscribersuname[0];
            unset($subscribersuname[0]);
            $subscribersuname = array_merge(array(), $subscribersuname);
        }

        $retuser = $model->newsletterEntry($cid, $user_id, $username);

        $responcemsg = "";

        for ($i = 0, $in = count($cid); $i < $in; $i++) {
            $subscriber  = $model->getNewsletterSubscriber($newsletterId, $cid[$i]);

            $responcemsg .= "<div>" . $incNo . ": " . $subscriber[$incNo - 1]->name . "( " . $subscriber[$incNo - 1]->email . " ) -> ";

            if ($retuser[$i]) {
                $responcemsg .= "<span style='color: #00ff00'>" . JText::_(
                        'COM_REDSHOP_NEWSLETTER_SENT_SUCCESSFULLY'
                    ) . "</span>";
            } else {
                $responcemsg .= "<span style='color: #ff0000'>" . JText::_(
                        'COM_REDSHOP_NEWSLETTER_MAIL_NOT_SENT'
                    ) . "</span>";
            }

            $responcemsg .= "</div>";
            $incNo++;
        }

        $session->set('subscribers', $subscribers);
        $session->set('subscribersuid', $subscribersuid);
        $session->set('subscribersuname', $subscribersuname);
        $session->set('incNo', $incNo);

        if (count($cid) == 0) {
            $session->clear('subscribers');
            $session->clear('subscribersuid');
            $session->clear('subscribersuname');
            $session->clear('incNo');
        }

        $responcemsg = "<div id='sentresponse'>" . $responcemsg . "</div>";
        echo $responcemsg;

        JFactory::getApplication()->close();
    }
}