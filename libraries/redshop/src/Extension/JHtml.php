<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Extension;

defined('_JEXEC') || die;

use Joomla\CMS\HTML\HTMLHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * JHtml integration for Twig.
 *
 * @since  2.1.5
 */
final class JHtml extends AbstractExtension
{
	/**
	 *
	 * @return array
	 *
	 * @since 2.1.5
	 */
	public function getFunctions() : array
	{
		$options = [
			'is_safe' => ['html']
		];

		return [
			new TwigFunction('jhtml', [HTMLHelper::class, '_'], $options)
		];
	}

	/**
	 *
	 * @return string
	 *
	 * @since 2.1.5
	 */
	public function getName() : string
	{
		return 'jhtml';
	}
}
