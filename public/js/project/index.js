let submit_btn    = document.querySelector('.submit_btn');

submit_btn.onclick = function () {
    const parent_element = document.querySelector('.parent_projects');
    const err_element    = document.querySelector('.err_msg');

    let   cursor         = parent_element.getAttribute('data-cursor');
    let   url            = document.querySelector('.index_url').value;
    let   search_value   = document.querySelector('#search').value;
    
    if (cursor != 'false') {
        axios.post(url + '?cursor=' + cursor, { 'search': search_value })
            .then(res => {
                if (res.status == 200) {
                    let view       = res.data.view;
                    let new_cursor = res.data.cursor;

                    parent_element.setAttribute('data-cursor', new_cursor);
                    parent_element.insertAdjacentHTML('beforeend', view);
                }
            })
            .catch(err => {
                if (err.response.status == 500) {
                    let   error       = err.response.data.error;
                    
                    err_element.textContent   = error;
                    err_element.style.display = '';
                }
            });
    }else{
        submit_btn.style.display  = 'none';
        err_element.textContent   = 'no projects'
        err_element.style.display = '';
    }
}

