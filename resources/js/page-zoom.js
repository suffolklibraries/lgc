function PageZoom () {
    const increaseButton = document.querySelector('.subheader .increase');
    const decreaseButton = document.querySelector('.subheader .decrease');

    const initialScale = 100;
    var currentScale = null;

    if(sessionStorage.getItem('text-scale')) {
        currentScale = parseInt(sessionStorage.getItem('text-scale'));
        scale(currentScale);
    } else {
        currentScale = initialScale;
    }

    if(!increaseButton || !decreaseButton) return;

    document.addEventListener('click', (e) => {

        if(e.target.classList.contains('increase') || e.target.classList.contains('decrease')) {
            if(e.target.classList.contains('increase')) {
                scale(currentScale + 20);
            } else if(e.target.classList.contains('decrease')) {
                scale(currentScale - 20);
            }
        } else {
            return;
        };

    })

    function scale(scale) {
        if(scale <= 200 && scale >= 60) {
            document.documentElement.style.fontSize = `${scale}%`;
            currentScale = scale;
            sessionStorage.setItem('text-scale', currentScale);
        }
    }

}

export default PageZoom;
