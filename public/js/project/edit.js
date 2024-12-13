//delete proposal
const close_btns = document.getElementsByClassName('close_btn');
const delete_btn = document.querySelector('.delete_btn');
const success_ele = document.querySelector('.delete_msg');
const err_ele = document.querySelector('.err_msg');

close_btns.forEach(close_btn => {
    close_btn.onclick = e => {
        const file = e.target.getAttribute('data-file');

        delete_btn.setAttribute('data-file', file);
        delete_btn.removeAttribute('disabled');

        err_ele.style.display = 'none';
        success_ele.style.display = 'none';
    }
});


delete_btn.onclick = e => {
    const file = e.target.getAttribute('data-file');
    const url = document.getElementsByClassName(`${file}`)[0].value;

    delete_btn.disabled = true;

    // eslint-disable-next-line no-undef
    axios.delete(url)
        .then(res => {
            if (res.status == 200) {
                let success_msg = res.data.success;

                success_ele.textContent = success_msg;
                success_ele.style.display = '';

                document.getElementById(`${file}`).remove();
            }
        })
        .catch(err => {
            const error = err.response;
            const error_msg = error.data.error;

            delete_btn.disabled = false;

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

            // eslint-disable-next-line no-undef
            axios.delete(url);
        }

        document.querySelector('#input' + skill_index).remove();
    }
});

const all_inputs = document.querySelectorAll('.input');
let input_values = [];

all_inputs.forEach(input => {
    input_values.push(input.value);
})

// eslint-disable-next-line no-undef
generalEventListener('input', '.input_old', (e) => {
    let id = e.target.getAttribute('id');
    let input_value = e.target.value;
    let skill_id_ele = document.querySelector(`#skill_id_${id}`);

    if (input_values.includes(input_value) && skill_id_ele) {
        console.log('input_values: ', 1);

        skill_id_ele.remove();
    } else {
        let html = `<input type="hidden" name="skills_id[${id}]" id="skill_id_${id}">`;
        const body = document.querySelector('.body');

        body.insertAdjacentHTML('beforeend', html);
    }
})