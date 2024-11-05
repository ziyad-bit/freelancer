const chat_room_id = document.querySelector('.user_btn').getAttribute('data-selected_chat_room_id');

  // MARK: leave all channels
let subscribedChatChannels = new Set();

window.onbeforeunload = function () {
    subscribedChatChannels.forEach(key => {
        Echo.leaveChannel(`chat-room.${key}`);
    })
}

  //MARK:scroll to chatroom
if (chat_room_id) {
    const scrollableDiv     = document.querySelector('.list_tab_users');
    const elementToScrollTo = document.querySelector('.chat_room_' + chat_room_id);

    const offsetTop = elementToScrollTo.offsetTop;

    scrollableDiv.scrollTop = offsetTop;
}

  //MARK:get Old msgs
let old_msg = true;

function loadOldMessages() {
    const chat_box = document.getElementsByClassName('chat_body')

    for (let i = 0; i < chat_box.length; i++) {
        chat_box[i].scrollTo({
            top     : 1000,
            behavior: 'smooth'
        })

        chat_box[i].onscroll = function () {
            if (chat_box[i].scrollTop == 0) {
                if (old_msg == true) {
                    let first_msg_id = this.firstElementChild.id,
                        chat_room_id = this.getAttribute('data-chat_room_id');

                    const box = document.getElementsByClassName('box' + chat_room_id)[0];

                    axios.put("/message/show-old/" + chat_room_id, { 'first_msg_id': first_msg_id })
                        .then(res => {
                            if (res.status == 200) {
                                let view = res.data.view;

                                if (view !== '') {
                                    box.insertAdjacentHTML('afterbegin', view);

                                    box.scrollTo({
                                        top     : 100,
                                        behavior: 'smooth'
                                    })
                                } else {
                                    old_msg = false;
                                }
                            }
                        })
                        .catch(err => {
                            old_msg = false;
                        })
                }
            }
        }

    }
}

loadOldMessages()

  //MARK:get chatrooms 
const chat_room_box          = document.querySelector('.list_tab_users');
let   data_chat_rooms_status = true;

function loadPages() {
    let message_id = chat_room_box.lastElementChild.getAttribute('data-message_id');

    if (data_chat_rooms_status) {
        axios.get("/chat-room/show-more/" + message_id)
            .then(res => {
                if (res.status == 200) {
                    let chat_room_view = res.data.chat_room_view,
                        chat_box_view  = res.data.chat_box_view;

                    if (chat_room_view !== '') {
                        chat_room_box.insertAdjacentHTML('beforeend', chat_room_view);

                        document.querySelector('.box_msgs')
                            .insertAdjacentHTML('beforeend', chat_box_view)

                        loadOldMessages();
                    } else {
                        data_chat_rooms_status = false;
                    }
                }
            })
    }
}

let page                   = 1;
    chat_room_box.onscroll = function () {
    if (chat_room_box.offsetHeight == chat_room_box.scrollHeight - chat_room_box.scrollTop) {
        page++
        loadPages();
    }
}

  //MARK:store message
function storeMsg(e) {
    e.preventDefault();

    let chat_room_id = e.target.parentElement.getAttribute('data-chat_room_id'),
        form         = document.querySelector('#form' + chat_room_id),
        formData     = new FormData(form);

    const msg_err = document.getElementsByClassName(`msg_err${chat_room_id}`)[0];

    axios.post('/message', formData)
        .then(res => {
            if (res.status == 200) {
                let auth_name  = document.getElementById('auth_name').value,
                    auth_photo = document.getElementById('auth_photo').value,
                    message    = document.getElementById(`msg${chat_room_id}`).value;

                const box        = document.getElementsByClassName('box' + chat_room_id)[0];

                msg_err.textContent = '';

                document.getElementById('msg' + chat_room_id).value = '';

                box.insertAdjacentHTML('beforeend',
                    `
                <img  class = "rounded-circle image" src = "/storage/images/users/${auth_photo}" alt = "loading">
                <span class = "user_name">${auth_name}</span>
                <p    class = "user_message">${message}</p>
                    `
                )

                const uploaded_files_ele = document.querySelectorAll('.file_uploaded');

                uploaded_files_ele.forEach(function (file_ele) {
                    let file_src = file_ele.src;
                    let type     = file_ele.tagName;

                    if (type === 'img') {
                        box.insertAdjacentHTML('beforeend',
                            `
                        <img class = "rounded-circle image" src = "${file_src}" alt = "loading">
                            `
                        )
                    } else if (type === 'video') {
                        box.insertAdjacentHTML('beforeend',
                            `
                            <video class = "file_sent" src = "${file_src}"></video>
                            `
                        )

                    } else {
                        box.insertAdjacentHTML('beforeend',
                            `
                            <iframe class = "file_sent" src = "${file_src}"></iframe>
                            `
                        )
                    }
                })

                box.scrollTo({
                    top     : 10000,
                    behavior: 'smooth'
                })

                const input_files = document.querySelectorAll('.input_files');
                input_files.forEach(function (input_file) {
                    input_file.remove();
                })

                const files_uploaded = document.querySelectorAll(`.files_container${chat_room_id} .file_uploaded`);
                files_uploaded.forEach(function (file_uploaded) {
                    file_uploaded.remove();
                })

                document.querySelector(`.files_container${chat_room_id}`).style.display          = 'none';
                document.querySelector(`.chat_room_${chat_room_id} div p .msg_text`).textContent = message;
            }
        })
        .catch(err => {
            let error = err.response;
            if (error.status == 422) {
                let err_msgs = error.data.errors;

                for (const [key, value] of Object.entries(err_msgs)) {
                    msg_err.textContent   = value[0];
                    msg_err.style.display = '';
                }
            }
        });
}


  //MARK:file upload
let file_number = 0;

function upload_file(path, type, form_upload, chat_room_id) {
    let upload_url = document.querySelector('#upload_url').value;

    document.querySelector(`#app_input${chat_room_id}`).value   = '';
    document.querySelector(`#video_input${chat_room_id}`).value = '';
    document.querySelector(`#image_input${chat_room_id}`).value = '';

    axios.post(upload_url, form_upload)
        .then(res => {
            if (res.status == 200) {
                let file_name = res.data.file_name;

                file_number++;

                if (type === 'app') {
                    

                    var file_ele    = `<iframe class="file_uploaded" src="${path + file_name}"></iframe>`;
                    var file_inputs = `<input type="hidden" class="input_files" name="files[${file_number}][name]" value="${file_name}">
                        <input type="hidden" class="input_files" name="files[${file_number}][type]" value="application">`;
                } else if (type === 'image') {
                    

                    var file_ele    = `<img class="file_uploaded" src="${path + file_name}"></img>`;
                    var file_inputs = `<input type="hidden" class="input_files" name="files[${file_number}][name]" value="${file_name}">
                        <input type="hidden" class="input_files" name="files[${file_number}][type]" value="image">`;
                } else {
                    

                    var file_ele    = `<video class="file_uploaded" src="${path + file_name}"></video>`;
                    var file_inputs = `<input type="hidden" class="input_files" name="files[${file_number}][name]" value="${file_name}">
                        <input type="hidden" class="input_files" name="files[${file_number}][type]" value="video">`;
                }

                document.querySelector(`#form${chat_room_id}`)
                    .insertAdjacentHTML('afterbegin', file_inputs);

                document.querySelector(`.files_container${chat_room_id} .body_container`)
                    .insertAdjacentHTML('afterbegin', file_ele);

                document.querySelector(`.files_container${chat_room_id}`).style.display = '';
            }
        })
        .catch(err => {
            let error = err.response;
            if (error.status == 422) {
                let   err_msgs    = error.data.errors;
                let   chatroom_id = e.target.getAttribute('data-chat_room_id');
                const msg_ele     = document.querySelector(`.msg_err${chatroom_id}`);

                for (const [key, value] of Object.entries(err_msgs)) {
                    msg_ele.textContent   = value[0];
                    msg_ele.style.display = '';
                }
            }
        });
}

generalEventListener('input', '.file_input', e => {
    let chat_room_id = e.target.getAttribute('data-chat_room_id');
    let file_input   = document.querySelector(`#app_input${chat_room_id}`);
    let form         = document.querySelector(`#form_upload_app${chat_room_id}`);
    let form_upload  = new FormData(form);

    if (file_input.value) {
        let type = 'app';
        let path = '/storage/applications/messages/';

        upload_file(path, type, form_upload, chat_room_id);
    }
})

generalEventListener('input', '.file_input', e => {
    let chat_room_id = e.target.getAttribute('data-chat_room_id');
    let file_input   = document.querySelector(`#image_input${chat_room_id}`);
    let form         = document.querySelector(`#form_upload_image${chat_room_id}`);
    let form_upload  = new FormData(form);

    if (file_input.value) {
        let type = 'image';
        let path = '/storage/images/messages/';

        upload_file(path, type, form_upload, chat_room_id);
    }
})

generalEventListener('input', '.file_input', e => {
    let chat_room_id = e.target.getAttribute('data-chat_room_id');
    let file_input   = document.querySelector(`#video_input${chat_room_id}`);
    let form         = document.querySelector(`#form_upload_video${chat_room_id}`);
    let form_upload  = new FormData(form);

    if (file_input.value) {
        let type = 'video';
        let path = '/storage/videos/messages/';

        upload_file(path, type, form_upload, chat_room_id);
    }
})

generalEventListener('click', '.send_btn', e => {
    storeMsg(e);
})

generalEventListener('keypress', '.send_input', e => {
    if (e.keyCode == 13 && !e.shiftKey) {
        storeMsg(e);
    }
})

  //MARK: show typing
let typing_users_ids = new Set();

function is_typing(e) {
    let user_id = Number(e.user_id);

    const typing_ele = document.querySelector('.typing' + e.chat_room_id);

    if (e.msg_input_value !== '') {
        typing_users_ids.add(user_id);
    } else {
        typing_users_ids.delete(user_id);
    }

    if (typing_users_ids.size !== 0) {
        typing_ele.textContent = 'typing';
    } else {
        typing_ele.textContent = '';
    }
}

  //MARK:sub chat channel
function subscribeChatChannel(chat_room_id) {
    Echo.join(`chat-room.` + chat_room_id)
        .joining((data) => {
            const plus_ele            = document.querySelector('.plus' + data.chat_room_id);
            let   chat_room_users_ids = plus_ele.getAttribute('data-chat_room_users_ids');

            chat_room_users_ids = chat_room_users_ids + ',' + data.user_id;

            plus_ele.setAttribute('data-chat_room_users_ids', chat_room_users_ids);
        })
        .listen('MessageEvent', (e) => {
            const data      = e.data;
            const sender_id = data.sender_id;
            const files     = e.files;
            
            const box   = document.querySelector('.box' + data.chat_room_id);
            const name  = document.getElementById('name' + sender_id).textContent;
            const image = document.getElementById('image' + sender_id).getAttribute('src');

            box.insertAdjacentHTML('beforeend',
                `
                    <img  class = "rounded-circle image" src = "${image}" alt = "loading">
                    <span class = "user_name">${name}</span>
                    <p    class = "user_message">${data.text}</p>
                `
            )

            typing_users_ids.delete(data.sender_id);
            if (typing_users_ids.size === 0) {
                document.querySelector(`.typing${data.chat_room_id}`).textContent='';
            } 

            files.forEach(function (file) {
                let type = file.split('-')[0];

                if (type === 'image') {
                    box.insertAdjacentHTML('beforeend',
                        `
                    <img class = "rounded-circle image" src = "/storage/images/messages/${file}" alt = "loading">
                        `
                    )
                } else if (type === 'video') {
                    box.insertAdjacentHTML('beforeend',
                        `
                        <video class = "file_sent" src = "/storage/videos/messages/${file}"></video>
                        `
                    )
                } else {
                    box.insertAdjacentHTML('beforeend',
                        `
                        <iframe class = "file_sent" src = "/storage/applications/messages/${file}"></iframe>
                        `
                    )
                }
            })

            box.scrollTo({
                top     : 10000,
                behavior: 'smooth'
            });

            document.querySelector('.chat_room_' + data.chat_room_id + ' div p .msg_text').textContent = data.text;
        }).listenForWhisper('typing', (e) => {
            is_typing(e);
        }).leaving((e) => {
            typing_users_ids.delete(e.user_id);

            const typing_ele = document.querySelector('.typing' + e.chat_room_id);

            if (typing_users_ids.size === 0) {
                typing_ele.textContent = '';
            } 
        });
}


    //MARK:get other msgs
function getNewMessages(chat_room_id) {
    const box = document.getElementsByClassName('box' + chat_room_id)[0];

    let data_status_ele = document.querySelector('.chat_room_' + chat_room_id);
    let data_status     = data_status_ele.getAttribute('data-status');

    if (data_status == 'false') {
        axios.get("/message/" + chat_room_id)
            .then(res => {
                if (res.status == 200) {
                    let view = res.data.view;

                    if (view !== '') {
                        box.insertAdjacentHTML('afterbegin', view);

                        box.scrollTo({
                            top     : 100,
                            behavior: 'smooth'
                        })
                    }

                    data_status_ele.setAttribute('data-status', 'true');
                }
            })

          //subscribe chat channel and listen to event after click
        subscribeChatChannel(chat_room_id);
    }
}

generalEventListener('click', '.user_btn', e => {
    let chat_room_id          = e.target.getAttribute('data-chat_room_id');
        selected_chat_room_id = chat_room_id;
    subscribedChatChannels.add(Number(chat_room_id));


    getNewMessages(chat_room_id);
})


let selected_chat_room_id = document.querySelector('.user_btn.active').getAttribute('data-chat_room_id');

subscribeChatChannel(selected_chat_room_id);
subscribedChatChannels.add(selected_chat_room_id)

generalEventListener('input', '.send_input', e => {
    let card         = e.target.parentElement;
    let chat_room_id = card.getAttribute('data-chat_room_id');
    let user_id      = document.getElementById('auth_id').value;

    Echo.join(`chat-room.` + chat_room_id).whisper('typing', {
        chat_room_id   : chat_room_id,
        user_id        : user_id,
        msg_input_value: document.querySelector('#msg' + chat_room_id).value
    });
})

  //MARK:send invitation
generalEventListener('click', '.plus', e => {
    let user_names_ele = document.querySelectorAll('.user_names');

    user_names_ele.forEach(user_name => {
        user_name.style.display = 'none';
    });

    let user_names          = document.querySelectorAll('.name');
    let user_images         = document.querySelectorAll('.image');
    let users_ids           = document.querySelectorAll('.plus');
    let chat_room_id        = e.target.getAttribute('data-chat_room_id');
    let chat_room_users_ids = e.target.getAttribute('data-chat_room_users_ids').split(',');

    const add_body_modal = document.querySelector('.add_body');

    for (let i = 0; i < user_names.length; i++) {
        let user_id = users_ids[i].getAttribute('data-receiver_id');

        if (!chat_room_users_ids.includes(user_id)) {
            let user_name  = user_names[i].textContent;
            let user_image = user_images[i].getAttribute('src');

            add_body_modal.insertAdjacentHTML('beforeend',
                `
                    <li class = "list-group-item user_names">

                        <img class = "rounded-circle image" alt = "loading"
                            src   = "${user_image}">
                                ${user_name}

                        <button
                            type  = "button" data-chat_room_id                 = "${chat_room_id}"
                            class = "btn btn-primary add_btn" data-receiver_id = "${user_id}">
                            add
                        </button>
                    </li>
                `);
        }
    }
})

generalEventListener('click', '.add_btn', e => {
    let receiver_id  = e.target.getAttribute('data-receiver_id');
    let chat_room_id = e.target.getAttribute('data-chat_room_id');

    axios.post(`/chat-room/send-user-invitation/${receiver_id}/${chat_room_id}`)
        .then(res => {
            if (res.status == 200) {

            }
        })
})

  //MARK:search chatrooms
function hide_results() {
    const chatroom_btn   = document.getElementsByClassName('chatroom_btn');
    const no_results_ele = document.getElementsByClassName('no_results');

    for (let i = 0; i < chatroom_btn.length; i++) {
        chatroom_btn[i].style.display = 'none';
    }

    for (let index = 0; index < no_results_ele.length; index++) {
        no_results_ele[index].style.display = 'none';
    }
}

const search_input_ele = document.querySelector('.search_input');

let search_chatrooms_arr = [];

function search_chatrooms(page) {
    let search_input_val = search_input_ele.value;

    if (search_input_val) {
        if (search_chatrooms_arr.includes(search_input_val)) {
            hide_results();

            const old_search_results_ele = document.getElementsByClassName(`search_${search_input_val}`);
            for (let i = 0; i < old_search_results_ele.length; i++) {
                old_search_results_ele[i].style.display = '';
            }

            return;
        }

        search_chatrooms_arr.unshift(search_input_val);

        axios.post('/search/chat-room?page=' + page, { 'search': search_input_val })
            .then((res) => {
                if (res.status == 200) {
                    let chat_room_view = res.data.chat_room_view;
                    let chat_box_view  = res.data.chat_box_view;

                    hide_results();

                    if (chat_room_view != '') {
                        chat_room_box.insertAdjacentHTML('beforeend', chat_room_view);
                        document.querySelector('.box_msgs').insertAdjacentHTML('beforeend', chat_box_view);
                    } else {
                        chat_room_box.insertAdjacentHTML('beforeend', `<h3 class="no_results">no matched results</h3>`);
                    }
                }
            })
    } else {
        hide_results();

        const chatroom_page_1 = document.getElementsByClassName('chatroom_page_1');
        for (let i = 0; i < chatroom_page_1.length; i++) {
            chatroom_page_1[i].style.display = '';
        }
    }
}

let chatroom_page = 1;
search_input_ele.addEventListener('input', debounce(() => {
    search_chatrooms(chatroom_page);
}, 1000))
