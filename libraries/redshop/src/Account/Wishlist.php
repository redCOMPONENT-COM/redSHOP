<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Account;

defined('_JEXEC') or die;

/**
 * Account wishlist
 *
 * @since  2.1.0
 */
class Wishlist
{
	/**
	 * Method for send wishlist
	 *
	 * @param   array $post Wishlist data
	 *
	 * @return  boolean
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function send($post)
	{
		$userId     = \JFactory::getUser()->id;
		$wishlistId = \JFactory::getApplication()->input->getInt('wishlist_id');
		$emailTo    = $post['emailto'];
		$sender     = $post['sender'];
		$email      = $post['email'];
		$subject    = $post['subject'];
		$itemId     = $post['Itemid'];
		$db         = \JFactory::getDbo();
		$query      = $db->getQuery(true);

		// Get data from database if not than fetch from session
		if ($userId && $wishlistId)
		{
			$query->select('DISTINCT w.*, p.*')
				->from($db->qn('#__redshop_wishlist', 'w'))
				->leftJoin($db->qn('#__redshop_wishlist_product', 'pw') . ' ON ' . $db->qn('w.wishlist_id') . ' = ' . $db->qn('pw.wishlist_id'))
				->leftJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pw.product_id'))
				->where($db->qn('w.user_id') . ' = ' . $userId)
				->where($db->qn('w.wishlist_id') . ' = ' . $wishlistId);
		}
		else
		{
			// Add this code to send wishlist while user is not loged in ...
			$productIds = array();

			for ($index = 1; $index < $_SESSION['no_of_prod']; $index++)
			{
				$productIds[] = (int) $_SESSION['wish_' . $index]->product_id;
			}

			$productIds[] = (int) $_SESSION['wish_' . $index]->product_id;

			$query->select('DISTINCT p.*')
				->from($db->qn('#__redshop_product', 'p'))
				->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')');
		}

		$myWishList   = $db->setQuery($query)->loadObjectList();
		$data         = "";
		$mailBcc      = null;
		$wishlistBody = \Redshop\Mail\Helper::getTemplate(0, "mywishlist_mail");
		$dataAdd      = '';

		if (count($wishlistBody) > 0)
		{
			$wishlistBody = $wishlistBody[0];
			$data         = $wishlistBody->mail_body;

			if (trim($wishlistBody->mail_bcc) != "")
			{
				$mailBcc = explode(",", $wishlistBody->mail_bcc);
			}
		}

		if ($data)
		{
			$dataAdd = self::prepare($data, $myWishList, $emailTo, $sender);
		}
		elseif (!empty($myWishList))
		{
			$dataAdd = '';

			foreach ($myWishList as $row)
			{
				$dataAdd .= '<div class="redProductWishlist">';

				$productName = $row->product_name;
				$link        = \JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $itemId, false);
				$thumbImage  = \Redshop\Product\Image\Image::getImage(
					$row->product_id,
					$link,
					\Redshop::getConfig()->get('THUMB_WIDTH'),
					\Redshop::getConfig()->get('THUMB_HEIGHT')
				);

				$dataAdd .= $thumbImage;
				$dataAdd .= "<div><a href='" . $link . "' >" . $productName . '</a></div>';
				$dataAdd .= '</div>';
			}
		}

		\Redshop\Mail\Helper::imgInMail($dataAdd);

		return \JFactory::getMailer()->sendMail($email, $sender, $emailTo, $subject, $dataAdd, true, null, $mailBcc);
	}

	/**
	 * Method for prepare mail body
	 *
	 * @param   string $content    Template content
	 * @param   array  $myWishList My wishlist data
	 * @param   string $emailTo    Email to
	 * @param   string $sender     Sender
	 *
	 * @return mixed|string
	 * @throws \Exception
	 */
	protected static function prepare($content, $myWishList, $emailTo, $sender)
	{
		$templateD1          = explode("{product_loop_start}", $content);
		$templateD2          = explode("{product_loop_end}", $templateD1[1]);
		$wishlistDescription = $templateD2[0];

		if (strpos($content, '{product_thumb_image_2}') !== false)
		{
			$tag         = '{product_thumb_image_2}';
			$thumbHeight = \Redshop::getConfig()->get('THUMB_HEIGHT_2');
			$thumbWidth  = \Redshop::getConfig()->get('THUMB_WIDTH_3');
		}
		elseif (strpos($content, '{product_thumb_image_3}') !== false)
		{
			$tag         = '{product_thumb_image_3}';
			$thumbHeight = \Redshop::getConfig()->get('THUMB_HEIGHT_3');
			$thumbWidth  = \Redshop::getConfig()->get('THUMB_WIDTH_3');
		}
		elseif (strpos($content, '{product_thumb_image_1}') !== false)
		{
			$tag         = '{product_thumb_image_1}';
			$thumbHeight = \Redshop::getConfig()->get('THUMB_HEIGHT');
			$thumbWidth  = \Redshop::getConfig()->get('THUMB_WIDTH');
		}
		else
		{
			$tag         = '{product_thumb_image}';
			$thumbHeight = \Redshop::getConfig()->get('THUMB_HEIGHT');
			$thumbWidth  = \Redshop::getConfig()->get('THUMB_WIDTH');
		}

		$tmpTemplate = '';

		if (count($myWishList))
		{
			foreach ($myWishList as $row)
			{
				$itemId       = \RedshopHelperRouter::getItemId($row->product_id);
				$link         = \JRoute::_(
					'index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . (int) $itemId,
					true,
					-1
				);
				$thumbImage   = \Redshop\Product\Image\Image::getImage($row->product_id, $link, $thumbWidth, $thumbHeight);
				$productName  = $row->product_name;
				$wishlistData = str_replace($tag, $thumbImage, $wishlistDescription);
				$wishlistData = str_replace('{product_name}', $productName, $wishlistData);

				// Attribute ajax change
				if (!$row->not_for_sale)
				{
					$wishlistData = \RedshopHelperProductPrice::getShowPrice($row->product_id, $wishlistData);
				}
				else
				{
					$wishlistData = str_replace("{product_price}", "", $wishlistData);
					$wishlistData = str_replace("{price_excluding_vat}", "", $wishlistData);
					$wishlistData = str_replace("{product_price_table}", "", $wishlistData);
					$wishlistData = str_replace("{product_old_price}", "", $wishlistData);
					$wishlistData = str_replace("{product_price_saving}", "", $wishlistData);
					$wishlistData = str_replace("{product_price_saving_percentage}", "", $wishlistData);
				}

				$tmpTemplate .= $wishlistData;
			}
		}

		$content = $templateD1[0] . $tmpTemplate . $templateD2[1];

		$name    = explode('@', $emailTo);
		$content = str_replace('{from}', $sender, $content);
		$content = str_replace('{name}', $name[0], $content);
		$content = str_replace('{from_name}', $sender, $content);

		return $content;
	}
}
