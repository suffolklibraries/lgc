function Tabs() {
  const tabTriggers = document.querySelectorAll('.c-tab-trigger');
  const tabs = document.querySelectorAll('.c-tab');

  tabTriggers.forEach(trigger => {
    trigger.addEventListener('click', onClick);
  });

  function onClick(e) {
    e.preventDefault();
    const currentTrigger = e.currentTarget;
    const currentTab = document.querySelector(currentTrigger.getAttribute('href'));

    tabs.forEach(tab => {
      tab.classList.toggle('c-tab--active', currentTab === tab);
    });

    tabTriggers.forEach(trigger => {
      trigger.setAttribute('aria-expanded', currentTrigger === trigger ? 'true' : 'false');
    });

    if (currentTab.classList.contains('c-tab--map')) {
      setTimeout(() => {
        window.dispatchEvent(new Event('map-tab'));
        window.dispatchEvent(new Event('toggle-pagination'));
      }, 100);
    }

    if(currentTab.classList.contains('c-tab--list')) {
      setTimeout(() => {
        window.dispatchEvent(new Event('toggle-pagination'));
      }, 100);
    }
  }
}

export default Tabs;
