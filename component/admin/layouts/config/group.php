<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * =========================
 *
 * @var  array   $displayData  List of data
 * @var  string  $title        Title of this config field
 * @var  string  $content      Description of this config field.
 */
extract($displayData);
?>
<div class="box box-primary">
    <div class="box-header with-border"><h3 class="text-primary center"><?php echo $title ?></h3></div>
    <div class="box-body"><?php echo $content ?></div>
</div>
