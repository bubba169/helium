import flatpickr from 'flatpickr';

const init = () => {
    const flatpickrs = document.querySelectorAll('.flatpickr');
    Array.prototype.forEach.call(flatpickrs, (element) => {
        const pickr = flatpickr('#' + element.id, JSON.parse(element.getAttribute('data-config')));
    });
}

init();
