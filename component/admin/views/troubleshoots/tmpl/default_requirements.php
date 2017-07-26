<?php foreach ($this->list['requirements']->requirements as $key => $data): ?>
	<blockquote><?php echo $key; ?></blockquote>

	<?php foreach ($data as $phpValue => $field): ?>
		<div class="row">
			<span class="label label-default"><?php echo $phpValue; ?>: <?php echo $field->currentValue;?></span>
			<?php if (isset($field->messages)): ?>
				<?php foreach ($field->messages as $message ): ?>
					<span class="label label-danger"><?php echo $message; ?></span>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
<?php endforeach; ?>