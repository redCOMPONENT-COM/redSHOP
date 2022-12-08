<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHtml::_('redshopjquery.framework');
/** @scrutinizer ignore-deprecated */
JHtml::script('com_redshop/ajaxupload.min.js', false, true);

/**
 * $displayData extract
 *
 * @var   array $displayData Layout data.
 * @var   object $rowData    Extra field data
 * @var   string $required   Extra field required
 * @var   string $uniqueId   Extra field unique Id
 * @var   array $fieldCheck  Extra field check
 * @var   int $isAtt
 * @var   int $productId
 */
extract($displayData);
$http_referer = JFactory::getApplication()->input->server->getString('HTTP_REFERER', '');
$ajax   = '';

$unique = $rowData->name . '_' . $productId;

if ($isAtt > 0) {
    $ajax   = 'ajax';
    $unique = $rowData->name;
}

// Tweak by Ronni START - Change button ?>
<div class="userfield_input rsFileUpload hasPopover" title="<?php echo \JText::_('COM_REDSHOP_UPLOAD_TIP1') ?>" 
        data-content="<?php echo \JText::_('COM_REDSHOP_UPLOAD_TIP2') ?>">
    <button
            type="button"
            class="<?php echo $rowData->class; ?>"
            id="file<?php echo $ajax . $unique ?>"
            name="file<?php echo $ajax . $unique ?>"
            value="<?php echo JText::_('COM_REDSHOP_UPLOAD'); ?>"
            size="<?php echo $rowData->size; ?>"
            userfieldlbl="<?php echo $rowData->title; ?>"
            <?php echo $required; ?> />
        <i class='fas fa-upload'></i> <?php echo JText::_('COM_REDSHOP_UPLOAD'); ?>
    </button>
</div>
<?php /* ?>
<div class="userfield_input">
    <input
            type="button"
            class="<?php echo $rowData->class; ?>"
            id="file<?php echo $ajax . $unique ?>"
            name="file<?php echo $ajax . $unique ?>"
            value="<?php echo JText::_('COM_REDSHOP_UPLOAD'); ?>"
            size="<?php echo $rowData->size; ?>"
            userfieldlbl="<?php echo $rowData->title; ?>"
        <?php echo $required; ?>
    />
</div>
<?php */ 
// Tweak by Ronni END - Change button ?>
<?php if (strpos($http_referer, 'administrator') !== false
    && (strpos($http_referer, 'view=order_detail') !== false
        || strpos($http_referer, 'view=addorder_detail') !== false
        || strpos($http_referer, 'view=quotation') !== false
        || strpos($http_referer, 'view=quotation_detail') !== false
        || strpos($http_referer, 'view=addquotation_detail') !== false)
): ?>
    <script type="text/javascript" id="inner-ajax-script_<?php echo $uniqueId ?>">
        (function ($) {
            new AjaxUpload(
                "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
                {
                    action: "<?php echo JUri::root(
                    ) ?>index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
                    data: {
                        mname: "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
                        fieldName: "<?php echo $rowData->name ?>",
                        uniqueOl: "<?php echo $rowData->name . '_' . $uniqueId; ?>"
                    },
                    name: "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
                    onSubmit: function (file, ext) {
                        jQuery('#<?php echo $rowData->name ?>').text("<?php echo JText::_(
                                'COM_REDSHOP_UPLOADING'
                            ) . 'file'; ?>");
                    },
                    onComplete: function (file, response) {
                        jQuery("#ol_<?php echo $rowData->name; ?> li.error").remove();
                        jQuery('#ol_<?php echo $rowData->name; ?>').append(response);
                        var uploadfiles = jQuery('#ol_<?php echo $rowData->name; ?> li').map(function () {
                            return jQuery(this).find('span').text();
                        }).get().join(',');
                        jQuery('#<?php echo $rowData->name . '_' . $uniqueId; ?>').val(uploadfiles);
                        jQuery('#<?php echo $rowData->name; ?>').val(uploadfiles);
                        this.enable();
                    }
                }
            );
        })(jQuery);
    </script>
<?php else: ?>
    <script>
	    // jQuery.noConflict();
	    new AjaxUpload(
		    "file<?php echo $ajax . $unique ?>",
		    {
			    action:"<?php echo JUri::root() ?>index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
			    data :{
				    mname:"file<?php echo $ajax . $rowData->name ?>",
				    product_id:"<?php echo $productId ?>",
				    uniqueOl:"<?php echo $unique ?>",
				    fieldName: "<?php echo $rowData->name ?>",
				    ajaxFlag: "<?php echo $ajax ?>"
			    },
			    name:"file<?php echo $ajax . $unique ?>",
			    onSubmit : function(file , ext){
				    jQuery("file<?php echo $ajax . $unique ?>").text("<?php echo \JText::_('COM_REDSHOP_UPLOADING') ?>" + file);
				    // Tweak by Ronni - Loader image
                    jQuery("#loaderimg").attr("style","display:block;");
                    this.disable();
			    },
			    onComplete :function(file,response){
				    jQuery("#ol_<?php echo $unique ?> li.error").remove();
				    jQuery("#ol_<?php echo $unique ?>").append(response);
				    var uploadfiles = jQuery("#ol_<?php echo $unique ?> li").map(function() {
					    return jQuery(this).find("span").text();
				    }).get().join(",");
				    this.enable();
				    jQuery("#<?php echo $ajax . $unique ?>").val(uploadfiles);
				    jQuery("#<?php echo $rowData->name ?>").val(uploadfiles);
                    // Tweak by Ronni - Loader image + succes functions
                    jQuery("#loaderimg").attr("style","display:none;");
                    if (uploadfiles !="") {
						orgText = jQuery("#ol_<?php echo $unique ?>").html();
						newText = orgText.replace("<?php echo \JText::_('COM_REDSHOP_FILE_EXTENSION_NOT_ALLOWED') ?>","");
						jQuery("#ol_<?php echo $unique ?>").html(newText);
						jQuery(".file_ext_error").css("display","none");										
						jQuery("#rsFileUpload,.rsFileUpload,.drpFileUpload").css("opacity","0.5");
						jQuery("#rsFileUpload,.rsFileUpload,.drpFileUpload").css("pointer-events","none");
						jQuery(".file_uploaded_correct").css("display","block");
					}
			    }
		    }
	    );
    </script>
<?php endif; ?>

<?php
// Tweak by Ronni START - Dropbox upload button
$dropboxRMLink = "<br><a class='rmUploadedFile' onclick='javascript:removeDropboxURL(" . $unique . ");'>" . \JText::_('COM_REDSHOP_DELETE') . "</a>"; ?>
<div class="rsFileUpload hasPopover" title="<?php echo \JText::_('COM_REDSHOP_UPLOAD_DROPBOX_TIP1') ?>" 
        data-content="<?php echo \JText::_('COM_REDSHOP_UPLOAD_DROPBOX_TIP2') ?>">
	<a class="btn btn-dropbox" value="<?php echo \JText::_('COM_REDSHOP_UPLOAD_DROPBOX') ?>" id="chooser-image">
		<span class="fab fa-dropbox"></span> <?php echo \JText::_('COM_REDSHOP_UPLOAD_DROPBOX') ?>
	</a>
</div>
<a id="link"></a>
<script>
	document.getElementById("chooser-image").onclick = function () {
		Dropbox.choose({
			linkType: "preview",
			extensions: [".pdf", ".jpg", ".jpeg", ".zip"],
			success: function(files) {
		    	document.getElementById("' . $unique . '").value=files[0].link;
				document.getElementById("' . $data->name . '").value=files[0].link;
				orgText = jQuery("#ol_' . $unique . '").html();
				newText = orgText.replace("' . \JText::_('COM_REDSHOP_FILE_EXTENSION_NOT_ALLOWED') . '","");
			    jQuery("#ol_' . $unique . '").html(newText);
				jQuery("#ol_' . $unique . '").append(jQuery("<li>").html(files[0].link+"'.$dropboxRMLink.'"));
				jQuery("#ol_' . $unique . '").addClass("dropAddClass");
				jQuery(".file_ext_error").css("display","none");
				jQuery(".rsFileUpload").css("opacity","0.5");
				jQuery(".rsFileUpload").css("pointer-events","none");
				jQuery(".filext_jpg_info").css("display","none");
				jQuery(".file_uploaded_correct").css("display","block");
				jQuery("#attPropId3491738").css("opacity","1");
			}
		});
	};
</script>
<script src="templates/tx_optimus/js/dropins.js" id="dropboxjs" data-app-key="k9oyd9ixkqi9kuc"></script> <?php
// Tweak by Ronni END - Dropbox upload
// Tweak by Ronni START - Onedrive upload
$filepickerRMLink = "<br><a class='rmUploadedFile' onclick='javascript:removeOnedriveURL(" . $unique . ");'>" . \JText::_('COM_REDSHOP_DELETE') . "</a>"; ?>

<div class="rsFileUpload hasPopover" title="<?php echo \JText::_('COM_REDSHOP_UPLOAD_ONEDRIVE') ?>" 
        data-content="<?php echo \JText::_('COM_REDSHOP_UPLOAD_ONEDRIVE_TIP') ?>">						
	<a class="btn btn-onedrive" id="filepicker-image">
		<span class="fas fa-cloud-upload-alt"></span> <?php echo \JText::_('COM_REDSHOP_UPLOAD_ONEDRIVE') ?>
	</a>
</div>
<a id="webUrl"></a>

<script type="text/javascript">
document.getElementById("filepicker-image").onclick = function () {
	var odOptions = {
		clientId: "987ea353-5be6-425d-8fd4-194322e3d771",
		action: "download",
		multiSelect: false,
		advanced: {queryParameters: "select=@content.downloadUrl,webUrl",filter: ".jpeg,.jpg,.pdf,.zip"},
		success: function(files) {
			var upldFile = files.value[0]["@microsoft.graph.downloadUrl"];
			document.getElementById("' . $unique . '").value=upldFile;
			document.getElementById("' . $data->name . '").value=upldFile;
			orgText = jQuery("#ol_' . $unique . '").html();
			newText = orgText.replace("' . \JText::_('COM_REDSHOP_FILE_EXTENSION_NOT_ALLOWED') . '","");
			jQuery("#ol_' . $unique . '").html(newText);
			jQuery("#ol_' . $unique . '").append(jQuery("<li>").html(upldFile +"'.$filepickerRMLink.'"));
			jQuery("#ol_' . $unique . '").addClass("dropAddClass");
			jQuery(".file_ext_error").css("display","none");									
			jQuery(".rsFileUpload").css("opacity","0.5");
			jQuery(".rsFileUpload").css("pointer-events","none");
			jQuery(".filext_jpg_info").css("display","none");
			jQuery(".file_uploaded_correct").css("display","block");
			jQuery("#attPropId3491738").css("opacity","1");
			},
			cancel: function() { /* cancel handler */ },
			error: function(error) { /* error handler */ }
		};
	OneDrive.open(odOptions);
}
</script>
<script type="text/javascript" src="https://js.live.net/v7.2/OneDrive.js"></script> <?php
// Tweak by Ronni END - Onedrive upload

// Tweak by Ronni - Loader image ?>
<div style="display:none;" id="loaderimg">
    <img width="200" height="16" src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH ?>uploading1.gif" alt="Uploading image"/>
</div>