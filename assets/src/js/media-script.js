/**
 * Created by teamyea on 04/08/16.
 */

//upload image media query
function wwapw_upload_image_media_query(button_id, $) {
	$('#' + button_id).click(function (e) {
		e.preventDefault();
		var image = wp.media({
			title: 'Select Image',
			multiple: false
		}).open()
			.on('select', function (e) {
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();

				// We convert uploaded_image to a JSON object to make accessing it easier
				var selected_image = uploaded_image.toJSON();

				// assign the id value to the hidden input field
				var gallery = $('.image-gallery-main');

				// empty the div
				gallery.empty();

				// append image
				gallery.append("<img class='wwapw-main-image' src=" + selected_image.url + ">");

				// append hidden input
				gallery.append("<input id='image-gallery-main' class='wwapw-main-image-field wwapw-required' type=\'number\' name=\'image_gallery_main\' value=" + selected_image.id + " />");


			});
	});
}

// selecting multiple images.
function wwapw_upload_image_media_query_multiple(button_id, $) {
	$('#' + button_id).click(function (e) {
		e.preventDefault();
		var image = wp.media({
			title: 'Select Image',
			// mutiple: true if you want to upload multiple files at once
			multiple: true
		}).open()
			.on('select', function (e) {

				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection');

				// We convert uploaded_image to a JSON object to make accessing it easier
				var images = uploaded_image.toJSON();

				var gallery = $('.image-gallery');
				gallery.empty();

				for (var i = 0; i < images.length; i++) {
					gallery.append("<img src=" + images[i].url + ">");
					gallery.append("<input type=\'hidden\' name=\'image_gallery[" + i + "]\' value=" + images[i].id + " />");
				}

			});
	});
}
