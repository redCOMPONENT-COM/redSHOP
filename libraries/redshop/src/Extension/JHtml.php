<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Extension
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Phproberto\Joomla\Twig\Extension;

defined('_JEXEC') || die;

use Joomla\CMS\HTML\HTMLHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * JHtml integration for Twig.
 *
 * @since  1.0.0
 */
final class JHtml extends AbstractExtension
{
	/**
	 * Inject functions.
	 *
	 * @return  array
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
	 * Get the name of this extension.
	 *
	 * @return  string
	 */
	public function getName() : string
	{
		return 'jhtml';
	}
}
