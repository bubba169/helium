import SlimSelect from 'slim-select';

const init = () => {
    const selects = document.querySelectorAll('.slimselect');

    console.log(selects);

    Array.prototype.forEach.call(selects, (element) => {
        new SlimSelect({
            select: element
        });
    });
}

init();
