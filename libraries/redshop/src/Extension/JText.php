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

use Joomla\CMS\Language\Text;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * JText integration for Twig.
 *
 * @since  1.0.0
 */
final class JText extends AbstractExtension
{
	/**
	 *
	 * @return array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('jtext', [Text::class, '_']),
			new TwigFunction('jtext_sprintf', [Text::class, 'sprintf']),
		];
	}

	/**
	 *
	 * @return string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getName() : string
	{
		return 'jtext';
	}
}
