<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSEs
 */

defined('_JEXEC') or die;

$doTask = $displayData['doTask'];
$class  = $displayData['class'];
$text   = $displayData['text'];
$target = $displayData['target'];
?>
<a href="<?php echo $doTask; ?>"
	  target="<?php echo $target; ?>" class="btn btn-small">
	<span class="<?php echo $class; ?>"></span>
	<?php echo $text; ?>
</a>
