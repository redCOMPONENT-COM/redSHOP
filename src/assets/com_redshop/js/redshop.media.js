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
(function($, window, document, undefined) {
    var pluginName = "redshopMedia";

    var defaults = {
        /**
         * URL to upload file with Ajax
         *
         * @type  {String}
         */
        uploadUrl: "index.php?option=com_redshop&view=media&task=ajaxUpload",
        /**
         * URL to delete item in media gallery
         *
         * @type  {String}
         */
        deleteUrl: "index.php?option=com_redshop&view=media&task=ajaxDelete",
        allowedMime: "image/jpeg,image/jpg,image/png,image/gif",
        maxFileSize: 2048,
        initFile: null,
        showMediaFiles: false
    };

    // The actual plugin constructor
    function Plugin(element, options) {
        this._name     = pluginName;
        this._defaults = defaults;

        this.element = element;
        this.options = $.extend({}, defaults, options);
        this.token   = null;
        this.dropzoneInstance = null;

        this.$container       = $(element);
        this.$dropzonePreview = this.$container.find(".rs-media-dropzone-preview");
        this.$dropzoneForm    = this.$container.find(".rs-media-dropzone-form");

        this.$alertModal    = this.$container.find(".rs-media-alert-modal");
        this.$cropperModal  = this.$container.find(".rs-media-cropper-modal");
        this.$cropperButton = this.$container.find(".rs-media-cropper-btn");
        this.$removeButton  = this.$container.find(".rs-media-remove-btn");
        this.$target        = this.$container.find(".redshop-media-img-select");

        this.$mediaFileButton   = null;
        this.$mediaFileInsertButton = null;
        this.$mediaFileModal    = null;
        this.$mediaFilePreview  = null;
        this.$mediaFileDelModal = null;

        this.init();
    }

    Plugin.prototype = {
        /**
         * Initialise plugin
         *
         * @return {void}
         */
        init: function() {
            var self = this;

            this._initAttributes();

            // Disable DropzoneJS auto discover and apply default settings
            Dropzone.autoDiscover = false;

            if (self.$dropzoneForm.length) {
                $('body').append($(self.$dropzoneForm[0]).html());
            }

            if ($("#" + self.$container.data("id") + "-dropzone")) {
                // Initialize new Dropzone
                self.dropzoneInstance = new Dropzone("#" + self.$container.data("id") + "-dropzone", {
                    url: self.options.uploadUrl,
                    // acceptedFiles: ".png,.jpg,.jpeg,.bmp",
                    autoProcessQueue: true,
                    // maxFiles: 1,
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewTemplate: $(self.$dropzonePreview[0]).html()
                });
            }

            if (self.$alertModal.length) {
                self.$alertModal = $(self.$alertModal[0]);
            }

            if (self.$cropperButton.length) {
                self.$cropperButton = $(self.$cropperButton[0]);
            }

            if (self.$removeButton.length) {
                self.$removeButton = $(self.$removeButton[0]);
            }

            if (self.$target.length) {
                self.$target = $(self.$target[0]);
            }

            if (self.options.showMediaFiles == true) {
                if (self.$container.find('.rs-media-gallery-modal').length)
                    self.$mediaFileModal = $(self.$container.find('.rs-media-gallery-modal')[0]);

                if (self.$container.find('.rs-media-gallery-preview').length)
                    self.$mediaFilePreview = $(self.$container.find('.rs-media-gallery-preview')[0]);

                if (self.$container.find('.rs-media-gallery-delete-modal').length)
                    self.$mediaFileDelModal = $(self.$container.find('.rs-media-gallery-delete-modal')[0]);

                if (self.$container.find('.rs-media-gallery-btn').length)
                    self.$mediaFileButton = $(self.$container.find('.rs-media-gallery-btn')[0]);

                if (self.$container.find('.rs-media-gallery-insert-btn').length)
                    self.$mediaFileInsertButton = $(self.$container.find('.rs-media-gallery-insert-btn')[0]);

                this._initMediaFileEvents();
            }

            this._initEvents();

            if (self.options.initFile != null) {
                // Preload file from server
                var newFile  = self.dataURItoBlob(self.options.initFile.blob);
                newFile.name = self.options.initFile.name;

                // this.emit("addedfile", file);

                // And optionally show the thumbnail of the file:
                self.dropzoneInstance.emit("thumbnail", self.options.initFile, self.options.initFile.url);

                // this.files.push(file);
                self.dropzoneInstance.addFile(newFile);
            }
        },
        /**
         * Init attributes.
         *
         * @return  {void}
         */
        _initAttributes: function() {
            this.token = this.$container.attr('data-token');
        },
        /**
         * Init associated events
         *
         * @return {void}
         */
        _initEvents: function() {
            var self = this;

            /**
             * Event on add file via upload / drop.
             *
             * @return  void
             */
            self.dropzoneInstance.on('addedfile',  function(file) {
                if (!self.validateFile(file)) {
                    this.removeFile(file);

                    return;
                }

                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }

                self.$cropperButton.removeClass('disabled').prop('disabled', false);
                self.$removeButton.removeClass('disabled').prop('disabled', false);

                if (self.$container.find("#rs-media-img-delete").length) {
                    self.$container.find("#rs-media-img-delete").remove();
                }
            });

            /**
             * Event on success file via upload / drop.
             *
             * @return  void
             */
            self.dropzoneInstance.on('success', function(file, response){
                response = JSON.parse(response);
                if (response.success) {
                    self.$target.val(response.data.file.url);
                }
            });

            /**
             * Event on success file via upload / drop.
             *
             * @return  void
             */
            self.$removeButton.on("click", function(event){
                event.preventDefault();
                self.dropzoneInstance.removeAllFiles();
                self.$target.val("");
                self.$cropperButton.addClass('disabled').prop('disabled', true);
                self.$removeButton.addClass('disabled').prop('disabled', true);

                var $hidden = null;

                // Add input hidden for rs-media-img-delete.
                if (self.$container.find("#rs-media-img-delete").length <= 0) {
                    $hidden = $('<input/>');
                    $hidden.attr('id', 'rs-media-img-delete')
                        .attr('name', self.$container.data("id") + "_delete")
                        .attr('type', 'text');

                    $("#adminForm").append($hidden);
                }

                $hidden.val(true);
            });

            /**
             * Event on crop button
             *
             * @return  void
             */
            self.$cropperButton.on("click", function(event){
                event.preventDefault();

                // ignore files which were already cropped and re-rendered
                // to prevent infinite loop
                var file = self.dropzoneInstance.files[0];

                if (!file) {
                    showAlert('Please insert an image!!!');

                    return;
                }

                if (file.width < 100) {
                    // validate width to prevent too small files to be uploaded
                    // .. add some error message here
                    return;
                }

                self.$cropperModal.modal('show');
            });

            /**
             * Event on cropper modal load
             *
             * @return  void
             */
            self.$cropperModal.on("shown.bs.modal", function(e){
                var file = self.dropzoneInstance.files[0];

                // cache filename to re-assign it to cropped file
                var cachedFilename = file.name;

                // 'Crop and Upload' button in a modal
                var $uploadCrop = self.$cropperModal.find('.crop-upload');

                var $img = self.$cropperModal.find('.image-container img').first();

                // initialize FileReader which reads uploaded file
                var reader = new FileReader();

                reader.onloadend = function () {
                    // Add uploaded and read image to modal
                    $img.attr('src', reader.result);

                    // initialize cropper for uploaded image
                    $img.cropper("destroy")
                        .cropper({
                            dragMode: 'move',
                            autoCropArea: 0.5,
                            movable: false,
                            cropBoxResizable: true,
                            // minCropBoxWidth: 200,
                            //minContainerWidth: 320,
                            //minContainerHeight: 320,
                            viewMode: 3,
                            zoomable: true
                        });
                };

                // Read uploaded file (triggers code above)
                if (file.preload) {
                    reader.readAsDataURL(self.dataURItoBlob(file.blob));
                } else {
                    reader.readAsDataURL(file);
                }

                // unbind event click Crop button
                $uploadCrop.off('click');

                // listener for 'Crop and Upload' button in modal
                $uploadCrop.on('click', function() {
                    // Get cropped image data
                    var blob = $img.cropper('getCroppedCanvas').toDataURL();

                    // Transform it to Blob object
                    var newFile = self.dataURItoBlob(blob);

                    // Set 'cropped to true' (so that we don't get to that listener again)
                    newFile.cropped = true;

                    // Assign original filename
                    newFile.name = cachedFilename;

                    // Remove not cropped file from dropzone (we will replace it later)
                    self.dropzoneInstance.removeFile(file);

                    // Add cropped file to dropzone
                    self.dropzoneInstance.addFile(newFile);

                    // Upload cropped file with dropzone
                    // jDropzone.processQueue();
                    self.$cropperModal.modal('hide');
                });
            });
        },
        /**
         * Init associated events
         *
         * @return {void}
         */
        _initMediaFileEvents: function() {
            var self = this;

            /**
             * Event process for open Gallery button
             *
             * @return  void
             */
            self.$mediaFileButton.click(function(event){
                event.preventDefault();
                self.$mediaFileModal.modal("show");
            });

            // Click on image object.
            self.$mediaFileModal.find(".img-obj").click(function(event){
                event.preventDefault();

                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else{
                    self.$mediaFileModal.find(".img-obj").removeClass('selected');
                    $(this).addClass('selected');
                    self.mediaFileShowInfor(this);
                }
                self.mediaFileResetPreview();
                self.mediaFileToggleInsert();
            });

            // Click on Insert image button
            self.$mediaFileInsertButton.click(function(e) {
                e.preventDefault();

                var imgObj = self.$mediaFileModal.find(".img-obj.selected").find('img').first();
                var imgUrl = imgObj.attr('src');

                self.$target.val(imgUrl);

                var xhr = new XMLHttpRequest();
                xhr.open("GET", imgUrl);
                xhr.responseType = "blob";
                xhr.send();
                xhr.addEventListener("load", function() {
                    var reader = new FileReader();
                    reader.readAsDataURL(this.response);
                    reader.addEventListener("loadend", function() {
                        var newFile  = self.dataURItoBlob(reader.result);
                        newFile.name = imgObj.attr('alt');
                        self.dropzoneInstance.addFile(newFile);
                        self.$mediaFileModal.modal('hide');
                    });
                });
            });

            // Click on open modal delete.
            self.$mediaFileModal.find(".btn-del-g").on('click', function(e){
                e.preventDefault();

                self.$mediaFileDelModal.find(".btn-confirm-del-g").data('id', $(this).data('id'));
                self.$mediaFileDelModal.modal('show');
            });

            // Click confirm delete file.
            self.$mediaFileDelModal.find(".btn-confirm-del-g").on('click', function(e){
                e.preventDefault();

                var id = $(this).data('id');

                if (id) {
                    $.ajax({
                        url: self.options.deleteUrl,
                        method: 'post',
                        data: {id: id}
                    })
                        .done(function(response){
                            self.$mediaFileModal.find(".img-obj.selected").parent().remove();
                            self.$mediaFileModal.find(".pv-wrapper").addClass('hidden');
                        })
                        .always(function(e){
                            self.$mediaFileDelModal.modal('hide');
                        });
                }
            });
        },
        /**
         * Validate image
         *
         * @param  {object} file file object
         *
         * @return {boolean}
         */
        validateFile: function(file) {
            var self = this;

            var fileSize = file.size / 1024;

            if (fileSize > self.options.maxFileSize)
            {
                self.showAlert(Joomla.JText._("COM_REDSHOP_UPLOAD_FILE_TOO_BIG"));

                return false;
            }

            if (self.options.allowedMime.indexOf(file.type) == -1)
            {
                self.showAlert(Joomla.JText._("COM_REDSHOP_MEDIA_ERROR_FILE_UPLOAD_INVALID"));

                return false;
            }

            return true;
        },
        /**
         * Method for show alert using Bootstrap modal
         *
         * @param   {string}  text  Text of message
         *
         * @return  void
         */
        showAlert: function(text) {
            var self = this;

            self.$alertModal.find('.alert-text').text(text);
            self.$alertModal.modal('show');
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
            var ab         = new ArrayBuffer(byteString.length);
            var ia         = new Uint8Array(ab);

            for (var i = 0; i < byteString.length; i++)
            {
                ia[i] = byteString.charCodeAt(i);
            }

            return new Blob([ab], { type: 'image/jpg' });
        },
        /**
         * Show info of thumbnail when selecting
         *
         * @param   {DOM}  elem  Element item selected
         *
         * @return  {void}
         */
        mediaFileShowInfor: function(element)
        {
            var self = this;

            if (self.$mediaFileModal.find(".preview-pane").length <= 0) {
                return;
            }

            var $previewPanel = $(self.$mediaFileModal.find(".preview-pane")[0]);

            var $imgObj = $(element).find('.img-type');

            var info = {
                id: $imgObj.data('id'),
                url: $imgObj.attr('src'),
                name: $imgObj.attr('alt'),
                size: $imgObj.data('size'),
                dimension: $imgObj.data('dimension')
            };

            var $img = $imgObj.clone();

            $previewPanel.find('.pv-img .img-type').remove();
            $previewPanel.find('.pv-img').append($img);
            $previewPanel.find('.pv-zoom').attr('href', info.url);
            $previewPanel.find('.pv-zoom').attr('data-title', info.name);
            $previewPanel.find('.pv-link').attr('href', info.url);
            $previewPanel.find('.pv-name').text(info.name);
            $previewPanel.find('.pv-size').text(info.size);
            $previewPanel.find('.pv-dimension').text(info.dimension);
            $previewPanel.find('.pv-url').html('<input type="text" value="'+info.url+'" class="form-control" readonly="true">');
            $previewPanel.find('.pv-remove > a').data('id', info.id);

            $previewPanel.find('.pv-wrapper').removeClass('hidden');
        },
        /**
         * Clear attachment details when unselecting
         *
         * @return  {void}
         */
        mediaFileResetPreview: function()
        {
            var self = this;

            if (self.$mediaFileModal.find(".preview-pane").length <= 0) {
                return;
            }

            var $previewPanel = $(self.$mediaFileModal.find(".preview-pane")[0]);

            if (self.$mediaFileModal.find(".img-obj.selected").length <= 0) {
                $previewPanel.find('.pv-wrapper').addClass('hidden');
            }
        },
        /**
         * Toggle disabled or not of insert media button
         *
         * @return  {void}
         */
        mediaFileToggleInsert: function()
        {
            var self = this;

            if (self.$mediaFileModal == null) {
                return;
            }

            if (self.$mediaFileModal.find(".img-obj.selected").length > 0) {
                self.$mediaFileInsertButton.removeAttr('disabled');
            } else {
                self.$mediaFileInsertButton.attr('disabled', 'true');
            }
        }
    };

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        })
    }
})(jQuery, window, document);