<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\Entity\EntityCollection;

defined('_JEXEC') or die;

/**
 * Twig helper for redSHOP
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperTwig
{
	/**
	 * Method for render help block for Twig
	 *
	 * @param   string $class Twig class
	 * @param   string $tag   Template tag
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function renderTwigHelpBlock($class = '', $tag = '')
	{
		if (empty($class))
		{
			return '';
		}

		$tag          = empty($tag) ? $class : $tag;
		$isCollection = $class === 'collection';
		$class        = $isCollection ? '\\Redshop\\Entity\\CoreEntityCollection' : '\\Redshop\\Entity\\Twig\\' . ucfirst($class);

		$reflect = new ReflectionClass($class);
		$methods = $reflect->getMethods(ReflectionMethod::IS_PUBLIC);

		if (empty($methods))
		{
			return '';
		}

		$factory           = \phpDocumentor\Reflection\DocBlockFactory::createInstance();
		$result            = array();
		$methodsExclude = array('get', 'loadarray', 'next', 'rewind', 'set', 'add', 'getall', 'toobjects',
			'current', 'clear', 'key', 'tofieldarray', 'geteditlink', 'geteditlinkwithreturn');

		foreach ($methods as $method)
		{
			$methodName = strtolower($method->getName());

			if (0 === strpos($methodName, '__') || in_array($methodName, $methodsExclude))
			{
				continue;
			}

			if (!$isCollection && (0 !== strpos($methodName, 'get') || empty(substr($methodName, 3))))
			{
				continue;
			}

			$docBlock = $factory->create($method->getDocComment());

			$key = $isCollection ? $methodName : substr($methodName, 3);

			$result[strtolower($key)] = array('doc' => $docBlock, 'params' => $method->getParameters());
		}

		return RedshopLayoutHelper::render('templates.twig_help', array('tag' => $tag, 'methods' => $result));
	}

	/**
	 * Method for get supported twig template sections
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getSupportedTemplateSections()
	{
		return array('giftcard_list');
	}

	/**
	 * Method for get supported twig template sections
	 *
	 * @param   string  $section  Template section
	 * @param   string  $content  Template content
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  Exception
	 */
	public static function liveRender($section, $content)
	{
		if (!in_array($section, self::getSupportedTemplateSections()))
		{
			return '';
		}

		/** @var RedshopModelGiftcards $model */
		$model = RedshopModel::getInstance('Giftcards', 'RedshopModel');
		$model->setState('list.limit', 3);
		$giftcards = $model->getItems();

		// Twig process
		$loader = new Twig_Loader_Array(
			array(
				'giftcard-list-demo.html' => $content
			)
		);

		$twig = Redshop::getTwig($loader);

		$items = new EntityCollection;
		$items->loadArray($giftcards, 'RedshopEntityGiftcard', 'giftcard_id');

		return $twig->render(
			'giftcard-list-demo.html',
			array(
				'giftcards' => $items->toTwigEntities(),
				'page'      => $_SERVER
			)
		);
	}
}
