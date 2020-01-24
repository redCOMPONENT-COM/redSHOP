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
 * @since  1.0.0
 */
final class JPosition extends AbstractExtension
{
	/**
	 * Inject functions.
	 *
	 * @return  array
	 */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('jposition', [$this, 'render'], ['is_safe' => ['html']])
		];
	}

	/**
	 * Render the module position.
	 *
	 * @param   string  $position  Position to render
	 * @param   array   $attribs   Associative array of values
	 *
	 * @return  string
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
	 * Get an instance of the module renderer.
	 *
	 * @return  ModuleRenderer
	 *
	 * @codeCoverageIgnore
	 */
	protected function getModuleRenderer() : ModuleRenderer
	{
		return Factory::getDocument()->loadRenderer('module');
	}

	/**
	 * Get the name of this extension.
	 *
	 * @return  string
	 */
	public function getName() : string
	{
		return 'jposition';
	}
}
