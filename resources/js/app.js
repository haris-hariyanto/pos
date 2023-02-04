import '../scss/styles.scss';

// import * as bootstrap from 'bootstrap';
import '~bootstrap/js/src/alert';
import '~bootstrap/js/src/collapse';
import '~bootstrap/js/src/dropdown';
import '~bootstrap/js/src/modal';

import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;
window.axios = axios;

Alpine.start();