/**
 * @license
 * Media.js - redSHOP Javascript component
 *
 * Copyright (c) 2016. GNU General Public License version 2 or later, see LICENSE.
 *
 * This library provide a new class to handle media upload
 * with dropzonejs, cropperjs.
 * It also manage the script of new media gallery for redSHOP.
 * It support to search item with js by fusejs.
 * Media.js's dependencies use Bower to manage.
 *
 * Component: com_redshop
 * Develop: redWeb.vn Team
 * Author: thuy@redweb.dk
 * Version: 1.0 Devel
 *
 * References:
 * - http://www.dropzonejs.com
 * - https://fengyuanchen.github.io/cropperjs/
 * - http://fusejs.io
 * - https://bower.io
 *
 * To run:
 * - bower install
 * - gulp copy
 */

/**
 * Strict mode is declared
 */
// "use strict";

/**
 * Media library
 * Uses: rsMedia.init();
 *
 * @type  {Object}
 */
var rsMedia = {

    /**
     * URL to upload file with Ajax
     *
     * @todo Need more secure
     *
     * @type  {String}
     */
    url: 'index.php?option=com_redshop&view=media&task=ajaxUpload',

    /**
     * URL to delete item in media gallery
     *
     * @type  {String}
     */
    delUrl: 'index.php?option=com_redshop&view=media&task=ajaxDelete',

    /**
     * Get html of dropzone DOM
     *
     * @type  {jQueryObject}
     */
    dropzoneFromHtml: $("#j-dropzone-form").html(),

    /**
     * Pre-defined Dropzone object
     *
     * @type  {Object}
     */
    dropzoneInstance: {},

    /**
     * An array to store gallery items
     *
     * @type  {Array}
     */
    galleryItems: [],

    /**
     * [cropper description]
     *
     * @return  {[type]}  [description]
     */
    cropping: function(jDropzone) {

        $(document).on('click', 'button.rs-media-cropping',function(e) {
            e.preventDefault();
            // ignore files which were already cropped and re-rendered
            // to prevent infinite loop
            var file = jDropzone.files[0];

            if (!file) {
                $('#alertModal').find('.alert-text').text('Please insert an image!!!');
                $('#alertModal').modal('show');
                return;
            }

            if (file.width < 100) {
                // validate width to prevent too small files to be uploaded
                // .. add some error message here
                return;
            }
            // cache filename to re-assign it to cropped file
            var cachedFilename = file.name;

            // dynamically create modals to allow multiple files processing
            // var $cropperModal = $($.parseHTML(modalTemplate));
            var $cropperModal = $("#cropModal");
            // 'Crop and Upload' button in a modal
            var $uploadCrop = $cropperModal.find('.crop-upload');

            var $img = $('<img />');
            // initialize FileReader which reads uploaded file
            var reader = new FileReader();
            reader.onloadend = function () {
                // add uploaded and read image to modal
                $img.attr('src', reader.result);
                $cropperModal.find('.image-container').html($img);

                // initialize cropper for uploaded image
                $img.cropper({
                    // aspectRatio: 16 / 9,
                    dragMode: 'move',
                    autoCropArea: .5,
                    movable: false,
                    cropBoxResizable: true,
                    // minCropBoxWidth: 200,
                    minContainerWidth: 320,
                    minContainerHeight: 320,
                    viewMode: 3,
                    zoomable: false
                });
            };
            // read uploaded file (triggers code above)
            if (file.preload) {
                reader.readAsDataURL(rsMedia.dataURItoBlob(file.blob));
            } else {
                reader.readAsDataURL(file);
            }

            // unbind event click Crop button
            $uploadCrop.off('click');

            $cropperModal.modal('show');

            $cropperModal.trigger('resize');
            console.log('abc');

            // listener for 'Crop and Upload' button in modal
            $uploadCrop.on('click', function() {
                // get cropped image data
                var blob = $img.cropper('getCroppedCanvas').toDataURL();
                // transform it to Blob object
                var newFile = rsMedia.dataURItoBlob(blob);
                // set 'cropped to true' (so that we don't get to that listener again)
                newFile.cropped = true;
                // assign original filename
                newFile.name = cachedFilename;
                console.log(cachedFilename);
                console.log(newFile);
                // remove not cropped file from dropzone (we will replace it later)
                jDropzone.removeFile(file);
                // add cropped file to dropzone
                jDropzone.addFile(newFile);
                // upload cropped file with dropzone
                /*jDropzone.processQueue();*/
                $cropperModal.modal('hide');
            });
        });
    },

    /**
     * Handle dropzone
     *
     * @return  {void}
     */
    dropzone: function() {
        // Disable DropzoneJS auto discover and apply default settings
        Dropzone.autoDiscover = false;

        $('body').append(this.dropzoneFromHtml);

        // check if Dropzone HTML was included
        if ($('#j-dropzone').length) {
            // Initialize new Dropzone
            this.dropzoneInstance = new Dropzone(
                "#j-dropzone",
                {
                    url: rsMedia.url,
                    // acceptedFiles: ".png,.jpg,.jpeg,.bmp",
                    autoProcessQueue: true,
                    // maxFiles: 1,
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewTemplate: $("#j-dropzone-tpl").html()
                }
            );

            this.dropzoneEvents(this.dropzoneInstance);
            this.cropping(this.dropzoneInstance);
        }
    },

    /**
     * Handle events of dropzone instance
     *
     * @param   {Dropzone}  jDropzone  Dropzone instance
     *
     * @return  {void}
     */
    dropzoneEvents: function(jDropzone) {
        // jDropzone.on("maxfilesreached", function(file) {
        // 	console.log("trigger reach max: %s", this.files.length);
        // 	if (this.files.length > 1) {
        // 		this.removeAllFiles();
        // 		this.addFile(file);
        // 	}
        // });

        jDropzone.on('addedfile',  function(file) {
            if (file.type.indexOf("image/") < 0) {
                this.removeFile(file);
                $('#alertModal').find('.alert-text').text('You can not upload this type of file!');
                $('#alertModal').modal('show');
                return;
            }

            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }

            $('#j-dropzone').parent().find('.btn.rs-media-removing').removeClass('disabled').prop('disabled', false);
            $('#j-dropzone').parent().find('.btn.rs-media-cropping').removeClass('disabled').prop('disabled', false);
        });

        jDropzone.on('success', function(file, response){
            response = JSON.parse(response);
            if (response.success) {
                $(".img-select").val(response.data.file.url);
            }
        });

        // jDropzone.on('error', function(file, err){
        // 	console.log("trigger error: %o", file);
        // 	if (!file.accepted) {
        // 		jDropzone.removeFile(file);
        // 	}
        // 	$('#alertModal').find('.alert-text').text(err);
        // 	$('#alertModal').modal('show');
        // });

        $(document).on('click', 'button.rs-media-removing',function(e) {
            jDropzone.removeAllFiles();
            $(".img-select").val('');
            $('#j-dropzone').parent().find('.btn.rs-media-removing').addClass('disabled').prop('disabled', true);
            $('#j-dropzone').parent().find('.btn.rs-media-cropping').addClass('disabled').prop('disabled', true);

            if ($('#image_delete').length <= 0) {
                var hidden = $('<input/>');
                hidden.attr('id', 'image_delete').attr('name', 'image_delete').attr('type', 'hidden').val(true);
                $("#adminForm").append(hidden[0]);
            }
        });
    },

    /**
     * Preload a file into Dropzone thumbnail
     *
     * @param   {Dropzone}  jDropzone  Dropzone to load
     * @param   {Object}    file       File to Preload
     *
     * @return  {void}
     */
    dropzonePreload: function(jDropzone, file) {
        if (file) {
            // Preload file from server
            newfile = rsMedia.dataURItoBlob(file.blob);
            newfile.name = file.name;
            // this.emit("addedfile", file);
            // And optionally show the thumbnail of the file:
            jDropzone.emit("thumbnail", file, file.url);
            // this.files.push(file);
            jDropzone.addFile(newfile);
        }
    },

    /**
     * Transform cropper dataURI output to a Blob which Dropzone accepts
     *
     * @param   string  dataURI  base64encode data of files
     *
     * @return  Blob
     */
    dataURItoBlob: function(dataURI) {
        var byteString = atob(dataURI.split(',')[1]);
        var ab = new ArrayBuffer(byteString.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: 'image/jpg' });
    },

    /**
     * Initialize new Media instance
     *
     * @return  {void}
     */
    init: function() {
        // set limit for backdrop
        $.fn.modalmanager.defaults.backdropLimit = 1;

        // load dropzone
        this.dropzone();

        this.galleryEvents();
    },

    /**
     * Create dropzone for gallery media
     *
     * @return  {void}
     */
    galleryDropzone: function() {
        // check if Dropzone HTML was included
        if ($('#g-dropzone').length) {
            // Initialize new Dropzone
            var gDropzone = new Dropzone(
                "#g-dropzone",
                {
                    url: rsMedia.url,
                    // acceptedFiles: ".png,.jpg,.jpeg,.bmp",
                    // autoProcessQueue: false,
                    maxFiles: 1,
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewTemplate: $("#g-dropzone-tpl").html()
                }
            );

            this.galleryDropzoneEvents(gDropzone);
        }

    },

    /**
     * Events relative to gallery
     *
     * @return  {void}
     */
    galleryEvents: function(galleryId) {
        if (typeof galleryId == "undefined" || galleryId == "")
            galleryId = "galleryModal";

        // open gallery modal
        $(".choosing").on('click', function(e){
            e.preventDefault();

            $("#" + galleryId).modal('show');
        });

        // Click on gallery items
        $(document).on('click', "#" + galleryId + " .img-obj", function(e){
            e.preventDefault();
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else{
                $("#" + galleryId + " .img-obj").removeClass('selected');
                $(this).addClass('selected');
                rsMedia.showInfoThumbnail(this);
            }
            rsMedia.resetInfoThumbnail();
            rsMedia.toggleInsert();
        });

        // change filters
        $("#" + galleryId + " #type-filter").on("change", function(e){
            var value = $(this).val();
            if (value == 'all') {
                $("#" + galleryId + " .img-obj").parent().removeClass('hidden');
                return;
            }

            if (value == 'attached') {
                $("#" + galleryId + " .img-obj > img:not([data-attached=true])").parent().parent().addClass('hidden');
                return;
            }

            // filter by media type
            $("#" + galleryId + " .img-obj > img:not([data-media="+value+"])").parent().parent().addClass('hidden');
            $("#" + galleryId + " .img-obj > img[data-media="+value+"]").parent().parent().removeClass('hidden');
        });

        // insert and image
        $("#" + galleryId + " .btn-insert").on('click', function(e) {
            e.preventDefault();

            var imgObj = $("#" + galleryId + " .img-obj.selected").find('img').first();
            var imgUrl = imgObj.attr('src');

            $("#" + galleryId + " .img-select").val(imgUrl);

            var xhr = new XMLHttpRequest()
            xhr.open("GET", imgUrl);
            xhr.responseType = "blob";
            xhr.send();
            xhr.addEventListener("load", function() {
                var reader = new FileReader();
                reader.readAsDataURL(xhr.response);
                reader.addEventListener("loadend", function() {
                    var newFile = rsMedia.dataURItoBlob(reader.result);
                    newFile.name = imgObj.attr('alt');
                    rsMedia.dropzoneInstance.addFile(newFile);
                    $("#" + galleryId).modal('hide');
                });
            });
        });

        // open delete gallery modal
        $("#" + galleryId + " .btn-del-g").on('click', function(e){
            e.preventDefault();

            $("#" + galleryId + "Delete .btn-confirm-del-g").data('id', $(this).data('id'));
            $("#" + galleryId + "Delete").modal('show');

            // bootbox.confirm("This is the default confirm!", function(result){
            // 	console.log('This was logged in the callback: ' + result);
            // });
        });

        // confirm delete item
        $("#" + galleryId + "Delete .btn-confirm-del-g").on('click', function(e){
            e.preventDefault();

            console.log('abc');

            var id = $(this).data('id');
            if (id) {
                $.ajax({
                    url: rsMedia.delUrl,
                    method: 'post',
                    data: {id: id}
                })
                .done(function(response){
                    $("#" + galleryId).find(".img-obj.selected").parent().remove();
                    $("#" + galleryId + " .pv-wrapper").addClass('hidden');
                })
                .always(function(e){
                    $("#" + galleryId + "Delete").modal('hide');
                });
            }
        });
    },

    galleryDropzoneEvents: function(gDropzone) {
        gDropzone.on("sending", function(file, xhr, data) {
            data.append("new", true);
        });

        gDropzone.on('success', function(file, res) {
            res = JSON.parse(res);
            var file = res.data.file;
            var item = $('#g-item-tpl').html();
            var $item = $(item);
            var $itemObj = {};
            if (file.mime == 'image') {
                $item.find('span.img-type').remove();
                $itemObj = $item.find('img.img-type');
            } else {
                $item.find('img.img-type').remove();
                $itemObj = $item.find('span.img-type');
            }
            $itemObj.attr('src', '/' + file.url);
            $itemObj.attr('alt', file.name);
            $itemObj.data('id', file.id);
            $itemObj.data('size', file.size);
            $itemObj.data('dimension', file.dimension);
            $itemObj.data('media', file.media);
            $item.find('.img-mime').data('mime', file.mime);
            if (file.mime != '') {
                $itemObj.find('i.fa')
                .removeClass('fa-file-o')
                .addClass('fa-file-' + file.mime + '-o');
                $item.find('.img-mime i.fa')
                .removeClass('fa-file-o')
                .addClass('fa-file-' + file.mime + '-o');
            }
            $item.find('.img-name').text(file.name);
            $("#upload-lib .list-pane").append($item[0]);
            $('#g-tab a[href="#upload-lib"]').tab("show");

            gDropzone.removeAllFiles();
        });
    },

    /**
     * Show info of thumbnail when selecting
     *
     * @param   {DOM}  elem  Element item selected
     *
     * @return  {void}
     */
    showInfoThumbnail: function(elem)
    {
        var $imgObj = $(elem).find('.img-type');

        var info = {
            id: $imgObj.data('id'),
            url: $imgObj.attr('src'),
            name: $imgObj.attr('alt'),
            size: $imgObj.data('size'),
            dimension: $imgObj.data('dimension')
        };

        $img = $imgObj.clone();

        var $pane = $(".preview-pane");
        $pane.find('.pv-img .img-type').remove();
        $pane.find('.pv-img').append($img);
        $pane.find('.pv-zoom').attr('href', info.url);
        $pane.find('.pv-zoom').attr('data-title', info.name);
        $pane.find('.pv-link').attr('href', info.url);
        $pane.find('.pv-name').text(info.name);
        $pane.find('.pv-size').text(info.size);
        $pane.find('.pv-dimension').text(info.dimension);
        $pane.find('.pv-url').html('<input type="text" value="'+info.url+'" class="form-control" readonly="true">');
        $pane.find('.pv-remove > a').data('id', info.id);

        $pane.find('.pv-wrapper').removeClass('hidden');
    },

    /**
     * Clear attachment details when unselecting
     *
     * @return  {void}
     */
    resetInfoThumbnail: function()
    {
        var $pane = $(".preview-pane");
        if ($(".img-obj.selected").length <= 0) {
            $pane.find('.pv-wrapper').addClass('hidden');
        }
    },

    /**
     * Toggle disabled or not of insert media button
     *
     * @return  {void}
     */
    toggleInsert: function()
    {
        if ($(".img-obj.selected").length > 0) {
            $(".btn-insert").removeAttr('disabled');
        } else {
            $(".btn-insert").attr('disabled', 'true');
        }

    }
}