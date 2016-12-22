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
				<label>
					<input type="checkbox" value="<?php echo $sections->title ?>" name="menuhide[]"
						<?php echo in_array($sections->title, $menuhide) ? 'checked' : '' ?>>
					<?php echo JText::_($sections->title); ?>
				</label>
			</li>
			<?php continue; ?>
		<?php endif; ?>
		<li>
			<ul>
				<?php foreach ($sections['items'] as $sectionKey => $section) : ?>
				<li>
					<label>
						<input type="checkbox"
							value="<?php echo $section->title ?>" name="menuhide[]"
							<?php echo in_array($section->title, $menuhide) ? 'checked' : '' ?>
						>
						<?php echo JText::_($section->title); ?>
					</label>

					<ul>
					<?php foreach ($section->items as $item) : ?>
						<li>
							<label>
								<input type="checkbox"
									value="<?php echo $item->title ?>" name="menuhide[]"
									<?php echo in_array($item->title, $menuhide) ? 'checked' : '' ?>
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
			if ($(this).parent().parent().find('ul').length) {
				$(this).parent().parent().children('ul').find('input[type=checkbox]').prop('checked', this.checked);
			}
		});
	});
})(jQuery);
</script>

<?php endif; ?>
