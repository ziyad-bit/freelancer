let index_data = true;
window.onscroll = function () {
    if (window.scrollY + window.innerHeight -16 >= document.body.clientHeight && index_data) {
        const table_body_ele = document.querySelector('.table_body');

        let index_url = table_body_ele.getAttribute('data-index_url');
        let created_at = table_body_ele.lastElementChild.getAttribute('data-created_at');

        axios.get(index_url+'/'+created_at)
            .then(res => {
                if (res.status == 200) {
                    let view =String(res.data.view) ;

                    if (view === "") {
                        index_data = false;
                        return;
                    }

                    table_body_ele.insertAdjacentHTML('beforeend',view);
                }
            })
    }
}