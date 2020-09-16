const init = () => {
    // Need to highlight tabs with errors
    const tabs = document.querySelectorAll('.nav-tabs .nav-item');
    Array.prototype.forEach.call(tabs, (tab) => {

        // Find any "required" form elements
        const inputs = document.querySelectorAll(
            tab.getAttribute('href') + ' input[required],select[required],textarea[required]'
        );

        Array.prototype.forEach.call(inputs, (input) => {
            input.addEventListener('change', validateTab.bind(tab));
            input.addEventListener('invalid', validateTab.bind(tab));
        });

    });

    const forms = document.getElementsByClassName('needs-validation');
    Array.prototype.forEach.call(forms, (form) => {
        form.addEventListener('submit', (event) => {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation()
            }
            form.classList.add('was-validated');
        });
    })
}

function validateTab()
{
    if (document.querySelector(this.getAttribute('href') + ' :invalid')) {
        this.classList.add('has-error');
    } else {
        this.classList.remove('has-error');
    }
}

init();
