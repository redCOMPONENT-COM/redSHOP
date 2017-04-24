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
$display = 'style="display:none"';
$app = JFactory::getApplication();
$tmpl = $app->input->get('tmpl', '');
?>

<form action="index.php?option=com_redshop&task=medium.edit&id=<?php echo $this->item->id ?>"
	method="post"
	id="adminForm"
	name="adminForm"
	class="form-validate form-horizontal"
	enctype="multipart/form-data">
	<fieldset class="adminform">

        <!-- Show toolbar in case template is component -->
        <?php if ($tmpl == 'component'): ?>
        <div class="row">
            <div class="btn-toolbar" id="toolbar">
                <div class="btn-wrapper" id="toolbar-apply">
                    <button class="btn btn-small btn-success" type="submit" onclick="Joomla.submitbutton('medium.apply');">
                        <span class="icon-apply"></span>
                        <?php echo JText::_('JAPPLY'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php endif ?>
        <!-- End toolbar -->

		<div class="row">
			<div class="col-sm-5">
		        <div class="box box-primary">
		            <div class="box-header with-border">
		                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_MEDIA_NAME'); ?></h3>
		            </div>
		            <div class="box-body">
		                <div class="form-group" id="divShowBox" <?php echo ($this->item->type == 'youtube')? $display: '' ?>>
							<?php echo RedshopHelperMediaImage::render(
								'name',
								$this->item->section,
								$this->item->id,
								$this->item->section,
								$this->item->name,
								false
							) ?>
							<?php echo $this->form->renderField('name') ?>
                            <div id="divVideoContent" style="display:none;">
                                <?php echo $this->form->getField('video_content')->input ?>
                            </div>
		                </div>
		                <div class="form-group" id="divYouTube" <?php echo (!isset($this->item->type) || $this->item->type != 'youtube')? $display: '' ?>>
		                	<?php echo $this->form->renderField('youtube_id') ?>
		                	<div id="divYouTubeContent">
		                	<?php echo $this->form->getField('youtube_content')->input ?>
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
                        <?php echo $this->form->renderField('title') ?>
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
                url: 'index.php?option=com_redshop&task=medium.ajaxUpdateYoutubeVideo&youtube_id=' + youtubeId,
                type: 'GET'
            })
            .done(function (response) {
            	jQuery('#divYouTubeContent').html(response);
            })
		});
	});

	function loadYoutubeFields()
	{
		jQuery('#divShowBox').toggle();
        jQuery('#divYouTube').toggle();
	}

	function loadSectionId(e)
	{
		mediaSeciton = jQuery(e).val();
		jQuery.ajax({
            url: 'index.php?option=com_redshop&task=medium.ajaxUpdateSectionId&media_section=' + mediaSeciton,
            type: 'GET'
        })
        .done(function (response) {
        	jQuery('#divSectionId').html(response);
        	jQuery("#jform_section_id").select2();
        })
	}
</script>

