<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   string  $link   Document link
 * @param   string  $title  Document title
 */
extract($displayData);
?>
<a href="<?php echo $link; ?>" target="_blank"><?php echo $title; ?></a>
