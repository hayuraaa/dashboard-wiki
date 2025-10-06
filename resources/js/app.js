import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { 
    faTachometerAlt, 
    faUsers, 
    faShieldAlt, 
    faLock, 
    faCog, 
    faSignOutAlt, 
    faBars, 
    faTimes,
    faChevronDown,
    faChevronRight,
    faHistory,
    faEdit,          
    faPhone,         
    faEnvelope,      
    faMapMarkerAlt,  
    faCalendar,
    faUser,
    faMoon,   
    faSun,
    faHome,
    faDatabase,
    faGlobe,
    faCheck,
    faUserFriends,
    faInbox,
    faEnvelopeOpenText,
    faBookOpen,        
    faBullhorn,        
    faImage           
} from '@fortawesome/free-solid-svg-icons';

// Add Font Awesome icons to the library
library.add(
    faTachometerAlt, 
    faUsers, 
    faShieldAlt, 
    faLock, 
    faCog, 
    faSignOutAlt, 
    faBars, 
    faTimes,
    faChevronDown,
    faChevronRight,
    faHistory,
    faEdit,          
    faPhone,         
    faEnvelope,      
    faMapMarkerAlt,  
    faCalendar,
    faUser,
    faMoon,   
    faSun,
    faHome,
    faDatabase,
    faGlobe,
    faCheck,
    faUserFriends,
    faInbox,
    faEnvelopeOpenText,
    faBookOpen,
    faBullhorn,
    faImage
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => {
        if (title.includes(' - ')) {
            return title;
        }
        return `${title} - ${appName}`;
    },
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});