let submit_btn = document.querySelector('.submit_btn');

let page          = 1;
let projects_data = true;

submit_btn.onclick = function () {
    const parent_element = document.querySelector('.parent_projects');
    let   url            = document.querySelector('.index_url').value;
    let   cursor         = parent_element.getAttribute('data-cursor');

    page++;

    if (projects_data === true) {
        axios.post(url + '?cursor=' + cursor)
            .then(res => {
                if (res.status == 200) {
                    let view   = res.data.view;
                    let new_cursor = res.data.cursor;
                    

                    if (view == '') {
                        projects_data = false;
                        return;
                    }

                    parent_element.setAttribute('data-cursor',new_cursor);
                    parent_element.insertAdjacentHTML('beforeend', view);
                }
            })
            .catch(err => {
                if (err.response.status == 500) {
                    let   error       = err.response.data.error;
                    const err_element = document.querySelector('.err_msg');

                    err_element.textContent = error;
                    err_element.style.display = '';
                }
            });
    }
}