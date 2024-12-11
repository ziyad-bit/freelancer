/* eslint-disable no-undef */
//add skill input
const num_input_ele     = document.querySelector('#num_input');
let   num_input_ele_val = num_input_ele.value;

let number = 1;
if (num_input_ele_val > 1) {
    number = num_input_ele_val;
}

generalEventListener('click', '.add_button',() => {
    if (number < 20) {
        number++;
        num_input_ele.value = number;

        let html = `<div class="form-group skills">
                        <div id="input${number}}">
                            <label for="exampleInputEmail1">
                                - skill
                            </label>

                            <button type="button" class="btn-close  delete_skill" id="${number}">
                            </button>

                            <input list="skills" id="${number}"  name="skills_name[${number}]" class="form-control input" >
                        </div>
    
                        <small style="color: red" class="errors" id="skills_name.${number}_err">
                            
                        </small>
    
                        <input type="hidden" name="skills_id[${number}]" id="skill_id_${number}">
                    </div>`;

        const body = document.querySelector('#skills_input');

        body.insertAdjacentHTML('beforeend', html);
    } else {
        const err_ele = document.querySelector('#err_msg');

        err_ele.style.display = '';
        err_ele.textContent = "you can't add more than 19 inputs"
    }
})

generalEventListener('input', '.input', e => {
    let input       = e.target,
        options     = document.querySelectorAll('#skills option'),
        id          = input.getAttribute('id'),
        hiddenInput = document.getElementById(`skill_id_${id}`),
        inputValue  = input.value;

    for (let i = 0; i < options.length; i++) {
        let option = options[i];

        if (option.value === inputValue) {
            hiddenInput.value = option.getAttribute('data-value');
            break;
        }else{
            hiddenInput.value = 0;
        }
    }
})