import tinymce from 'tinymce';

// Default icons are required for TinyMCE 5.3 or above
import 'tinymce/icons/default';

// A theme is also required
import 'tinymce/themes/silver';

import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/code';

const init = () => {
    const editors = document.querySelectorAll('textarea.wysiwyg');
    Array.prototype.forEach.call(editors, editor => {
        let config = JSON.parse(editor.getAttribute('data-config'));
        config.selector = '#' + editor.id;
        config.setup = function (editor) {
            editor.on('change', function() {
                console.log('changed');
                editor.save();
            })
        }

        tinymce.init(config);


    })

}

init();
