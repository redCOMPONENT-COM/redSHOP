<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


namespace Redshop\Extension;

defined('_JEXEC') || die;

use Joomla\CMS\Language\Language;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * JLanguage integration for Twig.
 *
 * @since  __DEPLOY_VERSION__
 */
final class JLanguage extends AbstractExtension
{
	/**
	 *
	 * @return array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('jlang', [Language::class, 'getInstance'])
		];
	}

	/**
	 *
	 * @return string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getName() : string
	{
		return 'jlang';
	}
}
