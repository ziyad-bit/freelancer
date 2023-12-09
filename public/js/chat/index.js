const chat_room_id = document.getElementById('chat_room_id').getAttribute('data-chat_room_id');
if (chat_room_id) {
    const scrollableDiv = document.querySelector('.list_tab_users');
    const elementToScrollTo = document.querySelector('.chat_room_' + chat_room_id);

    // Calculate the distance from the top of the scrollable div to the top of the element
    const offsetTop = elementToScrollTo.offsetTop;

    // Scroll the div to the calculated offset
    scrollableDiv.scrollTop = offsetTop;
}


let old_msg = 1;
var users_box = document.querySelector('.list_tab_users');
//load old messages
function loadOldMessages() {
    const chat_box = document.getElementsByClassName('chat_body')
    for (let i = 0; i < chat_box.length; i++) {
        chat_box[i].onscroll = function () {
            if (chat_box[i].scrollTop == 0) {
                if (old_msg == 1) {
                    let first_msg_id = this.firstElementChild.id,
                        reveiver_id = this.getAttribute('data-user_id');

                    const box = document.getElementsByClassName('box' + reveiver_id);

                    axios.put("/message/" + reveiver_id, { 'first_msg_id': first_msg_id })
                        .then(res => {
                            if (res.status == 200) {
                                let view = res.data.view;


                                for (let index = 0; index < box.length; index++) {
                                    const each_box = box[index];

                                    each_box.insertAdjacentHTML('afterbegin', view);

                                    each_box.scrollTo({
                                        top: 100,
                                        behavior: 'smooth'
                                    })
                                }


                            }
                        })
                        .catch(err => {
                            old_msg = 0;
                        })
                }
            }
        }

    }
}

loadOldMessages()

//load friends by infinite scrolling
let next_friends_page = 1;
function loadPages(page) {
    axios.post("?page=" + page)
        .then(res => {
            if (res.status == 200) {
                let users = res.data.auth_friends.data,
                    next_page = res.data.auth_friends.next_page_url;

                if (next_page == null) {
                    next_friends_page = 0;
                }

                if (users.length > 0) {
                    for (let i = 0; i < users.length; i++) {
                        const user = users[i];

                        users_box.insertAdjacentHTML('beforeend',
                            `
                                <button
                                    class=" friend_btn user_btn nav-link list-group-item list-group-item-action "
                                    id="list-home-list" data-bs-toggle="pill" data-bs-target= '#chat_box${user.id} '   role="tab"
                                    data-id="${user.id}" aria-controls="home"  data-status="0">

                                    <img class="rounded-circle image" src="/images/users/${user.photo}"    alt="loading">

                                    ${user.online == 1 ? '<div class="rounded-circle dot"></div>' : ''}

                                    <span style="font-weight: bold">${user.name}</span>
                                </button>
                                `
                        );

                        document.querySelector('.tab-content')
                            .insertAdjacentHTML('beforeend',
                                `
                            <div class="tab-pane fade  " id="chat_box${user.id}"
                                role="tabpanel" aria-labelledby="list-home-list">
                                <form id="form${user.id}"  >
            
                                    <div class="card" style="height: 300px">
                                        <h5 class="card-header">chat<span id="loading${user.id}"
                                                style="margin-left: 50px;display:none">loading old messages</span>
                                        </h5>

                                        <div class="card-body " id="box${user.id}" data-user_id="${user.id}" data-old_msg='1'>
                                            
                                        </div>
                                        <input type="hidden" name="receiver_id" class="receiver_id"
                                            value="${user.id}">
                                        <input type="text" name="message" id="msg${user.id}" class="form-control"
                                            data-id="${user.id}">
                                        
                                        <button type="button" class="btn btn-success send_btn"
                                            data-receiver_id="${user.id}">Send</button>
                                    </div>
                                </form>
                            </div>
                            `)
                    }

                    loadOldMessages()
                }
            }
        })
}

function storeMsg(e) {
    e.preventDefault();

    let receiver_id = e.target.getAttribute('data-receiver_id'),
        message = document.getElementsByClassName('msg' + receiver_id)[0].value;

    if (message) {
        message = document.getElementsByClassName('msg' + receiver_id)[0].value;
    } else {
        message = document.getElementsByClassName('msg' + receiver_id)[1].value;
    }

    axios.post('/message', { 'text': message, 'receiver_id': receiver_id })
        .then(res => {
            if (res.status == 200) {
                let auth_name = document.getElementById('auth_name').value,
                    auth_photo = document.getElementById('auth_photo').value;

                const box = document.getElementsByClassName('box' + receiver_id),
                    msg_err = document.getElementsByClassName(`msg_err${receiver_id}`)[0];

                if (msg_err) {
                    msg_err.remove();
                }

                document.getElementById('msg' + receiver_id).value = '';

                for (let i = 0; i < box.length; i++) {
                    let each_box = box[i];

                    each_box.insertAdjacentHTML('beforeend',
                        `
                        <img class="rounded-circle image" src="/images/users/${auth_photo}" alt="loading">
                            <span class="user_name">${auth_name}</span>
                            <p class="user_message">${message}</p>
                            `
                    )

                    each_box.scrollTo({
                        top: 10000,
                        behavior: 'smooth'
                    })
                }

            }
        })
}

//store message
generalEventListener('click', '.send_btn', e => {
    storeMsg(e);
})

generalEventListener('keypress', '.send_input', e => {
    if (e.keyCode == 13) {
        storeMsg(e);
    }
})

//get messages for users
function getNewMessages(receiver_id) {
    const box = document.getElementsByClassName('box' + receiver_id),
        data_status_ele = document.querySelector(`[data-id="${receiver_id}"]`);

    let data_status = data_status_ele.getAttribute('data-status');
    if (data_status == '0') {
        axios.get("/message/" + receiver_id)
            .then(res => {
                if (res.status == 200) {
                    let view = res.data.view;

                    for (let index = 0; index < box.length; index++) {
                        box[index].insertAdjacentHTML('afterbegin', view);

                        box[index].scrollTo({
                            top: 10000,
                            behavior: 'smooth'
                        });
                    }

                    data_status_ele.setAttribute('data-status', '1');
                }
            }).catch(err => {
                let error = err.response.data.error;

                for (let i = 0; i < box.length; i++) {
                    box[i].insertAdjacentHTML('beforeend',
                        `
                        <h3 class="msg_err${receiver_id}">${error}</h3>
                    `
                    );
                }

                data_status_ele.setAttribute('data-status', '1');
            });
    }
}

//get messages for other users
generalEventListener('click', '.user_btn', e => {
    let id = e.target.getAttribute('data-id');
    let data_status = document.querySelector(`[data-id="${id}"]`).getAttribute('data-status');

    if (data_status == '0') {
        getNewMessages(id);
    }
})



//subscribe chat channel and listen to event
/*  let auth_id = document.getElementById('auth_id').value;
 Echo.private(`chat.${auth_id}`)
     .listen('MessageSend', (e) => {
         const box = document.getElementById('box' + e.sender_id);
 
         box.insertAdjacentHTML('beforeend',
             `
             <img  class="rounded-circle image" src="/images/users/${e.user_photo}" alt="loading">
             <span class="user_name">${e.user_name}</span>
             <p class="user_message">${e.text}</p>
             `
         )
 
         box.scrollTo({
             top: 10000,
             left: 0,
             behavior: 'smooth'
         })
     }); */


//search friends
function hide_results() {
    const friend_btn = document.getElementsByClassName('friend_btn'),
        no_results_ele = document.getElementsByClassName('no_results');

    for (let i = 0; i < friend_btn.length; i++) {
        friend_btn[i].style.display = 'none';
    }

    for (let index = 0; index < no_results_ele.length; index++) {
        no_results_ele[index].style.display = 'none';
    }
}


const search_input_ele = document.querySelector('.search_friends');

let search_friends_arr = [],
    pages_friends_status = true,
    search_friends_status = false;

function load_search_pages(page, search_input_val) {
    axios.post('/message/search-friends?page=' + page, { 'search': search_input_val })
        .then((res) => {
            if (res.status == 200) {
                let friends_view = res.data.friends_view,
                    friends_tab_view = res.data.friends_tab_view;

                if (friends_view != '') {
                    users_box.insertAdjacentHTML('beforeend', friends_view);
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
                    let friends_view = res.data.friends_view,
                        friends_tab_view = res.data.friends_tab_view;

                    hide_results();

                    if (friends_view != '') {
                        users_box.insertAdjacentHTML('beforeend', friends_view);
                        document.querySelector('.box_msgs').insertAdjacentHTML('beforeend', friends_tab_view);
                    } else {
                        users_box.insertAdjacentHTML('beforeend', `<h3 class="no_results">no matched results</h3>`);
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

let page = 1;
users_box.onscroll = function () {
    if (users_box.offsetHeight == users_box.scrollHeight - users_box.scrollTop) {
        if (next_friends_page == 1 && search_friends_status == false) {
            page++;
            loadPages(page);
        }


        if (search_friends_status == true && pages_friends_status == true) {
            let search_input_val = search_input_ele.value;

            page_friends++;
            load_search_pages(page_friends, search_input_val);
        }
    }
}


//search last messages
const search_friends_chat = document.querySelector('.search_friends_chat');
let search_last_msgs_arr = [];

function hide_results_last_msgs() {
    const friend_btn = document.getElementsByClassName('users_chat'),
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
                    let last_msgs_view = res.data.last_msgs_view,
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







