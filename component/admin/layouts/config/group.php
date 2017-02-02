<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * =========================
 *
 * @var  array  $displayData List of data
 * @var  string $title       Title of this config field
 * @var  string $content     Description of this config field.
 */
extract($displayData);
?>
<div class="panel panel-primary form-vertical">
    <div class="panel-heading"><h3><?php echo $title ?></h3></div>
    <div class="panel-body"><?php echo $content ?></div>
</div>
