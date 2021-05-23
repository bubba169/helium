// Store the currently dragged form element
var draggedForm = null;

const init = () => {
    const repeaters = document.querySelectorAll('.helium-repeater-field');
    // A simple event listener setup on drag start that prevents the ghost
    // image snapping back to the original position
    const bodyDrag = e => e.preventDefault();

    if (repeaters.length) {
        repeaters.forEach((repeater) => {
            const addButton = repeater.querySelector('.helium-repeater-actions [data-action=add]');
            const minEntries = repeater.getAttribute('data-min-entries');
            const maxEntries = repeater.getAttribute('data-max-entries');

            // Hook up the add button
            if (!addButton.hasAttribute('data-helium-init')) {
                addButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    addNewForm(addButton.closest('.helium-repeater-field'));
                });
                addButton.setAttribute('data-helium-init', true);
            }

            // Hook up the drag button
            repeater.querySelectorAll('.helium-repeater-form:not([data-helium-init])')
                .forEach(form => {
                    const field = repeater;
                    const handle = form.querySelector('.helium-repeater-drag');
                    handle.addEventListener('mousedown', event => {
                        form.firstElementChild.setAttribute('draggable', true);
                    });
                    form.firstElementChild.addEventListener('dragstart', event => {
                        document.body.addEventListener('dragover', bodyDrag);
                        draggedForm = form;
                        form.style.zIndex = 1;
                        form.firstElementChild.style.border = '1px dashed black';
                        form.firstElementChild.style.backgroundColor = '#EFF6FF';
                        form.firstElementChild.addEventListener('dragend', event => {
                            document.body.removeEventListener('dragover', bodyDrag);
                            form.style.zIndex = null;
                            form.firstElementChild.style.border = null;
                            form.firstElementChild.style.backgroundColor = null;
                            form.firstElementChild.setAttribute('draggable', false);
                            field.querySelectorAll('.helium-repeater-form-drop-above, .helium-repeater-form-drop-below')
                                .forEach(el => {
                                    // Disable all of the drop zones
                                    el.style.pointerEvents = null;
                                });
                        }, {
                            once: true
                        });

                        // Activate all of the drop zones
                        field.querySelectorAll('.helium-repeater-form-drop-above, .helium-repeater-form-drop-below')
                            .forEach(el => {
                                el.style.pointerEvents = 'auto';
                            });
                    });
                    const dropAbove = form.querySelector('.helium-repeater-form-drop-above');
                    dropAbove.addEventListener('dragenter', event => {
                        if (draggedForm != form && nextFormSibling(draggedForm) != form) {
                            if (form.offsetTop > draggedForm.offsetTop) {
                                moveDownToBelow(draggedForm, previousFormSibling(form));
                            } else {
                                moveUpToAbove(draggedForm, form);
                            }
                        };
                    });
                    const dropBelow = form.querySelector('.helium-repeater-form-drop-below');
                    dropBelow.addEventListener('dragenter', event => {
                        if (draggedForm != form && previousFormSibling(draggedForm) != form) {
                            if (form.offsetTop > draggedForm.offsetTop) {
                                moveDownToBelow(draggedForm, form);
                            } else {
                                moveUpToAbove(draggedForm, nextFormSibling(form));
                            }
                        };
                    });
                    form.setAttribute('data-helium-init', true);
                });


            // Hook up the remove buttons
            repeater.querySelectorAll('.helium-repeater-remove:not([data-helium-init])')
                .forEach(removeButton => {
                    removeButton.addEventListener('click', event => {
                        event.preventDefault();
                        const form = removeButton.closest('.helium-repeater-form');
                        animateDestroy(form);
                        reindex(repeater);
                    });
                    removeButton.setAttribute('data-helium-init', true);
                });

            // Hook up the move up buttons
            repeater.querySelectorAll('.helium-repeater-move-up:not([data-helium-init])')
                .forEach(upButton => {
                    upButton.addEventListener('click', function () {
                        const form = upButton.closest('.helium-repeater-form');
                        const previous = previousFormSibling(form);
                        if (previous) {
                            moveUpToAbove(form, previous);
                        }
                    });

                    upButton.setAttribute('data-helium-init', true);
                });

            // Hook up the move down buttons
            repeater.querySelectorAll('.helium-repeater-move-down:not([data-helium-init])')
                .forEach(downButton => {
                    downButton.addEventListener('click', function () {
                        const form = downButton.closest('.helium-repeater-form');
                        const next = nextFormSibling(form);
                        if (next) {
                            moveDownToBelow(form, next);
                        }
                    });

                    downButton.setAttribute('data-helium-init', true);
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
        const form = div.querySelector('.helium-repeater-form');

        repeater.querySelector('.helium-repeater-forms-container')
            .insertAdjacentElement('beforeend', form);

        animateAppear(form);
        reindex(repeater);

        const formCount = repeater.querySelectorAll('.helium-repeater-form:not(.removed)').length;
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
    const formCount = repeater.querySelectorAll('.helium-repeater-form:not(.removed)').length;
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
