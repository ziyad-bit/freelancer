  /* eslint-disable no-undef */
  //update proposal
const edit_btn = document.querySelector('.edit_btn');
if (edit_btn) {
    edit_btn.onclick =() => {
        const price_input       = document.querySelector('.price_input');
        const num_of_days_input = document.querySelector('.num_of_days_input');
        const content_input     = document.querySelector('.content_input');

        const price_ele       = document.querySelector('.price');
        const num_of_days_ele = document.querySelector('.num_of_days');
        const content_ele     = document.querySelector('.content');

        price_input.value       = price_ele.textContent;
        num_of_days_input.value = num_of_days_ele.textContent;
        content_input.value     = content_ele.textContent;

        const update_btn = document.querySelector('.update_btn');

        update_btn.onclick =() => {
            const url = document.querySelector('#update_url').value;

            let form     = document.getElementById('proposal_form'),
                formData = new FormData(form);

            axios.post(url, formData)
                .then(res => {
                    if (res.status == 200) {
                        let success_msg = res.data.success;

                        document.querySelector('.errors').style.display = 'none';

                        const success_ele = document.querySelector('.success_msg');

                        success_ele.textContent   = success_msg;
                        success_ele.style.display = '';

                        price_ele.textContent       = price_input.value;
                        num_of_days_ele.textContent = num_of_days_input.value;
                        content_ele.textContent     = content_input.value;
                    }
                })
                .catch(err => {
                    let error = err.response;
                    if (error.status == 422) {
                        let err_msgs = error.data.errors;
                        for (const [key, value] of Object.entries(err_msgs)) {
                            let error_ele = document.getElementById(key + '_err');

                            error_ele.textContent   = value[0];
                            error_ele.style.display = '';
                        }
                    }
                });
        }
    }

      //delete proposal
    const delete_btn   = document.querySelector('.delete_btn');
    delete_btn.onclick = () => {
        const url = document.querySelector('#delete_url').value;

        axios.delete(url)
            .then(res => {
                if (res.status == 200) {
                    let   success_msg = res.data.success;
                    const success_ele = document.querySelector('.delete_msg');

                    success_ele.textContent   = success_msg;
                    success_ele.style.display = '';

                    document.querySelector('.proposal').remove();
                }
            })
    }
}

  //load more proposals by infinite scrolling
window.onscroll = function () {
    const proposal_wrapper = document.querySelector('#proposal_wrapper');
    const cursor           = proposal_wrapper.getAttribute('data-cursor');

    if (window.scrollY + window.innerHeight - 16 >= document.body.clientHeight && cursor !='false') {
        let show_url = proposal_wrapper.getAttribute('data-show_url');

        axios.get(show_url+`?cursor=${cursor}`)
            .then(res => {
                if (res.status == 200) {
                    let view       = res.data.view;
                    let new_cursor = res.data.cursor;

                    proposal_wrapper.setAttribute('data-cursor',new_cursor);
                    proposal_wrapper.insertAdjacentHTML('beforeend',view);
                }
            })
    }
}

