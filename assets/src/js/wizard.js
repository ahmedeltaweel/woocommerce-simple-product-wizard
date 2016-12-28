/**
 * Created by teamyea on 11/08/16.
 */

(function (w, $, undefined)
{

	/**
	 * Show error border.
	 *
	 * @param selector
	 */
	function showErrorBorder(selector)
	{
		$(selector).css({
			"border-color": "#FF0000",
			"border-width": "1px",
			"border-style": "solid"
		});
	}

	/**
	 * Remove border from around selector.
	 *
	 * @param selector
	 */
	function removeErrorBorder(selector)
	{
		$(selector).css('border', '');
	}

	$(function ()
	{
		// submitting condition.
		var submit = true;

		// making wizard
		var form = $("#add-product-wizard-form");
		form.children("div").steps({
			headerTag: "h1",
			bodyTag: "section",
			transitionEffect: "slideLeft",
			onStepChanging: function (event, currentIndex, newIndex)
			{
				// allow return back.
				if (newIndex < currentIndex)
				{
					return true;
				}

				// first page.
				if (currentIndex === 0)
				{
					// validate name.
					if (!$('#product-name').val().length)
					{
						// show border around name field.
						showErrorBorder('#product-name');
						return false;
					}

					// remove error border
					removeErrorBorder('#product-name');

					// validate image.
					var gallery_image_main = $('#image-gallery-main');

					if (!gallery_image_main.length)
					{
						showErrorBorder('#product-gallery-open-main');
						return false;
					}

					if (!gallery_image_main.val().length)
					{
						showErrorBorder('#product-gallery-open-main');
						return false;
					}

					// remove error border.
					removeErrorBorder('#product-gallery-open-main');
				}

				// second page.
				if (currentIndex === 1)
				{

					// validate regular price.
					if (!$('#product-regular-price').val().length || $('#product-regular-price').val() < 0.01)
					{

						// show error order.
						showErrorBorder('#product-regular-price');
						return false;
					}

					// remove error order.
					removeErrorBorder('#product-regular-price');

				}

				// Third page.
				if (currentIndex === 2)
				{

					// validate regular price.
					if (!$('#product-sku').val().length)
					{

						// show error order.
						showErrorBorder('#product-sku');
						return false;
					}
					// remove error order.
					removeErrorBorder('#product-sku');
				}

				// fifth page.
				if (currentIndex === 4)
				{

					// validate categories for non empty.
					if (!$("#product-categories-select-2").val())
					{
						// show error order.
						showErrorBorder('.category-container');
						return false;
					}

					// remove error order.
					removeErrorBorder('.category-container');
				}
				return true
			},
			onFinished: function (event, currentIndex)
			{


				// remove error border.
				removeErrorBorder('input');
				removeErrorBorder('.category-container');
				removeErrorBorder('.tags-container');

				// submit if true.
				if (submit)
				{
					// submitting the form.
					form.submit();
					submit = false;

					// adding spinner
					$('.wizard>.actions>ul').append('<span class="spinner"></span>');

					// viewing the spinner.
					$('.spinner').css({'visibility': 'visible'});

				}

				return true;
			}
		});

		// submit event
		form.on('submit', function (e)
		{
			e.preventDefault(); // avoid to execute the actual submit of the form.

			// getting description.
			var description = get_editor_content();

			var url = wwapw_ajax_object.wwapw_ajax_url;

			var data = $(this).serializeArray(); // convert form to array
			// adding description to this form.
			data.push({name: "product_description", value: description});

			$.ajax({
				data: data,
				type: 'post',
				url: url,
				success: function (data)
				{

					// removing the spinner
					$('.spinner').remove();

					var container = $('.wizard-error-area');
					container.empty();

					if (data.success)
					{

						// enable submitting again.
						submit = true;

						alert('Successfully added.');

						// enable.
						$('a[href =#finish]').bind('click', true);

						// change url.
						window.location.href = data.data;
					} else
					{
						// enable submitting again.
						submit = true;

						console.log( data.data );

						//append errors message.
						container.append("" +
							"<div class='error notice'><p>" + data.data[0] + "</p></div>" +
							"");

						//animate to error message
						$('body').animate({
							scrollTop: $("div.error").offset().top - 75
						}, 500);

						// check for slides (input select).
						switch (data.data[2])
						{
							case 0:
								showErrorBorder("input[name=" + data.data[1] + "]");
								break;

							case 1:
								showErrorBorder("input[name=" + data.data[1] + "]");
								break;

							case 2:
								if (data.data[1] == 'product_stock_status' || data.data[1] == 'product_stock_back_orders')
								{
									// select fields.
									showErrorBorder("select[name=" + data.data[1] + "]");
									break;
								}

								// input field
								showErrorBorder("input[name=" + data.data[1] + "]");

								break;
							case 3:

								if (data.data[1] == 'product_shipping_class')
								{
									showErrorBorder("select[name=" + data.data[1] + "]");
									break;
								}

								showErrorBorder("input[name=" + data.data[1] + "]");
								break;
							case 4:
								if (data.data[1] == 'cat_input')
								{
									//categories.
									showErrorBorder('.category-container');
								} else if (data.data[1] == 'product_tag_selection')
								{
									//tags
									showErrorBorder('.tags-container');
								}
								break;
							case 5:

								if (data.data[1] == 'product_after_submit_condition')
								{
									// select field.
									showErrorBorder("select[name=" + data.data[1] + "]");
								}

								//input field.
								showErrorBorder("input[name=" + data.data[1] + "]");
								break;
						}

						//go to desired slide.
						$("#steps-uid-0-t-" + data.data[2]).click();

					}
				}
			});
		});

		// adding media query component.
		wwapw_upload_image_media_query('product-gallery-open-main', $);

		wwapw_upload_image_media_query_multiple('product-gallery-open', $);

		// viewing hidden elements pricing.
		var pricing_visible = false;
		$('#wwapw-view-hidden-link').click(function (e)
		{
			e.preventDefault();
			if (!pricing_visible)
			{
				$('.wwapw-hidden').css("display", 'block');
				pricing_visible = true;
			}
			else
			{
				$('.wwapw-hidden').css("display", 'none');
				pricing_visible = false;
			}

		});

		// viewing hidden elements.
		$('#product-stock-management').on('change', function (e)
		{
			e.preventDefault();
			if (this.checked)
			{
				$('.wwapw-hidden-inventory').css("display", 'block');
			}
			else
			{
				$('.wwapw-hidden-inventory').css("display", 'none');
			}

		});

		// adding date pickers
		$('#product-sale-price-sdate').datepicker({
			inline: true,
			dateFormat: 'yy-mm-dd',
			minDate: new Date()
		});
		$('#product-sale-price-edate').datepicker({
			inline: true,
			dateFormat: 'yy-mm-dd',
			minDate: new Date()
		});

		$('#product-categories-select-2').select2({
			placeholder: 'Select categories',
			dropdownCssClass: 'product-categories-select-2',
			width: '100%',
			dropDownAutoWidth: true
		});

		// // adding select 2 to tags.
		$('#product-tags-select-2').select2({
			placeholder: 'Select tags',
			dropdownCssClass: 'product-tags-select',
			width: '100%',
			dropDownAutoWidth: true,
			tags: true
		});

	});

	/**
	 * getting content of wp-editor
	 *
	 * @returns string
	 */
	function get_editor_content()
	{
		// text area selector
		var selector = $('#product_description');

		// check for using tinymce or textarea.
		if ($(selector).css('display') == 'none')
		{
			return tinymce.editors['product_description'].getContent();
		} else
		{
			return $(selector).val();
		}
	}
})(window, jQuery);