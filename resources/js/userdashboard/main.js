// 1. Import Bootstrap JS (This makes dropdowns/tooltips work)
import * as bootstrap from 'bootstrap';

// 2. Import your custom logic (which pulls in sidebar and charts)
import './custom.js';

// 3. Import SCSS (Corrected path to your style.scss)
// From resources/js/userdashboard/ to resources/css/userdashboard/
import '../../css/userdashboard/style.scss';

// Optional: Export bootstrap if you need to use it in inline scripts
window.bootstrap = bootstrap;