const init = () => {
    const btns = document.getElementsByClassName('helium-toggle');
    Array.prototype.forEach.call(btns, btn => {
        const target = document.querySelector(btn.getAttribute('data-target'));

        if (!target.classList.contains('collapsed')) {
            target.style.maxHeight = target.scrollHeight + 'px';
        }

        btn.addEventListener('click', event => {
            if (!target.classList.contains('collapsed')) {
                target.style.maxHeight = '0px';
                target.classList.add('collapsed');
            } else {
                target.style.maxHeight = target.scrollHeight + 'px';
                target.classList.remove('collapsed');
            }
        });
    });
}

init();
