<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Newsletter;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Newsletter Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param int $newsletterId
     *
     * @return array|mixed
     */
    public static function getNewsletterList($newsletterId = 0)
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('n.*,CONCAT(n.name," (",n.subject,")") AS text')
            ->from($db->qn('#__redshop_newsletter', 'n'));

        if ($newsletterId != 0) {
            $query->where($db->qn('n.id') . ' = ' . $db->q($newsletterId));
        }

        return $db->setQuery($query)->loadObjectList();
    }

    public static function getNewsletterTracker($newsletterId = 0)
    {
        $data  = self::getNewsletterList($newsletterId);
        $db    = Factory::getDbo();
        $query = $db->getQuery(true);

        $qs = array();

        for ($d = 0, $dn = count($data); $d < $dn; $d++) {
            $query->clear()
                ->select('COUNT(*) AS total')
                ->from($db->qn('#__redshop_newsletter_tracker'))
                ->where($db->qn('newsletter_id') . ' = ' . $db->q($data[$d]->id));

            $totalResult = $db->setQuery($query)->loadResult();

            if ( ! $totalResult) {
                $totalResult = 0;
            }

            if ($newsletterId != 0) {
                $totalRead    = self::getReadNewsletter($data[$d]->id);
                $qs[0]        = new \stdClass;
                $qs[0]->xdata = \JText::_('COM_REDSHOP_NO_OF_UNREAD_NEWSLETTER');
                $qs[0]->ydata = $totalResult - $totalRead;
                $qs[1]        = new \stdClass;
                $qs[1]->xdata = \JText::_('COM_REDSHOP_NO_OF_READ_NEWSLETTER');
                $qs[1]->ydata = $totalRead;
            } else {
                $qs[$d]        = new \stdClass;
                $qs[$d]->xdata = $data[$d]->name;
                $qs[$d]->ydata = $totalResult;
            }
        }

        if ($newsletterId != 0) {
            $return = array($qs, $data[0]->name);
        } else {
            $return = array($qs, \JText::_('COM_REDSHOP_NO_OF_SENT_NEWSLETTER'));
        }

        return $return;
    }

    /**
     * @param $newsletterId
     *
     * @return int
     */
    public static function getReadNewsletter($newsletterId)
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('COUNT(*) AS total')
            ->from($db->qn('#__redshop_newsletter_tracker'))
            ->where($db->qn('newsletter_id') . ' = ' . $db->q($newsletterId))
            ->where($db->qn('read') . ' = 1');

        $result = $db->setQuery($query)->loadObject();

        if ( ! $result) {
            $result->total = 0;
        }

        return $result->total;
    }
}