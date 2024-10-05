import axios from 'axios';
import Swal from "sweetalert2";

const { API_HOST, API_VERSION, API_PREFIX } = { API_HOST: "http://127.0.0.1:8000", API_PREFIX: "api", API_VERSION: "" };

export class Api {

    static onError(error) {
        if (error.response && error.response.data) {

            let data = error.response.data;
            if (data.message && typeof data.message === 'string') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message
                });
            } else if (data.error && typeof data.error === 'string') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.error
                });
            } else if (data.message && Array.isArray(data.message)) {
                let messages = data.message;
                // Handle the array of messages if needed
            }

        } else {
            console.error(error);
        }
    }

    static getConfig(with_token = true) {
        let config = {
            headers: {
                'Content-Type': 'application/json'
            }
        };

        if (with_token) {
            let token = localStorage.getItem('token');
            if (token) {
                config.headers = { ...config.headers, "Authorization": `Bearer ${token}` };
            }
        }

        return config;
    }

    static post(path, data, with_token = true, silent = false, with_notify = true) {
        return new Promise((resolve, reject) => {
            let config = Api.getConfig(with_token);
            axios.post(`${API_HOST}/${API_PREFIX}/${API_VERSION}${path}`, data, config).then((response) => {
                resolve(response);
            }).catch((error) => {
                Api.onError(error);
                reject(error);
            });
        });
    }

    static get(path, with_token = true, silent = false) {
        return new Promise((resolve, reject) => {
            let config = Api.getConfig(with_token);
            axios.get(`${API_HOST}/${API_PREFIX}/${API_VERSION}${path}`, config).then((response) => {
                resolve(response);
            }).catch((error) => {
                Api.onError(error);
                reject(error);
            });
        });
    }

    static put(path, data, with_token = true, silent = false) {
        return new Promise((resolve, reject) => {
            let config = Api.getConfig(with_token);
            axios.put(`${API_HOST}/${API_PREFIX}/${API_VERSION}${path}`, data, config).then((response) => {
                resolve(response);
            }).catch((error) => {
                Api.onError(error);
                reject(error);
            });
        });
    }

    static delete(path, with_token = true, silent = false) {
        return new Promise((resolve, reject) => {
            let config = Api.getConfig(with_token);
            axios.delete(`${API_HOST}/${API_PREFIX}/${API_VERSION}${path}`, config).then((response) => {
                resolve(response);
            }).catch((error) => {
                Api.onError(error);
                reject(error);
            });
        });
    }

    static upload(path, formdata, with_token = true, silent = false) {
        return new Promise((resolve, reject) => {
            let config = Api.getConfig(with_token);
            config.headers = { ...config.headers, 'Content-Type': 'multipart/form-data' };
            axios.post(`${API_HOST}/${API_PREFIX}/${API_VERSION}${path}`, formdata, config).then((response) => {
                resolve(response);
            }).catch((error) => {
                Api.onError(error);
                reject(error);
            });
        });
    }
}
