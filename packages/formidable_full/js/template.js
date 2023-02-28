(function () {
    CKEDITOR.plugins.add('formidable', {
        init: function (editor) {
            editor.addCommand( 'formidableData', {
                exec: function( editor ) {  
                    editor.insertHtml('{%formidable_mailing%}');
                }
            });
            editor.ui.addButton( 'Formidable Data', {
                label: 'Insert Formidable Mailing Tag',
                command: 'formidableData',
                toolbar: 'about'
            });               
        }
    });
})();
