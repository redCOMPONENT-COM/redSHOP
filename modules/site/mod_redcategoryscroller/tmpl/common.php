<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<?php if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right')): ?>
	<table><tr>
<?php endif ?>

<?php $i = 0 ?>

<?php foreach ($rows as $row): ?>

	<?php if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right')): ?>
		<td style="vertical-align:top;padding: 2px 5px 2px 5px;"><table width="<?php echo $this->boxwidth ?>">
	<?php endif ?>

	<?php echo $this->ShowCategory($row, $i); ?>

	<?php if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right')): ?>
		</table></td>
	<?php else: ?>
		<?php for ($i = 0; $i < $this->ScrollLineCharTimes; $i++): ?>
			<?php echo $this->ScrollLineChar; ?>
		<?php endfor ?>
	<?php endif ?>

	<?php $i++; ?>

<?php endforeach ?>

<?php if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right')): ?>
	</tr></table>
<?php endif ?>
