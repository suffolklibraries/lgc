function Search() {
  const searchForm = document.querySelector('.js-search');
  if (!searchForm) return;

  searchForm.addEventListener('change', onChange);
  searchForm.addEventListener('submit', onSubmit);
}

function onChange(e) {
  console.log('Nothing?')
  const form = e.currentTarget;
  const checkedCategories = form.querySelectorAll('[name="category[]"]:checked');

  if (e.target.name === 'category-clear') {
    form.querySelectorAll('[name="category[]"]').forEach(el => {
      el.checked = false;
    });

    if (!checkedCategories.length) {
      e.target.checked = true;
    }

    setTimeout(() => {
      form.dispatchEvent(new Event("submit")); 
    }, 100);

    return;
  }

  if (e.target.name === 'category[]') {
    form.querySelectorAll('[name="category-clear"]').forEach(el => {
      el.checked = !checkedCategories.length;
    });
  }

  if (form.classList.contains('js-search--simple')) {
    setTimeout(() => {
      form.submit(); 
    }, 100);
    return true;
  }

  if (e.target.name === 'place') {
    setTimeout(() => {
      form.dispatchEvent(new Event("submit")); 
    }, 100);
    return;
  }

  console.log(1)

  form.dispatchEvent(new Event('submit'));
}

function onSubmit(e) {
  if (e.currentTarget.classList.contains('js-search--simple')) { 
    return;
  }

  console.log(2)

  e.preventDefault();
  const formData = new FormData(e.currentTarget);
  const searchParams = new URLSearchParams();
  searchParams.delete('category-clear');

  const keys = [...formData.keys()];
  keys.forEach(key => {
    if (keys.filter(x => x === key).length > 1) {
      formData.getAll(key).forEach(value => {
        searchParams.append(key, value);
      })

      keys.forEach((k, i) => {
        if (k === key) {
          keys.splice(i, 1)
        }
      })
    } else {
      searchParams.set(key, formData.get(key))
    }
  })

  const queryString = searchParams.toString();
  const url = window.location.pathname + '?' + queryString;

  fetch(url)
    .then((r) => r.text())
    .then(result => Promise.resolve(result.replace( /<script(?! type="application\/json").\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, "" )))
    .then((response) => {
      const results = document.createElement('div');
      results.innerHTML = response;
      
      const newEvents = results.querySelector('.js-events');
      const oldEvents = document.querySelector('.js-events');
      if (newEvents && oldEvents) {
        oldEvents.innerHTML = newEvents.innerHTML;
      }

      const newHeader = results.querySelector('.js-search-header');
      const oldHeader = document.querySelector('.js-search-header');
      if (newHeader && oldHeader) {
        oldHeader.innerHTML = newHeader.innerHTML;
      }

      const newPagination = results.querySelector('.js-pagination');
      const oldPagination = document.querySelector('.js-pagination');
      if (newPagination && oldPagination) {
        oldPagination.innerHTML = newPagination.innerHTML;
      }

      const newJSON = results.querySelector('#json-events');

      if (newJSON) {
        window.dispatchEvent(new CustomEvent('render-markers', {
          detail: JSON.parse(newJSON.innerHTML)
        }));
      }

      window.history.replaceState('', '', url);
    })
    .catch(console.error);

}

export default Search;
