const init = () => {
    const repeaters = document.querySelectorAll('.helium-repeater-field');
    if (repeaters.length) {
        repeaters.forEach((repeater) => {
            // Hook up the add buttons
            repeater.querySelectorAll('.helium-repeater-actions [data-action=add]:not([data-helium-init])')
                .forEach(addButton => {
                    addButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        addNewForm(addButton.closest('.helium-repeater-field'));
                    });
                    addButton.setAttribute('data-helium-init', true);
                });

            // Hook up the remove buttons
            repeater.querySelectorAll('.helium-repeater-remove:not([data-helium-init])')
                .forEach(removeButton => {
                    removeButton.addEventListener('click', onRemoveClicked);
                    removeButton.setAttribute('data-helium-init', true);
                });
        });
    }
}

function onRemoveClicked(event) {
    event.preventDefault();
    event.target.closest('.helium-repeater-form').remove();
}

function addNewForm(repeater)
{
    fetch('/admin/entities/form-section', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json'
        },
        body: repeater.getAttribute('data-add-request')
    })
    .then(response => response.text())
    .then(data => {
        repeater.querySelector('.helium-repeater-forms-container')
            .insertAdjacentHTML('beforeend', data);

        window.dispatchEvent(new Event('helium-init-forms'));
    })
    .catch(error => {
        console.log(error)
    });
}

window.addEventListener('helium-init-forms', init);
