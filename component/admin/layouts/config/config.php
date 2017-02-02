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
 * @var  array   $displayData List of data
 * @var  string  $title       Title of this config field
 * @var  string  $desc        Description of this config field.
 * @var  string  $field       HTML content of field.
 * @var  boolean $line        True for show line.
 */
extract($displayData);

$line = (isset($line)) ? $line : true;
?>
<div class="row">
    <div class="form-group">
        <label class="col-md-4 hasPopover" data-content="<?php echo $desc ?>"><?php echo $title ?></label>
        <div class="col-md-8"><?php echo $field ?></div>
    </div>
</div>
<?php if ($line): ?>
    <hr/>
<?php endif; ?>
