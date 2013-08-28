function preloadSlimbox(isenable)
{
	jQuery(document).ready(function($){
		getImagename = function (link) {
	    	var re = new RegExp("filename=(.*?)&newxsize=(.*?)&newysize=(.*?)&swap=(.)");
			var m = link.match(re);
			return m;
	    }

	    redproductzoom = function () {
			var mainimg = $('div[id*=productImageWrapID_]').find('img');
			var m = getImagename(mainimg.attr('src'));
			var newxsize = m[2];
			var newysize = m[3];
			var swap = m[4];
			var urlfull = site_url + 'components/com_redshop/assets/images/' + m[1];

			mainimg.attr('data-zoom-image', urlfull);

			//more image
		   	$('span[id*=additional_images]').find('.additional_image').each(function(){
		   		$(this).attr('onmouseout', '');
				$(this).attr('onmouseover', '');

				gl = $(this).attr('id');

				var urlimg = $(this).find('img').attr('data-src');
				if (typeof urlimg === 'undefined' || urlimg === false) {
				   urlimg = $(this).find('img').attr('src');
				}

				var m = getImagename(urlimg);

				var urlthumb = site_url + 'components/com_redshop/helpers/thumb.php?filename=' + m[1] + '&newxsize=' + newxsize + '&newysize=' + newysize + '&swap=' + swap;
				var urlfull = site_url + 'components/com_redshop/assets/images/' + m[1];

				$(this).find('a').attr('data-image', urlthumb);
				$(this).find('a').attr('data-zoom-image', urlfull);
				$(this).find('a').attr('class', 'elevatezoom-gallery');
			});

		   	if(mainimg.data('elevateZoom'))
		   	{
				var ez = mainimg.data('elevateZoom');
				ez.currentImage = urlfull;
				ez.imageSrc = urlfull;
				ez.zoomImage = urlfull;
				ez.closeAll();
				ez.refresh();

				//Create the image swap from the gallery
				$('#'+ez.options.gallery + ' a').click( function(e) {

					//Set a class on the currently active gallery image
					if(ez.options.galleryActiveClass){
						$('#'+ez.options.gallery + ' a').removeClass(ez.options.galleryActiveClass);
						$(this).addClass(ez.options.galleryActiveClass);
					}
					//stop any link on the a tag from working
					e.preventDefault();

					//call the swap image function
					if($(this).data("zoom-image")){ez.zoomImagePre = $(this).data("zoom-image")}
					else{ez.zoomImagePre = $(this).data("image");}
					ez.swaptheimage($(this).data("image"), ez.zoomImagePre);
					return false;
				});

		   	}
		   	else
		   	{
		   		var gl = $('.redhoverImagebox').attr('id');
				mainimg.elevateZoom({
					cursor: "crosshair",
			   		gallery : gl
		   		});
		   	}
	    }

	    redproductzoom();

	});
}

