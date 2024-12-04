//delete proposal
const close_btns = document.getElementsByClassName('close_btn');
const delete_btn = document.querySelector('.delete_btn');

close_btns.forEach(close_btn => {
    close_btn.onclick = e => {
        const file = e.target.getAttribute('data-file');
        
        delete_btn.setAttribute('data-file', file);
        delete_btn.removeAttribute('disabled');
    }
});


delete_btn.onclick = e => {
    const file = e.target.getAttribute('data-file');
    const url = document.getElementsByClassName(`${file}`)[0].value;
    const success_ele = document.querySelector('.delete_msg');
    const err_ele = document.querySelector('.err_msg');

    err_ele.style.display = 'none';
    success_ele.style.display = 'none';

    axios.delete(url)
        .then(res => {
            if (res.status == 200) {
                let success_msg = res.data.success;

                success_ele.textContent = success_msg;
                success_ele.style.display = '';
                delete_btn.disabled = true;

                document.getElementById(`${file}`).remove();
            }
        })
        .catch(err => {
            const error = err.response;
            const error_msg = error.data.error;

            if (error.status === 404) {
                err_ele.textContent = error_msg;
                err_ele.style.display = '';
            }

        });
}


const delete_skill_btns = document.getElementsByClassName('delete_skill');

delete_skill_btns.forEach(delete_skill_btn => {
    delete_skill_btn.onclick = e => {
        const skill_index = e.target.id;
        const url_ele = document.querySelector('#delete_skill_url' + skill_index);

        if (url_ele) {
            const url = url_ele.value;

            axios.delete(url)
        }

        document.querySelector('#input' + skill_index).remove();
    }
});

