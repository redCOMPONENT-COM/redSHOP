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

use Joomla\CMS\Helper\ModuleHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Module helper access for Twig.
 *
 * @since  2.1.5
 */
final class JModuleHelper extends AbstractExtension
{
	/**
	 *
	 * @return array
	 *
	 * @since 2.1.5
	 */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('jmodule_get_module', [ModuleHelper::class, 'getModule']),
			new TwigFunction('jmodule_get_modules', [ModuleHelper::class, 'getModules']),
			new TwigFunction('jmodule_render_module', [ModuleHelper::class, 'renderModule'], ['is_safe' => ['html']])
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
		return 'jmodule';
	}
}
