//add skill input
const num_input_ele     = document.querySelector('#num_input');
let   num_input_ele_val = num_input_ele.value;

let number   = 1;
if (num_input_ele_val > 1) {
    number        = num_input_ele_val;
}

generalEventListener('click', '.add_button', e => {
    number++;

    num_input_ele.value=number;

    let html = `<div class="form-group skills">
                    <label for="exampleInputEmail1">
                    ${number}- skill
                    </label>
                    <input list="skills" name="skills_name[${number}]" class="form-control input">

                        <small style="color: red" class="errors" id="skills_name.${number}_err">
                            
                        </small>
                    </datalist>
                </div>`;

    const body = document.querySelector('#skills_input');

    body.insertAdjacentHTML('beforeend', html);
})