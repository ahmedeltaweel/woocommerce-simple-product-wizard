/**
 * Created by teamyea on 11/08/16.
 */
!function(a,b,c){/**
	 * Show error border.
	 *
	 * @param selector
	 */
function d(a){b(a).css({"border-color":"#FF0000","border-width":"1px","border-style":"solid"})}/**
	 * Remove border from around selector.
	 *
	 * @param selector
	 */
function e(a){b(a).css("border","")}/**
	 * getting content of wp-editor
	 *
	 * @returns string
	 */
function f(){
// text area selector
var a=b("#product_description");
// check for using tinymce or textarea.
// check for using tinymce or textarea.
return"none"==b(a).css("display")?tinymce.editors.product_description.getContent():b(a).val()}b(function(){
// submitting condition.
var a=!0,c=b("#add-product-wizard-form");c.children("div").steps({headerTag:"h1",bodyTag:"section",transitionEffect:"slideLeft",onStepChanging:function(a,c,f){
// allow return back.
if(f<c)return!0;
// first page.
if(0===c){
// validate name.
if(!b("#product-name").val().length)
// show border around name field.
return d("#product-name"),!1;
// remove error border
e("#product-name");
// validate image.
var g=b("#image-gallery-main");if(!g.length)return d("#product-gallery-open-main"),!1;if(!g.val().length)return d("#product-gallery-open-main"),!1;
// remove error border.
e("#product-gallery-open-main")}
// second page.
if(1===c){
// validate regular price.
if(!b("#product-regular-price").val().length||b("#product-regular-price").val()<.01)
// show error order.
return d("#product-regular-price"),!1;
// remove error order.
e("#product-regular-price")}
// Third page.
if(2===c){
// validate regular price.
if(!b("#product-sku").val().length)
// show error order.
return d("#product-sku"),!1;
// remove error order.
e("#product-sku")}
// fifth page.
if(4===c){
// validate categories for non empty.
if(!b("#product-categories-select-2").val())
// show error order.
return d(".category-container"),!1;
// remove error order.
e(".category-container")}return!0},onFinished:function(d,f){
// remove error border.
// submit if true.
// submitting the form.
// adding spinner
// viewing the spinner.
return e("input"),e(".category-container"),e(".tags-container"),a&&(c.submit(),a=!1,b(".wizard>.actions>ul").append('<span class="spinner"></span>'),b(".spinner").css({visibility:"visible"})),!0}}),
// submit event
c.on("submit",function(c){c.preventDefault();// avoid to execute the actual submit of the form.
// getting description.
var e=f(),g=wwapw_ajax_object.wwapw_ajax_url,h=b(this).serializeArray();// convert form to array
// adding description to this form.
h.push({name:"product_description",value:e}),b.ajax({data:h,type:"post",url:g,success:function(c){
// removing the spinner
b(".spinner").remove();var e=b(".wizard-error-area");if(e.empty(),c.success)
// enable submitting again.
a=!0,alert("Successfully added."),
// enable.
b("a[href =#finish]").bind("click",!0),
// change url.
window.location.href=c.data;else{
// check for slides (input select).
switch(
// enable submitting again.
a=!0,console.log(c.data),
//append errors message.
e.append("<div class='error notice'><p>"+c.data[0]+"</p></div>"),
//animate to error message
b("body").animate({scrollTop:b("div.error").offset().top-75},500),c.data[2]){case 0:d("input[name="+c.data[1]+"]");break;case 1:d("input[name="+c.data[1]+"]");break;case 2:if("product_stock_status"==c.data[1]||"product_stock_back_orders"==c.data[1]){
// select fields.
d("select[name="+c.data[1]+"]");break}
// input field
d("input[name="+c.data[1]+"]");break;case 3:if("product_shipping_class"==c.data[1]){d("select[name="+c.data[1]+"]");break}d("input[name="+c.data[1]+"]");break;case 4:"cat_input"==c.data[1]?
//categories.
d(".category-container"):"product_tag_selection"==c.data[1]&&
//tags
d(".tags-container");break;case 5:"product_after_submit_condition"==c.data[1]&&
// select field.
d("select[name="+c.data[1]+"]"),
//input field.
d("input[name="+c.data[1]+"]")}
//go to desired slide.
b("#steps-uid-0-t-"+c.data[2]).click()}}})}),
// adding media query component.
wwapw_upload_image_media_query("product-gallery-open-main",b),wwapw_upload_image_media_query_multiple("product-gallery-open",b);
// viewing hidden elements pricing.
var g=!1;b("#wwapw-view-hidden-link").click(function(a){a.preventDefault(),g?(b(".wwapw-hidden").css("display","none"),g=!1):(b(".wwapw-hidden").css("display","block"),g=!0)}),
// viewing hidden elements.
b("#product-stock-management").on("change",function(a){a.preventDefault(),this.checked?b(".wwapw-hidden-inventory").css("display","block"):b(".wwapw-hidden-inventory").css("display","none")}),
// adding date pickers
b("#product-sale-price-sdate").datepicker({inline:!0,dateFormat:"yy-mm-dd",minDate:new Date}),b("#product-sale-price-edate").datepicker({inline:!0,dateFormat:"yy-mm-dd",minDate:new Date}),b("#product-categories-select-2").select2({placeholder:"Select categories",dropdownCssClass:"product-categories-select-2",width:"100%",dropDownAutoWidth:!0}),
// // adding select 2 to tags.
b("#product-tags-select-2").select2({placeholder:"Select tags",dropdownCssClass:"product-tags-select",width:"100%",dropDownAutoWidth:!0,tags:!0})})}(window,jQuery);