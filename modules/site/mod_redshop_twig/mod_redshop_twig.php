<?php
/**
 * @package     Phproberto.Module
 * @subpackage  Site.mod_phproberto.login
 *
 * @copyright  Copyright (C) 2017-2018 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

defined('_JEXEC') || die;
require './libraries/vendor/composer/ClassLoader.php';
// This will allow that we start using our namespaced classes
$loader = new ClassLoader();
$loader->setPsr4('Redshop\\Twig\\Module\\Site\\Twig\\', __DIR__ . '/src');
$loader->register(true);

var_dump(loader);
exit;

require('src/RedShopTwigModule.php');

$modInstance = new RedShopTwigModule($params, $module);

$layout = '@module/mod_redshop_twig/' . $params->get('layout', 'default') . '.html.twig';

echo $modInstance->render($layout);