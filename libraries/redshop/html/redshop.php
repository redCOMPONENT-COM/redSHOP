<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2022 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

/**
 * Class JHtmlRedshop
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class JHtmlRedshop
{
	/**
	 * Creates a tooltip with an image as button
	 *
	 * @param   string  $tooltip  The tip string.
	 * @param   mixed   $title    The title of the tooltip or an associative array with keys contained in
	 *                            {'title','image','text','href','alt'} and values corresponding to parameters of the same name.
	 * @param   string  $image    The image for the tip, if no text is provided.
	 * @param   string  $text     The text for the tip.
	 * @param   string  $href     A URL that will be used to create the link.
	 * @param   string  $alt      The alt attribute for img tag.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function tooltip(
		string $tooltip,
		string $title = null,
		string $image = 'com_redshop/tooltip.png',
		string $text = null,
		string $href = null,
		string $alt = 'Tooltip'
	): string
	{
		if (!$text)
		{
			$alt = htmlspecialchars($alt, ENT_COMPAT, 'UTF-8');
			$text = HTMLHelper::image($image, $alt, null, true);
		}

		if ($href)
		{
			$tip = '<a href="' . $href . '">' . $text . '</a>';
		}
		else
		{
			$tip = $text;
		}

		HTMLHelper::_('bootstrap.popover');

		$attr = '';

		if ($title !== $tooltip)
		{
			$attr = ' title="' . htmlspecialchars(trim($title, ':')) . '"';
		}

		if (version_compare(JVERSION, '4.0', '<'))
		{
			$attr .= ' data-content="' . htmlspecialchars($tooltip) . '"';
		}
		else
		{
			$attr .= ' data-bs-content="' . htmlspecialchars($tooltip) . '"';
		}

		return '<span class="hasPopover" ' . $attr . '>' . $tip . '</span>';
	}
}
