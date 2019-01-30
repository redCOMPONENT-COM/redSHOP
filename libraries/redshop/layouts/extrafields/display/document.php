<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @var   array   $displayData  Layout data
 * @var   object  $data         Data object
 * @var   object  $field        Field object
 * @var   string  $link         Document link
 * @var   string  $title        Document title
 */
extract($displayData);
?>
<a href="<?php echo $link; ?>" target="_blank"><?php echo $title; ?></a>
