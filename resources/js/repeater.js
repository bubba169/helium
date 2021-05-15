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

            // Hook up the move up buttons
            repeater.querySelectorAll('.helium-repeater-move-up:not([data-helium-init])')
                .forEach(upButton => {
                    upButton.addEventListener('click', function () {
                        const field = repeater;
                        const form = upButton.closest('.helium-repeater-form');
                        const previous = previousFormSibling(form);
                        if (previous) {
                            previous.before(form);
                        }
                        reindex(field);
                    });

                    upButton.setAttribute('data-helium-init', true);
                });

            // Hook up the move down buttons
            repeater.querySelectorAll('.helium-repeater-move-down:not([data-helium-init])')
                .forEach(downButton => {
                    downButton.addEventListener('click', function () {
                        const field = repeater;
                        const form = downButton.closest('.helium-repeater-form');
                        const next = nextFormSibling(form);
                        if (next) {
                            next.after(form);
                        }
                        reindex(field)
                    });

                    downButton.setAttribute('data-helium-init', true);
                });

            reindex(repeater);
        });
    }
}

function onRemoveClicked(event)
{
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
        // Create a temp div to contain the new html
        const div = document.createElement('div');
        div.insertAdjacentHTML('beforeend', data);

        // Extract the form
        const form = div.querySelector('.helium-repeater-form');

        repeater.querySelector('.helium-repeater-forms-container')
            .insertAdjacentElement('beforeend', form);

        reindex(repeater);

        window.dispatchEvent(new Event('helium-init-forms'));
    })
    .catch(error => {
        console.log(error)
    });
}

function reindex(repeater)
{
    const orderable = repeater.classList.contains('orderable');
    repeater.querySelectorAll('.helium-repeater-form').forEach((form, index) => {
        if (orderable) {
            form.querySelector('.helium-sequence-field').value = index++;
        }
        form.querySelector('.helium-repeater-move-down').classList.toggle('hidden', !orderable || !nextFormSibling(form));
        form.querySelector('.helium-repeater-move-up').classList.toggle('hidden', !orderable || !previousFormSibling(form));
    });
}

function nextFormSibling(form)
{
    let next = form.nextElementSibling;
    while(next && !next.matches('.helium-repeater-form')) {
        next = next.nextElementSibling;
    }
    return next;
}

function previousFormSibling(form)
{
    let previous = form.previousElementSibling;
    while(previous && !previous.matches('.helium-repeater-form')) {
        previous = previous.previousElementSibling;
    }
    return previous;
}

window.addEventListener('helium-init-forms', init);
