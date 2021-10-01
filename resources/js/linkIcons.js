function LinkIcons() {
  document.querySelectorAll('.js-link-icons a:first-child').forEach(anchor => {
    if (anchor.parentNode.childNodes[0] === anchor) {
      anchor.classList.add('c-link-icon')
    } 
  });
}

export default LinkIcons;
