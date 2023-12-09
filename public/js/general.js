//add event listener
function generalEventListener(type, selector, callback) {
    document.addEventListener(type, e => {
        if (e.target.matches(selector)) {
            callback(e);
        }
    });
}

//debounce
function debounce(cb,delay=1000) {
    let timeout;

    return (...args)=>{
        clearTimeout(timeout);

        timeout=setTimeout(() => {
                cb(...args);
            }, delay);
    }
}