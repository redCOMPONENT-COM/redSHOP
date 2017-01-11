<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$items = RedshopMenuLeft_Menu::render(true);

$menuhide = explode(",", $this->config->get('MENUHIDE'));
?>

<legend><?php echo JText::_('COM_REDSHOP_MENUHIDE') ?></legend>

<?php if (isset($items)): ?>

<ul id="menuhide">
	<?php foreach ($items as $group => $sections) : ?>
		<?php if (is_object($sections)): ?>
			<li>
				<?php $isHide = in_array($sections->title, $menuhide); ?>
				<label <?php echo $isHide ? 'class="text-danger"' : '' ?>>
					<input type="checkbox" value="<?php echo $sections->title ?>" name="menuhide[]" <?php echo $isHide ? 'checked' : '' ?>>
					<?php echo JText::_($sections->title); ?>
				</label>
			</li>
			<?php continue; ?>
		<?php endif; ?>
		<?php foreach ($sections['items'] as $sectionKey => $section) : ?>
		<li>
			<?php $isHide = in_array($section->title, $menuhide); ?>
			<label <?php echo $isHide ? 'class="text-danger"' : '' ?>>
				<input type="checkbox" value="<?php echo $section->title ?>" name="menuhide[]" <?php echo $isHide ? 'checked' : '' ?>>
				<?php echo JText::_($section->title); ?>
			</label>
			<ul>
			<?php foreach ($section->items as $item) : ?>
				<li>
					<?php $isHide = in_array($item->title, $menuhide); ?>
					<label <?php echo $isHide ? 'class="text-danger"' : '' ?>>
						<input type="checkbox" value="<?php echo $item->title ?>" name="menuhide[]" <?php echo $isHide ? 'checked' : '' ?>>
					   <?php echo JText::_($item->title)?>
					</label>
				</li>
			<?php endforeach; ?>
			</ul>
		</li>
		<?php endforeach; ?>
	<?php endforeach; ?>
</ul>

<script type="text/javascript">
(function($){
	$(document).ready(function()
	{
		$('#menuhide').find('input[type=checkbox]').click(function(){
			var $self = $(this);
			var check = $self.prop("checked");

			if ($(this).parent().parent().find('ul').length)
			{
				var $childs = $(this).parent().parent().children('ul').find('input[type=checkbox]');
				$childs.prop('checked', this.checked);

				if (check)
					$childs.parent().addClass("text-danger");
				else
					$childs.parent().removeClass("text-danger");
			}

			if (check)
				$(this).parent().addClass("text-danger");
			else
				$(this).parent().removeClass("text-danger");
		});
	});
})(jQuery);
</script>

<?php endif; ?>
