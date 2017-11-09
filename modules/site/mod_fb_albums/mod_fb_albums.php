<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_fb_albums
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

include_once __DIR__ . '/helpers/fb.php';

$output = ModFbAlbumsHelper::getList($params);

if (isset($output->error))
{
	echo $output->error->message;
}
else
{
	$class_sfx = htmlspecialchars($params->get('class_sfx'));

	JHtml::stylesheet('mod_fb_albums/css.css', false, true);

	$type = $params->get('display', 0);

	$layout = $type === 1 ? 'posts' : 'default';

	require JModuleHelper::getLayoutPath('mod_fb_albums', $params->get('layout', $layout));
}
