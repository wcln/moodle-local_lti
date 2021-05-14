import axios from "axios";

const WEBSERVICE_URL = '/lib/ajax/service-nologin.php';

export const moodleAjax = (wsfunction, token, args) => {
    return new Promise((resolve, reject) => {
        axios.get(WEBSERVICE_URL, {
            params: {
                args: JSON.stringify([{
                    methodname: wsfunction,
                    args: {
                        token: token,
                        ...args
                    }
                }])
            }
        }).then(response => {

            // Check for any errors
            if (response.data[0].data !== undefined && response.data[0].data.error) {
                reject(response.data[0].data.error);
            } else if (response.data.error) {
                console.error(response.data.exception);
                reject({code: 'E000', message: response.data.exception.message});
            } else if (response.data[0].error) {
                console.error(response.data[0].exception);
                reject({code: 'E000', message: response.data[0].exception.message});
            }

            resolve(response.data[0].data);
        })
    });
}

export default {
    data() {
        return {
            webserviceUrl: WEBSERVICE_URL
        }
    },
    methods: {
        moodleAjax
    }
}
