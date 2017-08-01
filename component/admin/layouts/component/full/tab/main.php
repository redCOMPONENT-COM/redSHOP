<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);

?>

<div class="row">
	<div class="col-sm-2">
		<div class="box">
			<div class="box-body no-padding">
				<ul class="tabconfig nav nav-pills nav-stacked" role="tablist">
					<?php foreach ($tabMenu as $row) : ?>
						<?php echo RedshopLayoutHelper::render('component.full.tab.link', $row); ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="col-sm-10">
		<div class="tab-content">
			<?php foreach ($tabMenu as $row) : ?>
				<?php echo RedshopLayoutHelper::render('component.full.tab.layout', array('row' => $row, 'view' => $view)); ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
