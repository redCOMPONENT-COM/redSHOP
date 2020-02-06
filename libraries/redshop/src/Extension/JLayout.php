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

use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * JLayout integration for Twig.
 *
 * @since  2.1.5
 */
final class JLayout extends AbstractExtension
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
			new TwigFunction('jlayout', [$this, 'getFileLayout']),
			new TwigFunction('jlayout_render', [LayoutHelper::class, 'render'], $options),
			new TwigFunction('jlayout_debug', [LayoutHelper::class, 'debug'], $options)
		];
	}

	/**
	 *
	 * @return FileLayout
	 *
	 * @throws \ReflectionException
	 * @since 2.1.5
	 */
	public function getFileLayout() : FileLayout
	{
		$class = new \ReflectionClass(FileLayout::class);

		return $class->newInstanceArgs(func_get_args());
	}

	/**
	 *
	 * @return string
	 *
	 * @since 2.1.5
	 */
	public function getName() : string
	{
		return 'jlayout';
	}
}
