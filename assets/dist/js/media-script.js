/**
 * Created by teamyea on 04/08/16.
 */
//upload image media query
function wwapw_upload_image_media_query(a,b){b("#"+a).click(function(a){a.preventDefault();var c=wp.media({title:"Select Image",multiple:!1}).open().on("select",function(a){
// This will return the selected image from the Media Uploader, the result is an object
var d=c.state().get("selection").first(),e=d.toJSON(),f=b(".image-gallery-main");
// empty the div
f.empty(),
// append image
f.append("<img class='wwapw-main-image' src="+e.url+">"),
// append hidden input
f.append("<input id='image-gallery-main' class='wwapw-main-image-field wwapw-required' type='number' name='image_gallery_main' value="+e.id+" />")})})}
// selecting multiple images.
function wwapw_upload_image_media_query_multiple(a,b){b("#"+a).click(function(a){a.preventDefault();var c=wp.media({title:"Select Image",
// mutiple: true if you want to upload multiple files at once
multiple:!0}).open().on("select",function(a){
// This will return the selected image from the Media Uploader, the result is an object
var d=c.state().get("selection"),e=d.toJSON(),f=b(".image-gallery");f.empty();for(var g=0;g<e.length;g++)f.append("<img src="+e[g].url+">"),f.append("<input type='hidden' name='image_gallery["+g+"]' value="+e[g].id+" />")})})}