const init = () => {
    const tabs = document.querySelectorAll('.helium-tab');
    if (tabs.length) {
        tabs.forEach((tab) => {
            tab.addEventListener('click', (event) => {
                setActiveTab(tab)
            });
        });

        setActiveTab(document.getElementById('helium-tab-main'));
    }
}


function setActiveTab(tab) {
    // Show the active tab content
    const tabContent = document.querySelectorAll('.helium-tab-content');
    tabContent.forEach((content) => {
        content.style.display = (tab.getAttribute('data-tab-content-id') == content.id) ? 'block' : 'none';
    });

    // Set the active tab
    const tabs = document.querySelectorAll('.helium-tab.active');
    tabs.forEach((tab) => {
        tab.classList.remove('active');
    });
    tab.classList.add('active');
}

init();
