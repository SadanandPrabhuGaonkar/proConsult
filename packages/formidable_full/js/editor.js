(function () {
    CKEDITOR.plugins.add('formidable', {
        init: function (editor) {
            editor.addCommand( 'formidableData', {
                exec: function( editor ) {  
                    jQuery.fn.dialog.open({ 
                        width: 750,
                        height: 600,
                        modal: true,
                        href: element_dialog_url+'?formID='+$('#formID').val()+'&ccm_token='+formidable_security_token_element,
                        title: choose_element, 
                        open: function() {
                            $('.element_options .btn').off('click').on('click', function() {
                                var code = $(this).data('insert');
                                jQuery.fn.dialog.closeTop();
                                editor.insertHtml(code);
                            });
                        }     
                    });
                    
                }
            });
            editor.ui.addButton( 'Formidable Data', {
                label: 'Insert Formidable Data',
                command: 'formidableData',
                toolbar: 'about'
            });               
        }
    });
})();
