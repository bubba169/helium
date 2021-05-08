const init = () => {
    const repeaters = document.querySelectorAll('.helium-repeater-field');
    if (repeaters.length) {
        repeaters.forEach((repeater) => {
            repeater.querySelector('.helium-repeater-buttons [data-action=add]')
                .addEventListener('click', (event) => {
                    addNewForm(repeater)
                });
        });
    }
}

function addNewForm(repeater)
{
    
}
