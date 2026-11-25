jQuery(document).ready(function ($) {
    $('input.datepicker').click(function () {
        $(this).datepicker({showOn: 'focus',
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy'
        }).focus();
    });

    $('.meta_upload_file_button').click(function () {
        $('html').addClass('MetaUploadFile');
        formfield = $('.meta_upload_file', $(this).parent()).attr('name');
        tb_show('', 'media-upload.php?TB_iframe=true');
        // user inserts file into post. only run custom if user started process using the above process
        // window.send_to_editor(html) is how wp would normally handle the received data
        window.original_send_to_editor = window.send_to_editor;
        window.send_to_editor = function (html) {
            if (formfield) {
                url = $(html).attr('href');
                var activeField = $("#" + formfield);
                $(activeField).val(url);
                tb_remove();
                $('html').removeClass('MetaUploadFile');
                //console.log($("#roulette_options[page_blog_flog]").closest('.options_input_image'));
            } else {
                window.original_send_to_editor(html);
            }
        };
        return false;
    });
    //search media while editing is not working. it is conflicted with Types plugin
    $("#media-upload form#filter p#media-search input[type='submit']").click(function () {
        $("#media-upload form#filter input[name='type']").val('');
    });

    $("#roulette_d_price_information, #roulette_d_additional_information").addClass("mceEditor");
    if (typeof (tinyMCE) == "object" &&
            typeof (tinyMCE.execCommand) == "function") {
        tinyMCE.settings = {
            theme: "advanced",
            mode: "none",
            language: "en",
            height: "200",
            width: "100%",
            theme_advanced_layout_manager: "SimpleLayout",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_font_sizes: "10px,12px,14px,16px,24px",
            theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,bullist,numlist,link,unlink,justifyleft,justifycenter,justifyright,justifyfull,code",
            theme_advanced_buttons2: "",
            theme_advanced_buttons3: ""
        };
        tinyMCE.execCommand("mceAddControl", false, "roulette_d_price_information");
        tinyMCE.execCommand("mceAddControl", false, "roulette_d_additional_information");

    }

});	