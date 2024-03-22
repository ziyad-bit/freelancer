const chat_room_id = document.querySelector('.user_btn').getAttribute('data-selected_chat_room_id');

if (chat_room_id) {
    const scrollableDiv     = document.querySelector('.list_tab_users');
    const elementToScrollTo = document.querySelector('.chat_room_' + chat_room_id);

      // Calculate the distance from the top of the scrollable div to the top of the element
    const offsetTop = elementToScrollTo.offsetTop;

      // Scroll the div to the calculated offset
    scrollableDiv.scrollTop = offsetTop;
}

  //load old messages
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


  //load chat_rooms by infinite scrolling
const chat_room_box = document.querySelector('.list_tab_users');
let   data_status   = true;

function loadPages() {
    let message_id = chat_room_box.lastElementChild.getAttribute('data-message_id');

    if (data_status) {
        console.log('data_status: ', data_status);
        axios.get("/message/chat-rooms/" + message_id)
            .then(res => {
                if (res.status == 200) {
                    let chat_room_view = res.data.chat_room_view,
                        chat_box_view  = res.data.chat_box_view;

                    if (chat_room_view !== '') {
                        chat_room_box.insertAdjacentHTML('beforeend', chat_room_view);

                        document.querySelector('.box_msgs')
                            .insertAdjacentHTML('beforeend', chat_box_view)

                        loadOldMessages()
                    } else {
                        data_status = false;
                    }
                }
            })
    }
}

  //store message
function storeMsg(e) {
    e.preventDefault();

    let chat_room_id = e.target.getAttribute('data-chat_room_id'),
        form         = document.querySelector('#form' + chat_room_id),
        formData     = new FormData(form);

    const msg_err = document.getElementsByClassName(`msg_err${chat_room_id}`)[0];

    axios.post('/message', formData)
        .then(res => {
            if (res.status == 200) {
                let auth_name  = document.getElementById('auth_name').value,
                    auth_photo = document.getElementById('auth_photo').value,
                    message    = document.getElementById(`msg${chat_room_id}`).value;

                const box = document.getElementsByClassName('box' + chat_room_id)[0];

                msg_err.textContent = '';

                document.getElementById('msg' + chat_room_id).value = '';

                box.insertAdjacentHTML('beforeend',
                    `
                <img  class = "rounded-circle image" src = "/storage/images/users/${auth_photo}" alt = "loading">
                <span class = "user_name">${auth_name}</span>
                <p    class = "user_message">${message}</p>
                    `
                )

                box.scrollTo({
                    top     : 10000,
                    behavior: 'smooth'
                })

                document.querySelector('.chat_room_' + chat_room_id + ' div p .msg_text').textContent = message;
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

generalEventListener('click', '.send_btn', e => {
    storeMsg(e);
})

//subscribe chat channel and listen to event
function subscribeChannel(chat_room_id) {
    Echo.join(`chat-room.` + chat_room_id)
            .listen('MessageEvent', (e) => {
                const data      = e.data;
                const sender_id = data.sender_id;
                const box       = document.querySelector('.box' + data.chat_room_id);
                const name      = document.getElementById('name' + sender_id).textContent;
                const image     = document.getElementById('image' + sender_id).getAttribute('src');

                box.insertAdjacentHTML('beforeend',
                    `
                    <img  class = "rounded-circle image" src = "${image}" alt = "loading">
                    <span class = "user_name">${name}</span>
                    <p    class = "user_message">${data.text}</p>
                `
                )

                box.scrollTo({
                    top     : 10000,
                    behavior: 'smooth'
                });

                document.querySelector('.chat_room_' + data.chat_room_id + ' div p .msg_text').textContent = data.text;
            });
    }

//get messages for chat_rooms
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
        subscribeChannel(chat_room_id);
    }
}

generalEventListener('click', '.user_btn', e => {
    let chat_room_id = e.target.getAttribute('data-chat_room_id');

    getNewMessages(chat_room_id);
})

  //subscribe chat channel and listen to event 
let selected_chat_room_id = document.querySelector('.user_btn.active').getAttribute('data-chat_room_id');

subscribeChannel(selected_chat_room_id);

generalEventListener('input', '.send_input', e => {
    let chat_room_id = e.target.getAttribute('data-chat_room_id');

    Echo.join(`chat-room.` + chat_room_id).whisper('typing', {
        chat_room_id   : chat_room_id,
        msg_input_value: document.querySelector('#msg' + chat_room_id).value
    });
})

Echo.join(`chat-room.` + selected_chat_room_id).listenForWhisper('typing', (e) => {
    const typing_ele = document.querySelector('.typing' + e.chat_room_id);

    if (e.msg_input_value !== '') {
        typing_ele.textContent = 'typing';
    }else{
        typing_ele.textContent = '';
    }
});


  //search friends
function hide_results() {
    const friend_btn     = document.getElementsByClassName('friend_btn'),
          no_results_ele = document.getElementsByClassName('no_results');

    for (let i = 0; i < friend_btn.length; i++) {
        friend_btn[i].style.display = 'none';
    }

    for (let index = 0; index < no_results_ele.length; index++) {
        no_results_ele[index].style.display = 'none';
    }
}


const search_input_ele = document.querySelector('.search_friends');

let search_friends_arr    = [],
    pages_friends_status  = true,
    search_friends_status = false;

function load_search_pages(page, search_input_val) {
    axios.post('/message/search-friends?page=' + page, { 'search': search_input_val })
        .then((res) => {
            if (res.status == 200) {
                let friends_view     = res.data.friends_view,
                    friends_tab_view = res.data.friends_tab_view;

                if (friends_view != '') {
                    chat_room_box.insertAdjacentHTML('beforeend', friends_view);
                    document.querySelector('.box_msgs').insertAdjacentHTML('beforeend', friends_tab_view);
                } else {
                    pages_friends_status = false;
                }
            }
        })
}


function search_friends(page) {
    let search_input_val = search_input_ele.value;

    if (search_input_val) {
        if (search_friends_arr.includes(search_input_val)) {
            hide_results();

            const old_search_results_ele = document.getElementsByClassName(`${search_input_val}`);
            for (let i = 0; i < old_search_results_ele.length; i++) {
                old_search_results_ele[i].style.display = '';
            }

            return;
        }

        search_friends_arr.unshift(search_input_val);

        axios.post('/message/search-friends?page=' + page, { 'search': search_input_val })
            .then((res) => {
                if (res.status == 200) {
                    let friends_view     = res.data.friends_view,
                        friends_tab_view = res.data.friends_tab_view;

                    hide_results();

                    if (friends_view != '') {
                        chat_room_box.insertAdjacentHTML('beforeend', friends_view);
                        document.querySelector('.box_msgs').insertAdjacentHTML('beforeend', friends_tab_view);
                    } else {
                        chat_room_box.insertAdjacentHTML('beforeend', `<h3 class="no_results">no matched results</h3>`);
                    }
                }
            })
    } else {
        search_friends_status = false
        hide_results();

        const friends_1_page = document.getElementsByClassName('friends_1_page');
        for (let i = 0; i < friends_1_page.length; i++) {
            friends_1_page[i].style.display = '';
        }
    }
}


let page_friends = 1;
search_input_ele.addEventListener('input', debounce(() => {
    search_friends_status = true;
    search_friends(page_friends);
}, 1000)
)

let page                   = 1;
    chat_room_box.onscroll = function () {
    if (chat_room_box.offsetHeight == chat_room_box.scrollHeight - chat_room_box.scrollTop) {

        loadPages();



        if (search_friends_status == true && pages_friends_status == true) {
            let search_input_val = search_input_ele.value;

            page_friends++;
            load_search_pages(page_friends, search_input_val);
        }
    }
}


  //search last messages
const search_friends_chat  = document.querySelector('.search_friends_chat');
let   search_last_msgs_arr = [];

function hide_results_last_msgs() {
    const friend_btn     = document.getElementsByClassName('users_chat'),
          no_results_ele = document.getElementsByClassName('no_results_last_msgs');

    for (let i = 0; i < friend_btn.length; i++) {
        friend_btn[i].style.display = 'none';
    }

    for (let index = 0; index < no_results_ele.length; index++) {
        no_results_ele[index].style.display = 'none';
    }
}

function search_last_msgs(page) {
    let search_input_val = search_friends_chat.value;

    if (search_input_val) {
        if (search_last_msgs_arr.includes(search_input_val)) {
            hide_results_last_msgs();

            const old_search_results_ele = document.getElementsByClassName(`${"search" + search_input_val}`);
            for (let i = 0; i < old_search_results_ele.length; i++) {
                old_search_results_ele[i].style.display = '';
            }

            return;
        }

        search_last_msgs_arr.unshift(search_input_val);

        axios.post('/message/search-last-msgs?page=' + page, { 'search': search_input_val })
            .then((res) => {
                if (res.status == 200) {
                    let last_msgs_view     = res.data.last_msgs_view,
                        last_msgs_tab_view = res.data.last_msgs_tab_view;

                    hide_results_last_msgs();

                    const chat_tab = document.querySelector('.chat_tab_users')

                    if (last_msgs_view != '') {
                        chat_tab.insertAdjacentHTML('beforeend', last_msgs_view);
                        document.querySelector('.chat_box_body').insertAdjacentHTML('beforeend', last_msgs_tab_view);
                    } else {
                        chat_tab.insertAdjacentHTML('beforeend', `<h3 class="no_results_last_msgs">no matched results</h3>`);
                    }
                }
            })
    } else {
        search_friends_status = false
        hide_results_last_msgs();

        const friends_1_page = document.getElementsByClassName('last_msgs_1_page');
        for (let i = 0; i < friends_1_page.length; i++) {
            friends_1_page[i].style.display = '';
        }
    }
}







