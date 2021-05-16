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
                    removeButton.addEventListener('click', event => {
                        event.preventDefault();
                        const field = repeater;
                        const form = removeButton.closest('.helium-repeater-form');
                        animateDestroy(form);
                        reindex(field);
                    });
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

                        let top = form.getBoundingClientRect().height;
                        animateFrom(form, top);

                        top = -previous.getBoundingClientRect().height;
                        animateFrom(previous, top);
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

                        let top = next.getBoundingClientRect().height;
                        animateFrom(next, top);

                        top = -form.getBoundingClientRect().height;
                        animateFrom(form, top);
                    });

                    downButton.setAttribute('data-helium-init', true);
                });

            reindex(repeater);
        });
    }
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

        animateAppear(form);
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
    while(next && !next.matches('.helium-repeater-form:not(.removed)')) {
        next = next.nextElementSibling;
    }
    return next;
}

function previousFormSibling(form)
{
    let previous = form.previousElementSibling;
    while(previous && !previous.matches('.helium-repeater-form:not(.removed)')) {
        previous = previous.previousElementSibling;
    }
    return previous;
}

function animateFrom(item, top)
{
    item.style.setProperty('--animStart', top + 'px');
    item.style.animation = 'move-to 0.3s';
    item.addEventListener(
        'animationend',
        () => {
            item.style.animation = null;
        },
        {
            once: true
        }
    );
}

function animateDestroy(item)
{
    item.classList.add('removed');
    item.style.setProperty('--animStart', item.getBoundingClientRect().height + 'px');
    item.style.animation = 'shrink-out 0.3s';
    item.addEventListener(
        'animationend',
        () => {
            item.remove();
        },
        {
            once: true
        }
    );
}

function animateAppear(item)
{
    item.style.setProperty('--animEnd', item.getBoundingClientRect().height + 'px');
    item.style.animation = 'grow-in 0.3s';
    item.addEventListener(
        'animationend',
        () => {
            item.style.animation = null;
        },
        {
            once: true
        }
    );
}

window.addEventListener('helium-init-forms', init);
