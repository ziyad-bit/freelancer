//MARK:get users
// eslint-disable-next-line no-undef
generalEventListener('click', '.plus', e => {
    let request_status = e.target.getAttribute('data-request_status');
    let chat_room_id = e.target.getAttribute('data-chat_room_id');
    let chatroom_users_url = e.target.getAttribute('data-chatroom_users_url');
    let user_names_ele = document.querySelectorAll(`.user_names`);
    let old_user_names_ele = document.querySelectorAll(`.user_names${chat_room_id}`);

    const warning_ele = document.querySelector('.warning_msg');

    document.querySelector(`#get_users_url`).value = chatroom_users_url;
    document.querySelector(`#chat_room_id`).value = chat_room_id;

    if (request_status == 'false') {
        user_names_ele.forEach(user_name => {
            user_name.style.display = 'none';
        });

        // eslint-disable-next-line no-undef
        axios.get(chatroom_users_url)
            .then(res => {
                if (res.status == 200) {
                    let users = res.data.users;
                    const add_body_modal = document.querySelector('.add_body');

                    e.target.setAttribute('data-request_status', 'true');

                    if (users.length > 0) {
                        users.forEach(user => {
                            add_body_modal.insertAdjacentHTML('beforeend',
                                `
                                        <li class = "list-group-item user_names user_names${chat_room_id} user${user.id}">
                    
                                            <img class = "rounded-circle image" alt = "loading"
                                                src   = "/storage/images/users/${user.image}">
                                                    ${user.name}
                    
                                            <form  id   = "add_form${user.id}">
                                            <input type = "hidden" name = "chat_room_id" value = "${chat_room_id}">
                                            <input type = "hidden" name = "user_id" value      = "${user.id}">
                                            </form>        
                    
                                            <button
                                                type  = "button"
                                                class = "btn btn-primary add_btn" data-receiver_id = "${user.id}">
                                                add
                                            </button>
                                        </li>
                                    `);
                        })
                    } else {
                        warning_ele.style.display = '';
                        warning_ele.textContent = 'no users';

                        setTimeout(() => {
                            warning_ele.style.display = 'none';
                        }, 3000);
                    }
                }
            })
    } else {
        user_names_ele.forEach(user_name => {
            user_name.style.display = 'none';
        });

        old_user_names_ele.forEach(user_name => {
            user_name.style.display = '';
        });
    }
})

//MARK:search users
let inputs_arr = new Set();
// eslint-disable-next-line no-undef
const debouncedShowUsers = debounce(async function (show_users_url, inputValue, e) {
    try {
        let   user_names_ele     = document.querySelectorAll(`.user_names`);
        let   chat_room_id       = document.querySelector(`#chat_room_id`).value;
        let   old_user_names_ele = document.querySelectorAll(`.user_names${chat_room_id}`);
        const warning_ele        = document.querySelector('.warning_msg');

        if (inputs_arr.has(inputValue) === false && inputValue !== '') {
            // eslint-disable-next-line no-undef
            let source = axios.CancelToken.source();

            // eslint-disable-next-line no-undef
            const response = await axios.get(show_users_url, { cancelToken: source.token });
            const users = response.data.users;

            inputs_arr.add(inputValue);

            source.cancel();
            // eslint-disable-next-line no-undef
            source = axios.CancelToken.source();

            user_names_ele.forEach(user_name => {
                user_name.style.display = 'none';
            });

            if (users.length > 0) {
                const add_body_modal = document.querySelector('.add_body');

                e.target.setAttribute('data-request_status', 'true');

                users.forEach(user => {
                    add_body_modal.insertAdjacentHTML('beforeend',
                        `
                            <li class = "list-group-item user_names search${inputValue}  user${user.id}">
        
                                <img class = "rounded-circle image" alt = "loading"
                                    src   = "/storage/images/users/${user.image}">
                                        ${user.name}
        
                                <form  id   = "add_form${user.id}">
                                <input type = "hidden" name = "chat_room_id" value = "${chat_room_id}">
                                <input type = "hidden" name = "user_id" value      = "${user.id}">
                                </form>        
        
                                <button
                                    type  = "button"
                                    class = "btn btn-primary add_btn" data-receiver_id = "${user.id}">
                                    add
                                </button>
                            </li>
                        `);
                })

            } else {
                warning_ele.style.display = '';
                warning_ele.textContent = 'no matched results';

                setTimeout(() => {
                    warning_ele.style.display = 'none';
                }, 3000);
            }
        } else {
            user_names_ele.forEach(user_name => {
                user_name.style.display = 'none';
            });

            if (inputValue === '') {
                old_user_names_ele.forEach(user_name => {
                    user_name.style.display = '';
                });

                return
            }

            const old_search_results_ele = document.querySelectorAll(`.search${inputValue}`);
            old_search_results_ele.forEach(user_name => {
                user_name.style.display = '';
            });
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

// eslint-disable-next-line no-undef
generalEventListener('input', '#searchName', e => {
    const inputValue = e.target.value;
    let show_users_url = document.querySelector(`#get_users_url`).value + '?searchName=' + inputValue;

    debouncedShowUsers(show_users_url, inputValue, e);
});


//MARK:send invitation
// eslint-disable-next-line no-undef
generalEventListener('click', '.add_btn', e => {
    e.target.disabled = true;

    let receiver_id = e.target.getAttribute('data-receiver_id');
    let form = document.querySelector(`#add_form${receiver_id}`);
    let send_invitation_url = document.querySelector(`.add_body`).getAttribute('data-send_invitation_url');
    let data = new FormData(form);
    let user_ele = document.querySelector(`.user${receiver_id}`);

    const err_ele = document.querySelector('.err_msg');
    const success_ele = document.querySelector('.success_msg');

    // eslint-disable-next-line no-undef
    axios.post(send_invitation_url, data)
        .then(res => {
            if (res.status == 200) {
                let success_msg = res.data.success_msg;

                err_ele.style.display = 'none';
                success_ele.style.display = '';
                success_ele.textContent = success_msg;

                user_ele.remove();
            }
        })
        .catch(err => {
            e.target.disabled = false;

            let error = err.response;
            if (error.status == 400) {
                let error_msg = error.data.warning_msg;

                success_ele.style.display = 'none';
                err_ele.style.display = '';
                err_ele.textContent = error_msg;

                user_ele.remove();
            }

            if (error.status == 422 || error.status == 409) {
                let error_msg = error.data.message;

                success_ele.style.display = 'none';
                err_ele.style.display = '';
                err_ele.textContent = error_msg;
            }
        })
})
