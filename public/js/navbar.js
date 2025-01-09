/* eslint-disable no-undef */
const search_ele        = document.getElementById('search');
const list_search_item  = document.getElementsByClassName('search_item');
const list_search_group = document.querySelector('.navbar_list_search');

let recent_req     = 0,
    search_req_num = 0;

  //hide search results or notifications on document click
document.onclick=function(e){
    if(e.target.id != 'search'){
        for (let i = 0; i < list_search_item.length; i++) {
            list_search_item[i].style.display = 'none';
        }
    }

    if(e.target.id != 'bell'){
        notif_ele.style.display = 'none';
    }
}

  //submit search form on click
function submit_search(e){
    let text = e.target.innerText;
    
    search_ele.value = text;

    document.getElementById('search_form').submit();
}

generalEventListener('click','.search_item',e=>{
    submit_search(e);
});

generalEventListener('click','.search_name',e=>{
    submit_search(e);
});

  //show recent searches
let recent_search_url = document.querySelector('#recent_search_url').value;
function show_recent_searches(){
    if (recent_req == 0) {
        axios.get(recent_search_url)
            .then(res=>{
                if (res.status == 200) {
                    if (search_req_num == 1) {
                        for (let i = 0; i < list_search_item.length; i++) {
                            list_search_item[i].style.display = 'none';
                        }
                    }

                    search_req_num = 1;
                    recent_req     = 1;

                    let view = res.data.view;
                    list_search_group.insertAdjacentHTML('afterbegin',view);
                }
                
            });
        }else{
            if (search_req_num == 1) {
                for (let i = 0; i < list_search_item.length; i++) {
                    list_search_item[i].style.display = 'none';
                }
            }

            const recent_searches_ele = document.getElementsByClassName('recent_search');
            
            for (let i = 0; i < recent_searches_ele.length; i++) {
                recent_searches_ele[i].style.display = '';
            }
    }
}

  //show matched search results under input
let search_words = [];
let search_url   = document.querySelector('#search_url').value;

function showMatchedSearch() {
    let search = search_ele.value;
    if (search != '') {
        if (search_words.includes(search)) {
            for (let i = 0; i < list_search_item.length; i++) {
                list_search_item[i].style.display = 'none';
            }

            let search_key_ele = document.getElementsByClassName(`${search}`);
            for (let i = 0; i < search_key_ele.length; i++) {
                search_key_ele[i].style.display = '';
            }

            return
        }

        search_words.unshift(search);

        axios.post(search_url, { 'search': search })
            .then(res=> {
                if (res.status == 200) {
                    let view = res.data.view;

                    if (search_req_num == 1) {
                        for (let i = 0; i < list_search_item.length; i++) {
                            list_search_item[i].style.display = 'none';
                        }
                    }

                    search_req_num = 1;

                    list_search_group.insertAdjacentHTML('beforeend',view);
                }
                
            });
    }else{
        show_recent_searches();
    }
}

search_ele.addEventListener('input',debounce(()=>{
        showMatchedSearch()
    })
)

  //show recent searches
search_ele.onfocus=function(){
    let search = search_ele.value;
    if (search == '') {
        show_recent_searches();
    }
}

  //subscribe notification channel and listen to event
const notif_ele        = document.querySelector('.notif');
const notifs_count_ele = document.querySelector('#notifs_count');

let auth_id      = document.querySelector('#auth_id').value;
let notifs_count = Number(notifs_count_ele.textContent);

Echo.private(`App.Models.User.${auth_id}`)
    .notification((notification) => {
        notif_ele.insertAdjacentHTML('afterbegin',notification.view);

        notifs_count++;

        notifs_count_ele.innerText     = notifs_count;
        notifs_count_ele.style.display = '';
    });


  //show notifications and mark them as read
const bell_ele = document.querySelector('.fa-bell');

bell_ele.onclick=()=>{
    notif_ele.style.display = '';

    let url = document.querySelector('.wrapper_notifs').getAttribute('data-update_url');

    if (notifs_count > 0) {
        axios.put(url )
            .then(res=>{
                if (res.status == 200) {
                    notifs_count_ele.style.display = 'none';
                    notifs_count                   = 0;
                }
            });
    }
}

if (notifs_count != 0) {
    notifs_count_ele.style.display = '';
}

  //infinite scroll for notifications
let notif_req = true;

notif_ele.onscroll=()=>{
    if (notif_ele.offsetHeight-2 == notif_ele.scrollHeight - notif_ele.scrollTop && notif_req == true) {
        let show_notifs_url = document.querySelector('.notif').lastElementChild.getAttribute('data-show_old_url');

        axios.get(show_notifs_url)
            .then(res=>{
                if (res.status == 200) {
                    let view = res.data.view;

                    if (view !== '') {
                        notif_ele.insertAdjacentHTML('beforeend',view);
                    }else{
                        notif_req = false;
                    }
                }
            });
    }
}

//refuse invitation
generalEventListener('click', '.refuse_btn', e => {
    let chat_room_id = e.target.parentElement.getAttribute('data-chat_room_id');
    let refuse_url   = e.target.getAttribute('data-refuse_url');

    axios.post(refuse_url,{'chat_room_id':chat_room_id})
        .then(res=>{
            if (res.status == 200) {
                document.querySelector(`.notif_${chat_room_id}`).remove();
            }
        });
})

//accept invitation
generalEventListener('click', '.accept_btn', () => {
    const accept_form = document.querySelector('#accept_form');
    const csrf_token_ele = document.querySelector('[name="_token"]');

    accept_form.appendChild(csrf_token_ele);
    accept_form.submit();
})

