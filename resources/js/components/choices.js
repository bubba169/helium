import Choices from 'choices.js';

const init = () => {
    const multiselects = document.querySelectorAll('.choices-input');
    Array.prototype.forEach.call(multiselects, (element) => {
        const select = new Choices(element, {
            removeItemButton: true,
        });

        element.addEventListener('invalid', function() {
            select.containerOuter.element.classList.add('invalid');
        });
        element.addEventListener('change', function() {
            select.containerOuter.element.classList.remove('invalid');
        });
    });
}

init();
