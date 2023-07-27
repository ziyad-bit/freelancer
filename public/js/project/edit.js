//delete proposal
const close_btn = document.querySelector('.close_btn');
const delete_btn = document.querySelector('.delete_btn');

close_btn.onclick = e => {
    const file  = e.target.getAttribute('data-file');
    const url  = delete_btn.setAttribute('data-file',file);
}

delete_btn.onclick = e => {
    const file  = e.target.getAttribute('data-file');
    const url  = document.getElementsByClassName(`${file}`)[0].value;

    axios.delete(url)
        .then(res=> {
            if (res.status == 200) {
                let success_msg   = res.data.success;
                
                const success_ele   = document.querySelector('.delete_msg');

                success_ele.textContent   = success_msg;
                success_ele.style.display = '';

                document.getElementById(`${file}`).remove();
            }
        })
        .catch(err=>{
            
        });
}