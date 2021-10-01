function Menu() {
  Array.from(document.querySelectorAll('.c-navicon'), el => {
    el.addEventListener('click', toggle('main-nav-open'))
  })

  Array.from(document.querySelectorAll('.c-top_search-trigger'), el => {
    el.addEventListener('click', toggle('search-open', () => {
      if (document.body.classList.contains('search-open')) {
        const input = document.querySelector('[name="q"]');
        if (input) {
          input.focus();
        }
      }
    }))
  })

  function toggle(cl, cb = null) {
    return (e) => {
      document.body.classList.toggle(cl)
      if (cb) cb(e);
    }
  }
}

export default Menu;