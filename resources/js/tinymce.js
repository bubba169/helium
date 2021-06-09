const init = () => {
    const inputs = document.querySelectorAll('.helium-tinymce:not([data-helium-init])');
    inputs.forEach(input => {
        tinymce.init({
            selector: '#' + input.id,
            ...JSON.parse(input.getAttribute('data-config')),
        });
    });
}

window.addEventListener('helium-init-forms', init);
