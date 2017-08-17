<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * =======================
 * @var  array   $displayData   List of data.
 * @var  string  $id            ID
 * @var  string  $type          Type of section (Ex: product)
 * @var  string  $sectionId     Section ID (Ex: Product ID if $type is product)
 * @var  string  $mediaSection  Section media (Ex: product)
 * @var  array   $file          File data as array
 * @var  bool    $showMedia     Show pop-up for select image from media or not
 * @var  string  $maxFileSize   Max file size to upload
 */
extract($displayData);

$maxFileSize = !empty($maxFileSize) ? $maxFileSize : Redshop::getConfig()->get('MAX_FILE_SIZE_UPLOAD', 2048);
JText::script('COM_REDSHOP_UPLOAD_FILE_TOO_BIG');
JText::script('COM_REDSHOP_MEDIA_ERROR_FILE_UPLOAD_INVALID');
?>

<div id="<?php echo $id ?>-wrapper" data-id="<?php echo $id ?>">
    <!-- Dropzone Container -->
    <div action="/" id="<?php echo $id ?>-dropzone" class="rs-media-dropzone dropzone" enctype="multipart/form-data">
        <div class="fallback">
            <input name="file" type="file"/>
        </div>
    </div>
    <hr />
    <div class="btn-group btn-group-justified" role="group">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary rs-media-cropper-btn disabled" disabled="disabled" data-toggle="tooltip"
                    title="<?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_CROP'); ?>">
                <i class="fa fa-crop"></i> <?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_CROP') ?>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-danger rs-media-remove-btn disabled" disabled="true" data-toggle="tooltip"
                    title="<?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_REMOVE'); ?>">
                <i class="fa fa-trash"></i> <?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_REMOVE') ?>
            </button>
        </div>
    </div>
    <input type="hidden" name="<?php echo $id ?>" class="redshop-media-img-select" />
    <!-- End Dropzone Container -->

    <!-- Dropzone Template -->
    <div class="rs-media-dropzone-preview" style="display: none">
        <div class="dz-preview dz-file-preview">
            <div class="dz-details">
                <!-- <div class="dz-filename"><span data-dz-name></span></div> -->
                <!-- <div class="dz-size" data-dz-size></div> -->
                <img data-dz-thumbnail />
            </div>
            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
            <!-- <div class="dz-success-mark"><span>✔</span></div> -->
            <!-- <div class="dz-error-mark"><span>✘</span></div> -->
            <!-- <div class="dz-error-message"><span data-dz-errormessage></span></div> -->
        </div>
    </div>
    <!-- End Dropzone Template -->
</div>

<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            var option = {maxFileSize: '<?php echo $maxFileSize; ?>'};
			<?php if (!empty($file)): ?>
            option.initFile = {
                name: "<?php echo $file['name'] ?>",
                size: <?php echo $file['size'] ?>,
                // status: Dropzone.QUEUED,
                accepted: true,
                url: "<?php echo $file['path'] ?>",
                blob: "<?php echo $file['blob'] ?>",
                preload: true
            };
			<?php endif; ?>

            $("#<?php echo $id ?>-wrapper").redshopMedia(option);
        });
    })(jQuery);
</script>
