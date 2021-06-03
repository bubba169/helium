require('./tabs');
require('./toggle');
require('./repeater');
require('./tinymce');

window.dispatchEvent(new Event('helium-init-forms'));
