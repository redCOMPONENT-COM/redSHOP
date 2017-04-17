<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_fb_albums
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$path = __DIR__ . '/helpers/fb.php';

include_once $path;

$output = ModFbAlbumsHelper::getList($params);

if (isset($output->error))
{
    echo $output->error->message;
}
else{
    $class_sfx	= htmlspecialchars($params->get('class_sfx'));
    JHtml::stylesheet('mod_fb_albums/css.css', false, true);

    $type = $params->get('display', 0);

    switch ($type)
    {
        case 1:
            require JModuleHelper::getLayoutPath('mod_fb_albums', $params->get('layout', 'posts'));
            break;
        default:
            require JModuleHelper::getLayoutPath('mod_fb_albums', $params->get('layout', 'default'));
            break;
    }
}
