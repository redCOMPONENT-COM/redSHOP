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
			<div class="col-sm-5">
		        <div class="box box-primary">
		            <div class="box-header with-border">
		                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_MEDIA_NAME'); ?></h3>
		            </div>
		            <div class="box-body">
		                <div class="form-group" id="divShowBox">
							<?php echo RedshopHelperMediaImage::render(
								'name',
								$this->item->section,
								$this->item->id,
								$this->item->section,
								$this->item->name,
								false
							) ?>
							<?php echo $this->form->renderField('name') ?>
		                </div>
		                <div class="form-group" id="divYouTube">
		                	<?php echo $this->form->renderField('youtube_id') ?>
		                	<div id="divYouTubeContent">
		                	<?php echo $this->form->getField('youtube_content')->input ?>
		                	</div>
		                	<div id="divVideoContent">
		                	<?php echo $this->form->getField('video_content')->input ?>
		                	</div>
		                </div>
		            </div>
		        </div>
		    </div>
			<div class="col-sm-7">
				<div class="box box-primary">
					<div class="box-header with-border">
							<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_MEDIA_SECTION') ?></h3>
					</div>
					<div class="box-body">
						<?php echo $this->form->renderField('type') ?>
						<?php echo $this->form->renderField('section') ?>
						<div id="divSectionId">
							<?php echo $this->form->renderField('section_id') ?>
						</div>
					</div>
				</div>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAILS') ?></h3>
					</div>
					<div class="box-body">
						<?php echo $this->form->renderField('alternate_text') ?>
						
						<?php echo $this->form->renderField('published') ?>
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
		jQuery(document).on('keyup', '#jform_youtube_id', function(e){
			youtubeId = jQuery(this).val();
			jQuery.ajax({
                url: 'index.php?option=com_redshop&task=media.ajaxUpdateYoutubeVideo&youtube_id=' + youtubeId,
                type: 'GET'
            })
            .done(function (response) {
            	jQuery('#divYouTubeContent').html(response);
            })
		});
	});

	function loadYoutubeFields()
	{
		jQuery('#divShowBox').hide();
	}

	function loadSectionId(e)
	{
		mediaSeciton = jQuery(e).val();
		jQuery.ajax({
            url: 'index.php?option=com_redshop&task=media.ajaxUpdateSectionId&media_section=' + mediaSeciton,
            type: 'GET'
        })
        .done(function (response) {
        	jQuery('#divSectionId').html(response);
        	jQuery("#jform_section_id").select2();
        })
	}
</script>

