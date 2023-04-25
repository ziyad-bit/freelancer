//delete skill
const delete_button = document.getElementsByClassName('delete_btn');

for (let i = 0; i < delete_button.length; i++) {
    delete_button[i].onclick = function (e) {
        let   url = document.querySelector('#delete_route').value;
        const id  = e.target.id;
    
        url=url.substring(0,url.length-2)
    
        axios.delete(url+id)
            .then(res=> {
                if (res.status == 200) {
                    document.querySelector('.user_skill'+id).remove();
                }
            })
            .catch(err=>{
                let error=err.response;
                if (error.status == 422 || error.status == 500) {
                    let err_msg=error.data.error;
                    
                    let error_ele=document.querySelector('.err_msg');
                    
                    error_ele.textContent=err_msg;
                    error_ele.style.display='';
                }
            });
    }
    
}
