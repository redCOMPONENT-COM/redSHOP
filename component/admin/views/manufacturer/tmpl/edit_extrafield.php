<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$extraFields = RedshopHelperManufacturer::getExtraFields($this->item->manufacturer_id);
?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_FIELDS'); ?></h3>
			</div>
			<div class="box-body">
				<?php
					if ($extraFields != "")
					{
						echo $this->extra_field;
					}
					else
					{
						echo '<input type="hidden" name="noextra_field" value="1">';
					}
				?>
			</div>
		</div>
	</div>
</div>

