/* eslint-disable no-undef */
//add skill input
const num_input_ele = document.querySelector('#num_input');
let num_input_ele_val = num_input_ele.value;

let number = 1;
if (num_input_ele_val > 1) {
    number = num_input_ele_val;
}

generalEventListener('click', '.add_button', () => {

    if (number < 20) {
        number++;
        num_input_ele.value = number;

        let show_skills_url = document.querySelector('.input').getAttribute('data-show_skills_url');

        let html = `<div class="form-group skills">
                        <div   id  = "input${number}">
                        <label for = "exampleInputEmail1">
                                - skill
                            </label>

                            <button type = "button" class = "btn-close  delete_skill" id = "${number}">
                                </button>

                            <input list = "skills" autocomplete = "off" id = "${number}"  name = "skills[${number}][name]" 
                                class = "form-control input" data-show_skills_url = "${show_skills_url}" >

                            <small style="color: red;display: none" class="err_msg" >
                                </small>

                            <small style = "color: red" class = "errors" id = "skills_name.${number}_err">
                            </small>
    
                            <input type = "hidden" value = "" name = "skills[${number}][id]" id = "skill_id_${number}">
                        </div>
                    </div>`;

        const body = document.querySelector('.body');

        body.insertAdjacentHTML('beforeend', html);
    } else {
        const err_ele = document.querySelector('#err_msg');

        err_ele.style.display = '';
        err_ele.textContent = "you can't add more than 19 inputs"
    }
})


generalEventListener('click', '.delete_skill', e => {
    const skill_index = e.target.id;
    const url_ele = document.querySelector('#delete_skill_url' + skill_index);
    const input = document.querySelector('#input' + skill_index);

    if (url_ele) {
        const url = url_ele.value;

        axios.delete(url)
            .then(res => {
                if (res.status == 200) {
                    input.remove();

                    if (number > 1) {
                        number--;
                        num_input_ele.value = number;
                    }
                }
            });
    } else {
        input.remove();

        if (number > 1) {
            number--;
            num_input_ele.value = number;
        }
    }
})

let inputs_arr = new Set();
const debouncedShowSkills = debounce(async function (show_skills_url, inputValue) {
    try {
        if (inputs_arr.has(inputValue) === false) {
            let source = axios.CancelToken.source();

            const response    = await axios.get(show_skills_url, { cancelToken: source.token });
            const skills      = response.data.skills;

            inputs_arr.add(inputValue);

            source.cancel();
            source = axios.CancelToken.source();

            if (skills.length > 0) {
                const datalist = document.querySelector('#skills');
                datalist.innerHTML = '';

                skills.forEach(skill => {
                    const option = document.createElement('option');

                    option.value = skill.skill;
                    option.setAttribute('data-value', skill.id);
                    datalist.appendChild(option);
                });
            }
        }
    } catch (error) {
        const err_ele = document.querySelector('.err_msg');
        let err;

        if (error.response) {
            err = error.response.data.message;
        } else if (error.request) {
            err = 'Network error';
        } else {
            err = 'Something went wrong';
        }

        err_ele.style.display = '';
        err_ele.textContent = err;

        setTimeout(() => {
            err_ele.style.display = 'none';
        }, 3000);
    }
});

generalEventListener('input', '.input', e => {
    const input = e.target;
    const options = document.querySelectorAll('#skills option');
    const id = input.getAttribute('id');
    const hiddenInput = document.getElementById(`skill_id_${id}`);
    const inputValue = input.value;
    const show_skills_url = input.getAttribute('data-show_skills_url') + '/' + inputValue;

    debouncedShowSkills(show_skills_url, inputValue);

    // Update hidden input with selected skill ID
    for (let i = 0; i < options.length; i++) {
        const option = options[i];

        if (option.value === inputValue) {
            if (hiddenInput) {
                hiddenInput.value = option.getAttribute('data-value');
                break;
            }
        } else {
            if (hiddenInput && inputValue !== '') {
                hiddenInput.value = inputValue;
            } else {
                hiddenInput.value = "";
            }
        }
    }

});