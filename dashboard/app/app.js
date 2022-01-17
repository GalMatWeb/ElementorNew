import { Connect } from './connect.js';
import { AppDispatcher } from './appdispatcher.js';
import { http } from './http.js';

export const App = (function(){
    let timers = [];
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
                timers['list'] = window.setInterval(loadList , 3000);
            }
        }
    };

    let usersList = {};

    let currScreen = 'login';

    let userPop = function(uid){
        for(const [id,user] of Object.entries(usersList)) {
            if(user.id == uid) {
                document.querySelector(".user-data[data-type='name'] span").innerHTML = user.name;
                document.querySelector(".user-data[data-type='email'] span").innerHTML = user.email;
                document.querySelector(".user-data[data-type='useragent'] span").innerHTML = user.userAgent;
                document.querySelector(".user-data[data-type='entancetime'] span").innerHTML = user.entranceTime;
                document.querySelector(".user-data[data-type='visitcount'] span").innerHTML = user.visitCount;
                document.getElementById("user-pop").style.display = 'block';
                document.getElementById("user-pop").addEventListener("click",function (event) {
                    if(event.target.id == 'user-pop') {
                        document.getElementById("user-pop").style.display = 'none';
                        document.querySelector(".user-data span").innerHTML = '';
                    }

                });
                return;
            }
        }

    }

    let showList = function(response){

        const users = response.users;
        if(usersList != users) {
            usersList = users;
            const tableBody = document.querySelector(screenList.dashboard.selector  + " table tbody");
            tableBody.innerHTML = '';
            let line ='';
            for(const [id,user] of Object.entries(users)) {
                line +=  '<tr class="user-row" data-id="'+user.id+'"><td>'+user.name+'</td><td>'+user.entranceTime+'</td><td>'+user.lastUpdate+'</td><td>'+user.userIp+'</td></tr>';
            }
            tableBody.innerHTML = line;
            let rows = document.querySelectorAll(".user-row");
            for (let i = 0; i < rows.length; i++) {
                rows[i].addEventListener("click",function () {

                    userPop(this.dataset.id);
                })
            }

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
            document.getElementById("username").innerHTML = Connect.name;
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
        clearInterval(timers['list']);
        http.httpGet('?act=logout');
        switchScreen("login");
    };


    let init = function () {
        let self = this;

        window.addEventListener( 'beforeunload', function( event ) {
            alert("logout");
            http.httpGet('?act=logout');
        }, {capture: true});
        window.addEventListener( 'unload', function( event ) {
            alert("logout");
            http.httpGet('?act=logout');
        }, {capture: true});
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
        logout: logout,
        userPop: userPop
    }

})();

