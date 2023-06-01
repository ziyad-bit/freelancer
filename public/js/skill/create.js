//store skill
const button = document.querySelector('.submit_btn');
button.onclick = function () {
    const url = document.querySelector('#store_route').value;

    let skills = document.querySelector('#skills_input').value;
        skills = JSON.parse(skills);

    let form     = document.getElementById('skill_form');
        formData = new FormData(form);

    for (const pair of formData.entries()) {

        for (let i = 0; i < skills.length; i++) {
            const skill = skills[i];

            if (skill.skill == pair[1]) {
                formData.set(pair[0], skill.id)
            }
        }
    }

    axios.post(url, formData)
        .then(res=> {
            if (res.status == 200) {
                let success_msg   = res.data.success;

                document.querySelector('.errors').style.display='none';
                

                const success_ele   = document.querySelector('.success_msg'),
                        inputs      = document.getElementsByClassName('input');

                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].value='';
                }

                success_ele.textContent   = success_msg;
                success_ele.style.display = '';
            }
        })
        .catch(err=>{
            let error=err.response;
            if (error.status == 422) {
                let err_msgs=error.data.errors;
                for (const [key, value] of Object.entries(err_msgs)) {
                    let error_ele=document.getElementById(key+'_err');
                    
                    error_ele.textContent=value[0];
                    error_ele.style.display='';
                }
            }
        });
}
