(()=>{function t(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=n){var o,a,r,c,i=[],u=!0,s=!1;try{if(r=(n=n.call(e)).next,0===t){if(Object(n)!==n)return;u=!1}else for(;!(u=(o=r.call(n)).done)&&(i.push(o.value),i.length!==t);u=!0);}catch(e){s=!0,a=e}finally{try{if(!u&&null!=n.return&&(c=n.return(),Object(c)!==c))return}finally{if(s)throw a}}return i}}(e,t)||function(e,t){if(e){if("string"==typeof e)return n(e,t);var o={}.toString.call(e).slice(8,-1);return"Object"===o&&e.constructor&&(o=e.constructor.name),"Map"===o||"Set"===o?Array.from(e):"Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o)?n(e,t):void 0}}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function n(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,o=Array(t);n<t;n++)o[n]=e[n];return o}var o=document.querySelector("#chat_room_id").getAttribute("data-chat_room_id"),a=new Set;if(window.onbeforeunload=function(){a.forEach((function(e){Echo.leaveChannel("chatrooms.".concat(e))}))},o){var r=document.querySelector(".list_tab_users"),c=document.querySelector(".chat_room_"+o).offsetTop;r.scrollTop=c}var i=!0;function u(){for(var e=document.getElementsByClassName("chat_body"),t=function(t){e[t].scrollTo({top:1e3,behavior:"smooth"}),e[t].onscroll=function(){if(0==e[t].scrollTop&&1==i){var n=this.getAttribute("data-chat_room_id"),o=this.firstElementChild.getAttribute("data-show_old_msgs_url"),a=document.getElementsByClassName("box"+n)[0];axios.get(o).then((function(e){if(200==e.status){var t=e.data.view;""!==t?(a.insertAdjacentHTML("afterbegin",t),a.scrollTo({top:100,behavior:"smooth"})):i=!1}}))}}},n=0;n<e.length;n++)t(n)}u();var s=document.querySelector(".list_tab_users"),l=!0;function d(e){e.preventDefault();var n=e.target.parentElement.getAttribute("data-chat_room_id"),o=e.target.getAttribute("data-store_msg_url"),a=document.querySelector("#form"+n),r=new FormData(a),c=document.getElementsByClassName("msg_err".concat(n))[0];axios.post(o,r).then((function(e){if(200==e.status){var t=e.data.view,o=e.data.text,a=document.getElementsByClassName("box".concat(n))[0];a.insertAdjacentHTML("beforeend",t),c.textContent="",m=0,document.getElementById("msg".concat(n)).value="",a.scrollTo({top:1e4,behavior:"smooth"}),document.querySelectorAll(".input_files").forEach((function(e){e.remove()})),document.querySelectorAll(".files_container".concat(n," .file_uploaded")).forEach((function(e){e.remove()})),document.querySelector(".files_container".concat(n)).style.display="none";var r=document.querySelector(".chat_room_".concat(n," div p .msg_text")),i=document.querySelector(".chat_room_".concat(n," div p #sender_name"));r.textContent=null==o?"file":o,i.textContent="you :"}})).catch((function(e){var n=e.response;if(422==n.status)for(var o=n.data.errors,a=0,r=Object.entries(o);a<r.length;a++){var i=t(r[a],2),u=(i[0],i[1]);c.textContent=u[0],c.style.display=""}}))}s.onscroll=function(){var e;s.offsetHeight==s.scrollHeight-s.scrollTop&&(e=s.lastElementChild.getAttribute("data-show_more_chatroom_url"),l&&axios.get(e).then((function(e){if(200==e.status){var t=e.data.chat_room_view,n=e.data.chat_box_view;""!==t?(s.insertAdjacentHTML("beforeend",t),document.querySelector(".box_msgs").insertAdjacentHTML("beforeend",n),u()):l=!1}})))};var m=0;function f(n,o,a,r){var c=document.querySelector("#upload_url").value;document.querySelector("#app_input".concat(r)).value="",document.querySelector("#video_input".concat(r)).value="",document.querySelector("#image_input".concat(r)).value="",axios.post(c,a).then((function(e){if(200==e.status){var t=e.data.file_name;if(m++,"app"===o)var a='<iframe class="file_uploaded" src="'.concat(n+t,'"></iframe>'),c='<input type="hidden" class="input_files" name="files['.concat(m,'][name]" value="').concat(t,'">\n                        <input type="hidden" class="input_files" name="files[').concat(m,'][type]" value="application">');else if("image"===o)a='<img class="file_uploaded" src="'.concat(n+t,'"></img>'),c='<input type="hidden" class="input_files" name="files['.concat(m,'][name]" value="').concat(t,'">\n                        <input type="hidden" class="input_files" name="files[').concat(m,'][type]" value="image">');else a='<video class="file_uploaded" src="'.concat(n+t,'"></video>'),c='<input type="hidden" class="input_files" name="files['.concat(m,'][name]" value="').concat(t,'">\n                        <input type="hidden" class="input_files" name="files[').concat(m,'][type]" value="video">');document.querySelector("#form".concat(r)).insertAdjacentHTML("afterbegin",c),document.querySelector(".files_container".concat(r," .body_container")).insertAdjacentHTML("afterbegin",a),document.querySelector(".files_container".concat(r)).style.display=""}})).catch((function(n){var o=n.response;if(422==o.status)for(var a=o.data.errors,r=e.target.getAttribute("data-chat_room_id"),c=document.querySelector(".msg_err".concat(r)),i=0,u=Object.entries(a);i<u.length;i++){var s=t(u[i],2),l=(s[0],s[1]);c.textContent=l[0],c.style.display=""}}))}generalEventListener("input",".file_input",(function(e){var t=e.target.getAttribute("data-chat_room_id"),n=document.querySelector("#app_input".concat(t)),o=document.querySelector("#form_upload_app".concat(t)),a=new FormData(o);if(n.value){f("/storage/applications/messages/","app",a,t)}})),generalEventListener("input",".file_input",(function(e){var t=e.target.getAttribute("data-chat_room_id"),n=document.querySelector("#image_input".concat(t)),o=document.querySelector("#form_upload_image".concat(t)),a=new FormData(o);if(n.value){f("/storage/images/messages/","image",a,t)}})),generalEventListener("input",".file_input",(function(e){var t=e.target.getAttribute("data-chat_room_id"),n=document.querySelector("#video_input".concat(t)),o=document.querySelector("#form_upload_video".concat(t)),a=new FormData(o);if(n.value){f("/storage/videos/messages/","video",a,t)}})),generalEventListener("click",".send_btn",(function(e){d(e)})),generalEventListener("keypress",".send_input",(function(e){13!=e.keyCode||e.shiftKey||d(e)}));var _=new Set;function v(e){Echo.join("chatrooms."+e).joining((function(e){var t=document.querySelector(".plus"+e.chat_room_id),n=t.getAttribute("data-chat_room_users_ids");n=n+","+e.user_id,t.setAttribute("data-chat_room_users_ids",n)})).listen("MessageEvent",(function(e){var t=e.data,n=t.chat_room_id,o=document.querySelector(".box".concat(n));o.insertAdjacentHTML("beforeend",e.view),_.delete(t.sender_id),0===_.size&&(document.querySelector(".typing".concat(n)).textContent=""),o.scrollTo({top:1e4,behavior:"smooth"}),document.querySelector(".chat_room_".concat(n," div  #sender_name")).textContent=e.sender_name+":";var a=document.querySelector(".chat_room_".concat(n," div p .msg_text")),r=t.text;a.textContent=null==r?"file":r})).listenForWhisper("typing",(function(e){!function(e){var t=Number(e.user_id),n=document.querySelector(".typing"+e.chat_room_id);""!==e.msg_input_value?_.add(t):_.delete(t),0!==_.size?n.textContent="typing":n.textContent=""}(e)})).leaving((function(e){_.delete(e.user_id);var t=document.querySelector(".typing"+e.chat_room_id);0===_.size&&(t.textContent="")}))}generalEventListener("click",".user_btn",(function(e){var t=e.target.getAttribute("data-chat_room_id"),n=e.target.getAttribute("data-show_msgs_url");p=t,a.add(Number(t)),function(e,t){var n=document.getElementsByClassName("box"+e)[0],o=document.querySelector(".chat_room_"+e);"false"==o.getAttribute("data-status")&&(axios.get(t).then((function(e){if(200==e.status){var t=e.data.view;""!==t&&(n.insertAdjacentHTML("afterbegin",t),n.scrollTo({top:100,behavior:"smooth"})),o.setAttribute("data-status","true")}})),v(e))}(t,n)}));var p=document.querySelector(".user_btn.active").getAttribute("data-chat_room_id");function g(){for(var e=document.getElementsByClassName("chatroom_btn"),t=document.getElementsByClassName("no_results"),n=0;n<e.length;n++)e[n].style.display="none";for(var o=0;o<t.length;o++)t[o].style.display="none"}v(p),a.add(p),generalEventListener("input",".send_input",(function(e){var t=e.target.parentElement.getAttribute("data-chat_room_id"),n=document.getElementById("auth_id").value;Echo.join("chatrooms."+t).whisper("typing",{chat_room_id:t,user_id:n,msg_input_value:document.querySelector("#msg"+t).value})}));var h=document.querySelector(".search_input"),y=[];h.addEventListener("input",debounce((function(){!function(){var e=h.value,t=h.getAttribute("data-search_url");if(e){if(y.includes(e)){g();for(var n=document.getElementsByClassName("search_".concat(e)),o=0;o<n.length;o++)n[o].style.display="";return}y.unshift(e),axios.post(t,{search:e}).then((function(e){if(200==e.status){var t=e.data.chat_room_view,n=e.data.chat_box_view;g(),""!=t?(s.insertAdjacentHTML("beforeend",t),document.querySelector(".box_msgs").insertAdjacentHTML("beforeend",n)):s.insertAdjacentHTML("beforeend",'<h3 class="no_results">no matched results</h3>')}}))}else{g();for(var a=document.getElementsByClassName("chatroom_page_1"),r=0;r<a.length;r++)a[r].style.display=""}}()}),1e3))})();