const next_btn = document.querySelector('#next_btn');
next_btn.onclick = () => {
    next_btn.disabled = true;

    const checkout_btn = document.querySelector('.checkout_btn');
    let amount = document.querySelector('#amount').value;
    let url = checkout_btn.href;

    url = url.substring(0, url.lastIndexOf('/'));

    checkout_btn.href = url + '/' + amount;
    checkout_btn.style.display = '';
}