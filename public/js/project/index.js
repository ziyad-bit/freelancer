let submit_btn = document.querySelector('.submit_btn');

let page = 1;
let projects_data = true;

submit_btn.onclick = function () {
    page++;

    let url = document.querySelector('.index_url').value;
    console.log('url: ', url);

    if (projects_data === true) {
        axios.post(url + '?page=' + page)
            .then(res => {
                if (res.status == 200) {
                    let view = res.data.view;

                    if (view == '') {
                        projects_data = false;
                        return;
                    }

                    document.querySelector('.parent_projects').insertAdjacentHTML('beforeend', view);
                }
            })
            .catch(err => {
                let error = err.response.data.error;
                const err_element = document.querySelector('.err_msg');

                err_element.textContent = error;
                err_element.style.display = '';
            });
    }

}