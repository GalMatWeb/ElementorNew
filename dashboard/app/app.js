import { Connect } from './connect.js';
import { AppDispatcher } from './appdispatcher.js';
import { http } from './http.js';

export const App = (function(){

    let screenList = {
        login: {
            selector: '.log-in',
            status: 'on',
            onload: function () {
                loginHandler();
            }
        },
        dashboard: {
            selector: '.dashboard',
            status: 'off',
            onload: function () {
                window.setInterval(loadList , 3000);
            }
        }
    };

    let usersList = {};

    let currScreen = 'login';

    let showList = function(response){

        const users = response.users;
        if(usersList != users) {
            usersList = users;
            const tableBody = document.querySelector(screenList.dashboard.selector  + " table tbody");
            tableBody.innerHTML = '';
            let line ='';
            for(const [id,user] of Object.entries(users)) {
                line +=  '<tr><td>'+user.name+'</td><td>'+user.entranceTime+'</td><td>'+user.lastUpdate+'</td><td>'+user.userIp+'</td></tr>';
            }
            tableBody.innerHTML = line;
        }



    }

    let loadList = function(){
        http.httpGet("?act=list", showList);

    }

    let loginResponse = function(response){
        let res = response;
        if(res.status == 'ok') {
            Connect.userID = res.userID;
            Connect.name = res.name;
            switchScreen("dashboard");
            return;
        }
        alert(res.message);


    };

    let loginHandler = function(){
        const self = this;
        const loginForm = document.getElementById("login-form");
        loginForm.addEventListener("submit",function (event) {
            event.preventDefault();
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            //TODO validate inputs
            let obj = {
                name: name,
                email: email
            };

            http.httpPost("?act=login",obj, loginResponse );
        });

    };

    let showScreen = function(){
        for (const [key, value] of Object.entries(screenList)) {
            let el = document.querySelector(value.selector);
            if(el) {
                if(value.status=='on') {
                    el.classList.add("show");
                    value.onload();
                }
                else {
                    el.classList.remove("show");
                }

            }
        }

    };

    let switchScreen = function (screenToShow) {
        screenList[currScreen].status = 'off';
        screenList[screenToShow].status = 'on';
        currScreen = screenToShow;
        showScreen();
    }

    let isLoggedIn = function () {
        const userID = Connect.userID;
        if(userID) {
            //check Token with server
            return true;
        }
        return false;
    };

    let logout = function(event){
        event.preventDefault();
        Connect.userID = null;
        Connect.name = null;
        http.httpGet('?act=logout');
    };


    let init = function () {
        let self = this;
        window.addEventListener( 'unload', function( event ) {
            http.httpGet('?act=logout');
        });
        if(!self.isLoggedIn()) {
            self.switchScreen('login');
            return;
        }
        self.switchScreen('dashboard');
        //TODO full Screen handler

    };

    return {
        init: init,
        isLoggedIn: isLoggedIn,
        switchScreen: switchScreen,
        currScreen: currScreen,
        screenList: screenList,
        showScreen: showScreen,
        logout: logout
    }

})();

