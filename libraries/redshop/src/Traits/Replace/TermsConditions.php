<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Traits\Replace;

defined('_JEXEC') || die;

/**
 * For classes extends class RedshopTagsAbstract
 *
 * @since  3.0
 */
trait TermsConditions
{
    /**
     * @param   string  $templateDesc
     * @param   int     $itemId
     *
     * @return string|string[]
     * @since 3.0
     */
    public function replaceTermsConditions($templateDesc = "", $itemId = 1)
    {
        if (strpos($templateDesc, "{terms_and_conditions") !== false) {
            $user    = \JFactory::getUser();
            $session = \JFactory::getSession();
            $auth    = $session->get('auth');
	        $userId = $user->id ?: ($auth['users_info_id'] < 0 ? $auth['users_info_id'] : 0);
            $userInfo = \RedshopHelperUser::getUserInformation($userId);
            $termsLeftFinal = "";

            if (strpos($templateDesc, "{terms_and_conditions:") !== false && strpos($templateDesc, "}") !== false) {
                $termsLeftOne   = explode("{terms_and_conditions:", $templateDesc);
                $termsLeftTwo   = explode("}", $termsLeftOne[1]);
                $termsLeftThree = explode(":", $termsLeftTwo[0]);
                $termsLeftFinal = $termsLeftThree[0];
            }

            $finalTag       = ($termsLeftFinal != "") ? "{terms_and_conditions:$termsLeftFinal}" : "{terms_and_conditions}";
            $termsCondition = '';

            if (\Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 0 || (\Redshop::getConfig()->get(
                        'SHOW_TERMS_AND_CONDITIONS'
                    ) == 1 && ((is_object($userInfo) && $userInfo->accept_terms_conditions == 0) || count((array) $userInfo) == 0))) {
                $finalWidth  = "500";
                $finalHeight = "450";

                if ($termsLeftFinal != "") {
                    $dimension = explode(" ", $termsLeftFinal);

                    if (count($dimension) > 0) {
                        if (strpos($dimension[0], "width") !== false) {
                            $width      = explode("width=", $dimension[0]);
                            $finalWidth = (isset($width[1])) ? $width[1] : "500";
                        } else {
                            $height      = explode("height=", $dimension[0]);
                            $finalHeight = (isset($height[1])) ? $height[0] : "450";
                        }

                        if (strpos($dimension[1], "height") !== false) {
                            $height      = explode("height=", $dimension[1]);
                            $finalHeight = (isset($height[1])) ? $height[1] : "450";
                        } else {
                            $width      = explode("width=", $dimension[1]);
                            $finalWidth = (isset($width[1])) ? $width[1] : "500";
                        }
                    }
                }

                $url        = \JURI::base();
                $articleUrl = $url . "index.php?option=com_content&amp;view=article&amp;id=" . \Redshop::getConfig(
                    )->get('TERMS_ARTICLE_ID') . "&Itemid=" . $itemId . "&tmpl=component";

                $termsCondition = \RedshopLayoutHelper::render(
                    'tags.common.terms_and_conditions',
                    array(
                        'articleUrl'  => $articleUrl,
                        'finalWidth'  => $finalWidth,
                        'finalHeight' => $finalHeight
                    ),
                    '',
                    \RedshopLayoutHelper::$layoutOption
                );
            }

            $templateDesc = str_replace($finalTag, $termsCondition, $templateDesc);
        }

        return $templateDesc;
    }
}