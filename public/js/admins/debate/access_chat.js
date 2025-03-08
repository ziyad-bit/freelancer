//MARK:get Old msgs
let old_msg = true;

function loadOldMessages() {
    const chat_box = document.querySelector('.chat_body');

        chat_box.onscroll = function () {
            if (chat_box.scrollTop == 0) {
                if (old_msg == true) {
                    let message_id = this.firstElementChild.getAttribute('id');
                    let url = chat_box.getAttribute('data-url') + '/'+ message_id;
                    
                    // eslint-disable-next-line no-undef
                    axios.get(url)
                        .then(res => {
                            if (res.status == 200) {
                                let view = res.data.messages;

                                if (view !== '') {
                                    chat_box.insertAdjacentHTML('afterbegin', view);

                                    chat_box.scrollTo({
                                        top: 100,
                                        behavior: 'smooth'
                                    })
                                } else {
                                    old_msg = false;
                                }
                            }
                        })
                }
            }
        }
}

loadOldMessages()