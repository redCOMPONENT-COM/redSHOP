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

use Joomla\CMS\Document\Renderer\Html\ModuleRenderer;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Module position loader for Twig.
 *
 * @since  __DEPLOY_VERSION__
 */
final class JPosition extends AbstractExtension
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
			new TwigFunction('jposition', [$this, 'render'], ['is_safe' => ['html']])
		];
	}

	/**
	 * @param   string  $position
	 * @param   array   $attribs
	 *
	 * @return string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function render(string $position, array $attribs = []) : string
	{
		$modules  = ModuleHelper::getModules($position);
		$renderer = $this->getModuleRenderer();
		$html     = '';

		foreach ($modules as $module)
		{
			$html .= $renderer->render($module, $attribs);
		}

		return $html;
	}

	/**
	 *
	 * @return ModuleRenderer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	protected function getModuleRenderer() : ModuleRenderer
	{
		return Factory::getDocument()->loadRenderer('module');
	}

	/**
	 *
	 * @return string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getName() : string
	{
		return 'jposition';
	}
}
