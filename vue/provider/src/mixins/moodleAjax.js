import axios from "axios";

const WEBSERVICE_URL = '/lib/ajax/service-nologin.php';

export const moodleAjax = (wsfunction, token, args) => {
    return new Promise((resolve,) => {
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
            if (response.data.error || response.data[0].error) {
                throw new Error('Error querying Moodle webservice, see details below:\n\n' + JSON.stringify(response.data));
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
