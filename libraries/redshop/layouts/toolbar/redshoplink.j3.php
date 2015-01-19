<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSEs
 */

defined('_JEXEC') or die;

$doTask = $displayData['doTask'];
$class  = $displayData['class'];
$text   = $displayData['text'];
$target = $displayData['target'];
?>
<button onclick="window.open('<?php echo $doTask; ?>', '<?php echo $target; ?>');" class="btn btn-small">
	<span class="<?php echo $class; ?>"></span>
	<?php echo $text; ?>
</button>
