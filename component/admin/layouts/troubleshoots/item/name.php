<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */
$item = $displayData['item'];
?>

<span class="text"><?php echo $item->getOriginal('fullpath'); ?></span>
<br />
<!-- Render modified time if possible -->
<span class="label label-default"><?php echo $item->getModifiedTime();?></span>
<span class="label label-primary"><?php echo $item->getType();?></span>