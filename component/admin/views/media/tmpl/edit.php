<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidator');
$uri = JURI::getInstance();
$url = $uri->root();

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "media.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
		{
			Joomla.submitform(task);
		}
	};
');
?>

<form action="index.php?option=com_redshop&task=media.edit&id=<?php echo $this->item->id ?>"
	method="post"
	id="adminForm"
	name="adminForm"
	class="form-validate form-horizontal"
	enctype="multipart/form-data">
	<fieldset class="adminform">
		<div class="row">
			<div class="col-sm-4">
		        <div class="box box-primary">
		            <div class="box-header with-border">
		                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_MEDIA_NAME'); ?></h3>
		            </div>
		            <div class="box-body">
		                <div class="form-group">
							<?php echo RedshopHelperMediaImage::render(
								'media_name',
								$this->item->media_section,
								$this->item->id,
								$this->item->media_section,
								$this->item->media_name,
								false
							) ?>
							<?php echo $this->form->renderField('media_name') ?>
		                </div>
		            </div>
		        </div>
		    </div>
			<div class="col-sm-8">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAILS') ?></h3>
					</div>
					<div class="box-body">
						<?php echo $this->form->renderField('media_alternate_text') ?>
						<?php echo $this->form->renderField('media_section') ?>
						<?php echo $this->form->renderField('published') ?>
					</div>
					
				</div>
				<div class="box box-primary">
					<div class="box-header with-border">
							<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_MEDIA_SECTION') ?></h3>
					</div>
					<div class="box-body" id="divSectionId">
						<?php echo $this->form->renderField('section_id') ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo $this->form->getInput('id') ?>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</form>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(document).on('change', 'input[name="jform[media_section]"]', function(){
			mediaSeciton = jQuery(this).val();
			jQuery.ajax({
                url: 'index.php?option=com_redshop&task=media.ajaxUpdateSectionId&media_section=' + mediaSeciton,
                type: 'GET'
            })
            .done(function (response) {
            	jQuery('#divSectionId').html(response);
            	jQuery("#jform_section_id").select2();
            })

		});
	});
</script>

