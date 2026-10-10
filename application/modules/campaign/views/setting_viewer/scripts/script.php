<script>


    $('.campaign-block .form-group .select2').select2();


    $('.campaign-block .form-group .colorpicker1').colorpicker();

    $(".content .btnSaveCampaignConfig").on('click', function () {

        var selector = $(this);

        let dataSet = {};

        $( ".campaign-block .form-control" ).each(function( index ) {

            let id = $(this).attr('id');
            dataSet[id] = $(this).val();

        }).promise().done( function(){
            console.log(dataSet);
            saveConfigData(dataSet,selector);
        } );

        return false;
    });


    $('.campaign-block #PUSH_CAMPAIGNS_WITH_CRON').on('change',function (){
        let val = $(this).val();
        if(val==="true"){
            $('.campaign-block .campaign_with_cronjob').removeClass("hidden");
        }else{
            $('.campaign-block .campaign_with_cronjob').addClass("hidden");
        }
    });

</script>


<script src="<?= adminAssets("plugins/uploader/js/jquery.iframe-transport.js") ?>"></script>
<script src="<?= adminAssets("plugins/uploader/js/jquery.ui.widget.js") ?>"></script>
<script src="<?= adminAssets("plugins/uploader/js/jquery.fileupload.js") ?>"></script>
<script src="<?= adminAssets("plugins/jQueryUI/jquery-ui.js") ?>"></script>
<script>

    //Set up uploader fields
    setup_files_uploader01();


    function checkURL(url) {
        return(url.match(/\.(json)$/) != null);
    }

    function setup_files_uploader01(){

        $('.xc1302c .form-group').css("position","relative");

        //setup fields
        $( ".xc1302c input[data-file=json]").each(function( index ) {
            let $value = $(this).val();
            let $attr = $(this).attr("name");

            $(this).css('padding-right','49px');
            $(this).after('<button data-name="'+$attr+'" ' +
                'class="upload-file-btn" style="position:absolute;right:4px;top:29px"><i class="mdi mdi-upload"></i>' +
                '</button>').after('<input style="display:none" class="file_upload file_'+$attr+'" type="file"/>');

        }).promise().done( function(){

            $('[data-uploader] .upload-file-btn').on('click',function () {
                let $selector = $(this);
                let $attr = $(this).attr("data-name");
                let $file_input = $('.file_'+$attr);
                $file_input.trigger('click');
                $file_input.fileupload({
                    url: "<?=("cms/ajax/file_uploader")?>",
                    sequentialUploads: true,
                    limitMultiFileUploads: 1,
                    loadImageFileTypes: /^application\/(json)$/,
                    singleFileUploads: true,
                    formData: {
                        'token': "1zAqwOjs",
                        'attr': $attr,
                    },
                    dataType: 'json',
                    done: function (e, data) {

                        NSTemplateUIAnimation.buttonWithIcon.default = $selector;

                        var results = data._response.result;
                        let attr = results.attr;
                        if(results.success === 1){
                            let url = results.result;
                            $('input[name='+attr+']').val(url);
                        }else if(results.success === 0){
                            console.log(results.errors);
                        }

                    },
                    fail: function (e, data) {
                        console.log(data.jqXHR);
                    },
                    progressall: function (e, data) {

                    },
                    progress: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        console.log(progress);
                    },
                    start: function (e) {
                        NSTemplateUIAnimation.buttonWithIcon.loading = $selector;
                    }
                });



                return false;
            });


        } );


    }




</script>

