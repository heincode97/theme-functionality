jQuery(document).ready(function ($) {
        	
    $('div.section_body').hide();
             
    $('h3').click(function () {
        $(this).toggleClass("open");
        $(this).next("div").slideToggle('1000');
        return false;
    });
        		
    //set the textbox value from dropdown list
    $('select').change(function(){
        $(this).closest('.options_input').find('input[type=text]').val($(this).val());
    });		
    //when the page load, if textbox is not empty, set the selected value
    $('select').each(function(){
        $(this).val( $(this).closest('.options_input').find('input[type=text]').val() );
    });
        		
        		
    $('#option_page_media select, #option_page_image_library select').change(function(){
        var v = $(this).find('option:selected').val();
        if( v < 0 ) return false;
        $('input', $(this).closest('.options_input')).val(v);
    });
    $('#option_mini_sidebar_pageid select').change(function(){
        var v = $(this).find('option:selected').val();
        if( v < 0 ) return false;
        var input = $('input', $(this).closest('.options_input'));
        if( input.val() != '' ){
            input.val(input.val() + "," + v);
        }
        else{
            input.val(v);
        }
        				
        			
    });
    var formfield;
    $('.upload_image_button').click(function () {
        $('html').addClass('FrameworkUploadImage');
        formfield = $('.upload_image', $(this).parent()).attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=1');
        window.original_send_to_editor = window.send_to_editor;
        window.send_to_editor = function (html) {
            if (formfield) {
        					
                fileurl = $('img', html).attr('src');
                var activeImageField = $('.upload_image[name="' + formfield + '"]');
                $(activeImageField).val(fileurl);
                $('.image_holder img', $(activeImageField).parent()).remove();
                $('.image_holder', $(activeImageField).parent()).append('<img src="' + fileurl + '" style="max-width: 300px;" />');
                if( $(".delete_image_button", $(activeImageField).parent('.options_input_image')) === 'undefined' ){
                    $(activeImageField).parent('.options_input_image').append('<input class="button-secondary delete_image_button" type="button" value="Delete" />');;
                }
                tb_remove();
                $('html').removeClass('FrameworkUploadImage');
            //console.log($("#roulette_options[page_blog_flog]").closest('.options_input_image'));
            } else {
                window.original_send_to_editor(html);
            }
        };
        return false;
    });
    // user inserts file into post. only run custom if user started process using the above process
    // window.send_to_editor(html) is how wp would normally handle the received data
        		
        		
    // get the channel text color 
    /*	$("#option_col_travel, #option_col_auto, #option_col_entertainment, #option_col_lifestyle, #option_col_sport, #option_col_technology").children("input").change(function() {
                var p = $(this).closest(".options_input");
                $("#" + p.attr('id') + "_text_color").children("input").val(colorToHex($(this).css("color")));
        			
            })*/
    //onload
                
                
    $("#option_col_travel, #option_col_auto, #option_col_entertainment, #option_col_lifestyle, #option_col_sport, #option_col_technology").each(function() {
        var id = $(this).attr('id');
        var text_color = $("#" + id + '_text_color input').val();      
        $(this).children('input').css("color", text_color + " !important");
    });
    $("#option_col_travel_text_color, #option_col_auto_text_color, #option_col_entertainment_text_color, #option_col_lifestyle_text_color, #option_col_sport_text_color, #option_col_technology_text_color").children("input").keyup(function() {   
                    
        var channel_bg_id = $(this).closest(".options_input").attr('id').replace('_text_color', '');
        $("#" + channel_bg_id).children("input").css("color", $(this).val()+ " !important");
                        
    });
        	
    $(".delete_image_button").live('click', function () {
        $('.upload_image', $(this).parent()).val('');
        $('.image_holder img', $(this).parent()).remove();
        $(this).remove();
        return false;
    });
        	
    function set_image() {
        $(".options_input_image").each(function (i, v) {
            if ($(this).find('.upload_image').val() != '') {
                $(this).find('.image_holder').append('<img src="' + $(this).find('.upload_image').val() + '" style="max-width: 300px;" />');
                $(this).append('<input class="button-secondary delete_image_button" type="button" value="Delete" />');
            }
        });
    }
    set_image();
});