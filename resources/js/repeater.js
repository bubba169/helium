// Store the currently dragged form element
var draggedForm = null;

const init = () => {
    const repeaters = document.querySelectorAll('.helium-repeater-field');
    // A simple event listener setup on drag start that prevents the ghost
    // image snapping back to the original position
    const bodyDrag = e => e.preventDefault();

    if (repeaters.length) {
        repeaters.forEach((repeater) => {
            // Hook up the add button
            const addButton = repeater.querySelector(':scope > .helium-repeater-actions [data-action=add]');
            if (!addButton.hasAttribute('data-helium-init')) {
                addButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    addNewForm(addButton.closest('.helium-repeater-field'));
                });
                addButton.setAttribute('data-helium-init', true);
            }

            // Hook up the drag and drop functionality
            repeater.querySelectorAll(':scope > div > .helium-repeater-form:not([data-helium-init])')
                .forEach(form => {
                    //const field = repeater;
                    const draggable = form.firstElementChild;
                    const handle = form.querySelector(':scope > div > .helium-repeater-drag');

                    handle.addEventListener('mousedown', event => {
                        draggable.setAttribute('draggable', true);
                    });

                    draggable.addEventListener('dragstart', event => {
                        draggedForm = form;
                        form.classList.add('helium-form-dragging');
                        repeater.classList.add('helium-repeater-dragging');
                        // Add a listener on the body to prevent the ghost snapping back
                        document.body.addEventListener('dragover', bodyDrag);

                        draggable.addEventListener('dragend', event => {
                            document.body.removeEventListener('dragover', bodyDrag);
                            form.classList.remove('helium-form-dragging');
                            repeater.classList.remove('helium-repeater-dragging');
                            draggable.setAttribute('draggable', false);
                        }, {
                            once: true
                        });
                    });

                    const dropAbove = form.querySelector(':scope > div >.helium-repeater-form-drop-above');
                    dropAbove.addEventListener('dragenter', event => {
                        if (draggedForm != form && nextFormSibling(draggedForm) != form) {
                            if (form.offsetTop > draggedForm.offsetTop) {
                                moveDownToBelow(draggedForm, previousFormSibling(form));
                            } else {
                                moveUpToAbove(draggedForm, form);
                            }
                        };
                    });
                    const dropBelow = form.querySelector(':scope > div > .helium-repeater-form-drop-below');
                    dropBelow.addEventListener('dragenter', event => {
                        if (draggedForm != form && previousFormSibling(draggedForm) != form) {
                            if (form.offsetTop > draggedForm.offsetTop) {
                                moveDownToBelow(draggedForm, form);
                            } else {
                                moveUpToAbove(draggedForm, nextFormSibling(form));
                            }
                        };
                    });

                    // Hook up the remove buttons
                    const removeButton = form.querySelector(':scope > div > div > .helium-form-actions .helium-repeater-remove')
                    removeButton.addEventListener('click', event => {
                        event.preventDefault();
                        animateDestroy(form);
                        reindex(repeater);
                    });

                    // Hook up the move up buttons
                    const upButton = form.querySelector(':scope > div > div > .helium-form-actions .helium-repeater-move-up')
                    upButton.addEventListener('click', event => {
                        event.preventDefault();
                        const previous = previousFormSibling(form);
                        if (previous) {
                            moveUpToAbove(form, previous);
                        }
                    });

                    // Hook up the move down buttons
                    const downButton = form.querySelector(':scope > div > div > .helium-form-actions .helium-repeater-move-down')
                    downButton.addEventListener('click', event => {
                        event.preventDefault();
                        const next = nextFormSibling(form);
                        if (next) {
                            moveDownToBelow(form, next);
                        }
                    });

                    form.setAttribute('data-helium-init', true);
                });

            reindex(repeater);
        });
    }
}

function addNewForm(repeater)
{
    return fetch('/admin/entities/form-section', {
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
        const form = div.querySelector(':scope > .helium-repeater-form');
        const container = repeater.querySelector(':scope > .helium-repeater-forms-container');
        container.insertAdjacentElement('beforeend', form);

        animateAppear(form);
        reindex(repeater);

        const formCount = container.querySelectorAll(':scope > .helium-repeater-form:not(.removed)').length;
        const maxEntries = repeater.getAttribute('data-max-entries');
        const minEntries = repeater.getAttribute('data-min-entries');
        if (maxEntries > 0 && formCount >= maxEntries) {
            repeater.classList.add('helium-repeater-full');
        }

        if (formCount > minEntries) {
            repeater.classList.remove('helium-repeater-min');
        }

        window.dispatchEvent(new Event('helium-init-forms'));
    })
    .catch(error => {
        console.log(error)
    });
}

function reindex(repeater)
{
    const orderable = repeater.classList.contains('orderable');
    repeater.querySelectorAll(':scope > div > .helium-repeater-form').forEach((form, index) => {
        if (orderable) {
            form.querySelector(':scope > div > div > .helium-sequence-field').value = index++;
        }
        form.querySelector(':scope > div > div > .helium-form-actions .helium-repeater-move-down')
            .classList.toggle('hidden', !orderable || !nextFormSibling(form));
        form.querySelector(':scope > div > div >.helium-form-actions .helium-repeater-move-up')
            .classList.toggle('hidden', !orderable || !previousFormSibling(form));
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

function moveUpToAbove(item, above) {
    let totalOffset = 0;
    let top = item.getBoundingClientRect().height;
    let previous = previousFormSibling(item);
    let stopAt = previousFormSibling(above);

    while (previous && previous != stopAt) {
        totalOffset += previous.getBoundingClientRect().height;
        animateFrom(previous, -top);
        previous = previousFormSibling(previous);
    }

    above.before(item);
    animateFrom(item, totalOffset);
    reindex(item.closest('.helium-repeater-field'));
}

function moveDownToBelow(item, below) {
    let totalOffset = 0;
    let top = item.getBoundingClientRect().height;
    let next = nextFormSibling(item);
    let stopAt = nextFormSibling(below);

    while (next && next != stopAt) {
        totalOffset += next.getBoundingClientRect().height;
        animateFrom(next, top);
        next = nextFormSibling(next);
    }

    below.after(item);
    animateFrom(item, -totalOffset);
    reindex(item.closest('.helium-repeater-field'));
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

    const repeater = item.closest('.helium-repeater-field');
    const formCount = repeater.querySelectorAll(':scope > div > .helium-repeater-form:not(.removed)').length;
    const maxEntries = repeater.getAttribute('data-max-entries');
    const minEntries = repeater.getAttribute('data-min-entries');
    if (maxEntries > 0 && formCount < maxEntries) {
        repeater.classList.remove('helium-repeater-full');
    }

    if (formCount <= minEntries) {
        repeater.classList.add('helium-repeater-min');
    }

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
