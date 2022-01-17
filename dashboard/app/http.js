import { appConfig } from './../bin/config.js';
import { Connect } from './connect.js';
export const http = {
    httpPost: function (call,sendData ,callback) {
        let xhr = new XMLHttpRequest();
        let url = appConfig.serverBaseUrl + appConfig.baseApi  + call;
        let userID = Connect.userID;
        xhr.open("POST", url, true);
        xhr.setRequestHeader('USERID', userID);
        xhr.onreadystatechange = function () {
            if (this.readyState != 4) return;

            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                if(typeof callback == 'function')
                    callback(data);

                // we get the returned data
            }

            // end of state change: it can be after some time (async)
        };
        xhr.send(JSON.stringify(sendData));
    },
    httpGet: function (call ,callback) {
        let xhr = new XMLHttpRequest();
        let url = appConfig.serverBaseUrl + appConfig.baseApi  + call;
        let userID = Connect.userID;
        xhr.open("GET", url, true);
        xhr.setRequestHeader('USERID', userID);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (this.readyState != 4) return;

            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                if(typeof callback == 'function')
                    callback(data);
                // we get the returned data
            }

            // end of state change: it can be after some time (async)
        };

        xhr.send();
    }
}