import '../scss/styles.scss';

// import * as bootstrap from 'bootstrap';
import '~bootstrap/js/src/alert';
import '~bootstrap/js/src/collapse';
import '~bootstrap/js/src/dropdown';
import '~bootstrap/js/src/modal';

import Alpine from 'alpinejs';
import axios from 'axios';

import { Autocomplete } from './autocomplete';

window.Alpine = Alpine;
window.axios = axios;

document.addEventListener('alpine:init', () => {
    Alpine.data('autocomplete', () => ({
        searchQuery: '',
        searchInstances: [],
        init() {
            const searchInputs = document.querySelectorAll('input[data-search]');
            searchInputs.forEach(searchInput => {
                const search = new Autocomplete(searchInput, {
                    data: [],
                    maximumItems: 5,
                    onSelectItem: ({label, value}) => {
                        window.open(value, '_self');
                    },
                    highlightClass: '',
                });
                this.searchInstances.push(search);
            });
        },
        getAutocomplete() {
            const baseURL = this.$refs.base.dataset.base;
            axios({
                method: 'GET',
                url: baseURL,
                params: {
                    q: this.searchQuery,
                    req: this.countRequest,
                }
            })
                .then(response => {
                    const responseData = response.data;
                    const responseStatus = response.status;

                    if (responseStatus == '200' && responseData.success == true) {
                        const results = responseData.results;
                        const dataset = [];
                        results.forEach(result => {
                            dataset.push({
                                label: result.name,
                                value: result.route,
                                tag: result.tag,
                            });
                        });

                        this.searchInstances.forEach(searchInstance => {
                            searchInstance.setData(dataset);
                        });
                    }
                });
        },
    }));
});

Alpine.start();