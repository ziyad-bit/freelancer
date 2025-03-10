/* eslint-disable no-undef */
const mix = require('laravel-mix');
const fs = require('fs');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

//MARK:chat js
const chatSrc = 'public/js/chat/';
const chatOutput= 'public/js/minify/chat/';

fs.readdirSync(chatSrc).forEach(file => {
    if (file.endsWith('.js')) {
        mix.js(`${chatSrc}${file}`, chatOutput).minify(`${chatOutput}${file}`);
    }
});

//MARK:profile js
const profileSrc = 'public/js/profile/';
const profileOutput = 'public/js/minify/profile/';

fs.readdirSync(profileSrc).forEach(file => {
    if (file.endsWith('.js')) {
        mix.js(`${profileSrc}${file}`, profileOutput).minify(`${profileOutput}${file}`);
    }
});

//MARK:project js
const projectSrc = 'public/js/project/';
const projectOutput = 'public/js/minify/project/';

fs.readdirSync(projectSrc).forEach(file => {
    if (file.endsWith('.js')) {
        mix.js(`${projectSrc}${file}`, projectOutput).minify(`${projectOutput}${file}`);
    }
});

//MARK:transaction js
const transactionSrc = 'public/js/transaction/';
const transactionOutput = 'public/js/minify/transaction/';

fs.readdirSync(transactionSrc).forEach(file => {
    if (file.endsWith('.js')) {
        mix.js(`${transactionSrc}${file}`, transactionOutput).minify(`${transactionOutput}${file}`);
    }
});

//MARK:skill js
const skillSrc = 'public/js/skill/';
const skillOutput = 'public/js/minify/skill/';

fs.readdirSync(skillSrc).forEach(file => {
    if (file.endsWith('.js')) {
        mix.js(`${skillSrc}${file}`, skillOutput).minify(`${skillOutput}${file}`);
    }
});

//MARK:general js
const generalSrc = 'public/js/general.js';
const generalOutput = 'public/js/minify/general/general.js';

mix.js(`${generalSrc}`, generalOutput).minify(`${generalOutput}`);

//MARK:navbar js
const navbarSrc = 'public/js/navbar.js';
const navbarOutput = 'public/js/minify/navbar/navbar.js';

mix.js(`${navbarSrc}`, navbarOutput).minify(`${navbarOutput}`);

//MARK:admin debate 
const debateSrc = 'public/js/admins/debate/access_chat.js';
const debateOutput = 'public/js/minify/admins/debate/access_chat.js';

mix.js(`${debateSrc}`, debateOutput).minify(`${debateOutput}`);


//MARK:chat css  
const chatSrcCss = 'public/css/users/chat/index.css';
const chatOutputCSs = 'public/css/minify/users/chat/index.css';

mix.styles(`${chatSrcCss}`, chatOutputCSs).minify(`${chatOutputCSs}`);

//MARK:profile css  
const profileSrcCss = 'public/css/users/profile/index.css';
const profileOutputCSs = 'public/css/minify/users/profile/index.css';

mix.styles(`${profileSrcCss}`, profileOutputCSs).minify(`${profileOutputCSs}`);

//MARK:project css  
const projectSrcCss = 'public/css/users/project/index.css';
const projectOutputCSs = 'public/css/minify/users/project/index.css';

mix.styles(`${projectSrcCss}`, projectOutputCSs).minify(`${projectOutputCSs}`);

//MARK:navbar css  
const navbarSrcCss = 'public/css/navbar.css';
const navbarOutputCSs = 'public/css/minify/navbar.css';

mix.styles(`${navbarSrcCss}`, navbarOutputCSs).minify(`${navbarOutputCSs}`);

mix.version(); // Cache busting

mix.disableNotifications();



