<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

defined('_JEXEC') or die;

/**
 * Mail Catalog helper
 *
 * @since  2.1.0
 */
class Catalog
{
	/**
	 * Send catalog request
	 *
	 * @param   mixed  $catalog  Catalog data
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public static function sendRequest($catalog)
	{
		$catalog     = is_array($catalog) ? (object) $catalog : $catalog;
		$db          = \JFactory::getDbo();
		$mailSection = "catalog";
		$mailInfo    = Helper::getTemplate(0, $mailSection);
		$dataAdd     = "";
		$subject     = "";
		$mailBcc     = null;

		if (count($mailInfo) > 0)
		{
			$dataAdd = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		$config   = \JFactory::getConfig();
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_media'))
			->where($db->qn('media_section') . ' = ' . $db->quote('catalog'))
			->where($db->qn('media_type') . ' = ' . $db->quote('document'))
			->where($db->qn('section_id') . ' = ' . (int) $catalog->catalog_id)
			->where($db->qn('published') . ' = 1');

		$catalogMedias = $db->setQuery($query)->loadObjectList();
		$attachment    = array();

		foreach ($catalogMedias as $catalogMedia)
		{
			$attachment[] = REDSHOP_FRONT_DOCUMENT_RELPATH . 'catalog/' . $catalogMedia->media_name;
		}

		$dataAdd = str_replace("{name}", $catalog->name, $dataAdd);

		Helper::imgInMail($dataAdd);

		return Helper::sendEmail(
			$from, $fromName, $catalog->email, $subject, $dataAdd, 1, null,
			$mailBcc, $attachment, $mailSection, func_get_args()
		);
	}
}
