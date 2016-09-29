<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$items = LeftMenu::render(true);

$menuhide = explode(",", $this->config->get('MENUHIDE'));

?>

<legend><?php echo JText::_('COM_REDSHOP_MENUHIDE'); ?></legend>

<?php if(isset($items)) { ?>
<ul id="menuhide">
	<?php foreach ($items as $group => $sections) : ?>
		<li>
			<label>
				<input type="checkbox"
					value="<?php echo $group ?>" name="menuhide[]"
					<?php echo (in_array($group, $menuhide) ? 'checked' : ''); ?>
				>
				<?php echo JText::_('COM_REDSHOP_' . $group); ?>
			</label>

			<ul>
			<?php foreach ($sections as $sectionKey => $section) : ?>
				<li>
					<label>
						<input type="checkbox"
							value="<?php echo $section->title ?>" name="menuhide[]"
							<?php echo (in_array($section->title, $menuhide) ? 'checked' : ''); ?>
						>
						<?php echo JText::_($section->title); ?>
					</label>

					<ul>
					<?php foreach ($section->items as $item) : ?>
						<li>
							<label>
								<input type="checkbox"
									value="<?php echo $item->title ?>" name="menuhide[]"
									<?php echo (in_array($item->title, $menuhide) ? 'checked' : ''); ?>
								>
								<?php echo JText::_($item->title)?>
							</label>
						</li>
					<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
			</ul>
		</li>
	<?php endforeach; ?>
</ul>

<script type="text/javascript">
(function($){
	$(document).ready(function()
	{
		$('#menuhide').find('input[type=checkbox]').click(function(){
			console.log(this.checked);

			$(this).parent().parent().parent().find('input[type=checkbox]').prop('checked', this.checked);
		});
	});
})(jQuery);
</script>

<?php } ?>
