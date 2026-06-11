<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({ service: { type: Object, required: true } })

const activeTab = ref('Languages')
const tabs = ['Languages', 'Frameworks', 'Tools', 'Cloud & DevOps', 'Testing', 'Design']

const techData = {
  Languages: [
    { name: 'Swift' },
    { name: 'Kotlin' },
    { name: 'Dart' },
    { name: 'Java' },
    { name: 'Objective-C' },
    { name: 'JavaScript' },
    { name: 'TypeScript' },
    { name: 'Python' },
    { name: 'PHP' },
    { name: 'C#' },
    { name: 'C++' },
    { name: 'HTML5' },
    { name: 'CSS3' }
  ],
  Frameworks: [
    { name: 'Flutter' },
    { name: 'React Native' },
    { name: 'Xamarin' },
    { name: 'Ionic' },
    { name: 'Apache Cordova' },
    { name: 'PhoneGap' },
    { name: '.NET MAUI' }
  ],
  Tools: [
    { name: 'Figma' },
    { name: 'Git' },
    { name: 'VS Code' },
    { name: 'Postman' },
    { name: 'Android Studio' },
    { name: 'Xcode' }
  ],
  'Cloud & DevOps': [
    { name: 'AWS' },
    { name: 'Google Cloud' },
    { name: 'Microsoft Azure' },
    { name: 'Docker' },
    { name: 'Kubernetes' },
    { name: 'GitHub Actions' },
    { name: 'Bitrise' }
  ],
  Testing: [
    { name: 'Jest' },
    { name: 'Appium' },
    { name: 'Selenium' },
    { name: 'JUnit' },
    { name: 'XCTest' }
  ],
  Design: [
    { name: 'Figma' },
    { name: 'Adobe XD' },
    { name: 'Sketch' },
    { name: 'Photoshop' },
    { name: 'Illustrator' }
  ]
}

const cmsData = [
  { name: 'WordPress' },
  { name: 'Shopify' },
  { name: 'Webflow' },
  { name: 'Wix' },
  { name: 'Squarespace' },
  { name: 'Joomla' },
  { name: 'Drupal' },
  { name: 'Magento' },
  { name: 'PrestaShop' },
  { name: 'OpenCart' },
  { name: 'Strapi' },
  { name: 'Contentful' }
]

// Custom Brand SVGs using the theme colors (Indigo/Purple/Pink) and official brand highlights
const svgLogos = {
  // Languages
  'Swift': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FA5A28"><path d="M21.5 16.5c-1.5-2.2-4.1-3.6-7-4.2 2.8-.8 5.1-2.5 6.4-4.8-2 1.4-4.5 2.2-7.1 2.3 2.1-1.5 3.5-3.8 3.8-6.4-2.8 2.2-6.3 3.4-10 3.4-1.2 0-2.3-.1-3.4-.4 1.5 1.5 3.5 2.5 5.8 2.8-3.4 1.2-5.7 3.8-6.2 7 2.4-1.5 5.3-2.3 8.3-2.3.9 0 1.8.1 2.7.2-2 1.5-3.3 3.8-3.5 6.4 2.8-2 6.3-3.1 10-3.1.2 0 .4 0 .6.1-.2-.2-.3-.5-.4-.9z"/></svg>`,
  'Kotlin': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M2 2h20L2 22V2z" fill="url(#kot-app)"/><defs><linearGradient id="kot-app" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#E10098"/><stop offset="100%" stop-color="#7F52FF"/></linearGradient></defs></svg>`,
  'Dart': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#00C4B4"><path d="M12 2L2 7.8v8.4l10 5.8 10-5.8V7.8L12 2zm0 16.5l-6.5-3.8V8.3l6.5-3.8 6.5 3.8v4.6l-6.5 3.8z"/></svg>`,
  'Java': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#EA2D2E"><path d="M2 19c3 0 5-1 8-1s5 1 8 1 4-1 4-1v-2s-1 1-4 1-5-1-8-1-5 1-8 1h-2v2zm12-9c0-3.5-2-4.5-2-4.5s2.5 1.5 2.5 4.5S11 14.5 11 14.5s3-1.5 3-4.5z"/></svg>`,
  'Objective-C': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3178C6"><circle cx="12" cy="12" r="11" fill="none" stroke="#3178C6" stroke-width="2"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="10.5" fill="#3178C6" text-anchor="middle">Obc</text></svg>`,
  'JavaScript': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#F7DF1E"><rect width="24" height="24" rx="4"/><text x="20" y="20" font-family="system-ui" font-weight="900" font-size="10.5" fill="#000" text-anchor="end">JS</text></svg>`,
  'TypeScript': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3178C6"><rect width="24" height="24" rx="4"/><text x="20" y="20" font-family="system-ui" font-weight="900" font-size="10" fill="#fff" text-anchor="end">TS</text></svg>`,
  'Python': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3776AB"><circle cx="12" cy="12" r="11" fill="none" stroke="#3776AB" stroke-width="2"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11" fill="#3776AB" text-anchor="middle">Py</text></svg>`,
  'PHP': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#777BB4"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="11" fill="#fff" text-anchor="middle">php</text></svg>`,
  'C#': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#35007E"><rect width="24" height="24" rx="4" fill="#35007E"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11.5" fill="#fff" text-anchor="middle">C#</text></svg>`,
  'C++': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#00599C"><circle cx="12" cy="12" r="11" fill="none" stroke="#00599C" stroke-width="2"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9.5" fill="#00599C" text-anchor="middle">C++</text></svg>`,
  'HTML5': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#E34F26"><path d="M1.5 0h21l-1.9 21.2L12 24l-8.6-2.8L1.5 0zm16.5 6H7l.3 3.3h10.4l-.5 5.5-4.7 1.3-4.7-1.3-.3-3.1h-3l.5 5.9 7.5 2.1 7.5-2.1 1-11.6H18z"/></svg>`,
  'CSS3': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#1572B6"><path d="M1.5 0h21l-1.9 21.2L12 24l-8.6-2.8L1.5 0zm16.5 6h-11l.3 3.3h10.4l-.5 5.5-4.7 1.3-4.7-1.3-.3-3.1H8l-.3-3.3h10.4l.6-5.7z"/></svg>`,

  // Frameworks
  'Flutter': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M13.5 1.5L4 11l9.5 9.5H20L10.5 11 20 1.5h-6.5z" fill="#02569B"/><path d="M12 11l5-5h-4.5L9 9.5 12.5 13z" fill="#0175C2"/></svg>`,
  'React Native': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="#61DAFB" stroke-width="2"><ellipse cx="12" cy="12" rx="3" ry="9" transform="rotate(30 12 12)"/><ellipse cx="12" cy="12" rx="3" ry="9" transform="rotate(90 12 12)"/><ellipse cx="12" cy="12" rx="3" ry="9" transform="rotate(150 12 12)"/><circle cx="12" cy="12" r="1.5" fill="#61DAFB"/></svg>`,
  'Xamarin': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3498DB"><path d="M12 2L2 7.8v8.4l10 5.8 10-5.8V7.8L12 2zm4.5 13.5h-3L12 11.8l-1.5 3.7h-3L10.5 10 7.5 4.5h3L12 8.2l1.5-3.7h3L13.5 10l3 5.5z"/></svg>`,
  'Ionic': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3880FF"><circle cx="12" cy="12" r="11" fill="none" stroke="#3880FF" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="#3880FF"/></svg>`,
  'Apache Cordova': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#303030"><rect width="24" height="24" rx="4" fill="#303030"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">Cordova</text></svg>`,
  'PhoneGap': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#2563EB"><rect width="24" height="24" rx="4" fill="#2563EB"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">PG</text></svg>`,
  '.NET MAUI': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#512BD4"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">MAUI</text></svg>`,

  // Tools
  'Figma': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M8.5 12a3.5 3.5 0 110-7h7v7h-7z" fill="#F24E1E"/><path d="M8.5 19a3.5 3.5 0 010-7h3.5v3.5A3.5 3.5 0 018.5 19z" fill="#0ACF83"/><circle cx="15.5" cy="8.5" r="3.5" fill="#FF7262"/><circle cx="15.5" cy="15.5" r="3.5" fill="#1ABCFE"/></svg>`,
  'Git': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#F05032"><path d="M22.6 11.3L12.7 1.4c-.4-.4-1-.4-1.4 0L1.4 11.3c-.4.4-.4 1 0 1.4l9.9 9.9c.4.4 1 .4 1.4 0l9.9-9.9c.4-.4.4-1 0-1.4zM12 18c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3z"/></svg>`,
  'VS Code': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#007ACC"><path d="M22 6.5L14.5 1 2 12l12.5 11 7.5-5.5V6.5zm-4.5 8l-3-2.5 3-2.5v5z"/></svg>`,
  'Postman': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF6C37"><path d="M12 1C5.9 1 1 5.9 1 12s4.9 11 11 11 11-4.9 11-11S18.1 1 12 1zm3 14.5l-3-3-3 3v-7.5l3 3 3-3v7.5z"/></svg>`,
  'Android Studio': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3DDC84"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1 14h2v2h-2v-2zm0-10h2v8h-2V6z"/></svg>`,
  'Xcode': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#1B82F6"><circle cx="12" cy="12" r="12" fill="#1B82F6"/><path d="M7 16V8h2l3 4 3-4h2v8h-2v-5l-3 4-3-4v5H7z" fill="#fff"/></svg>`,

  // Cloud & DevOps
  'AWS': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF9900"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="8.5" fill="#fff" text-anchor="middle">AWS</text></svg>`,
  'Google Cloud': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#4285F4"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9.5" fill="#fff" text-anchor="middle">GCP</text></svg>`,
  'Microsoft Azure': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#0089D6"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">Azure</text></svg>`,
  'Docker': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#2496ED"><path d="M1.5 9h21l-1.9 8.2-8.6 2.8-8.6-2.8L1.5 9z" fill="#2496ED"/><rect x="8" y="4" width="3" height="3" fill="#2496ED"/><rect x="13" y="4" width="3" height="3" fill="#2496ED"/></svg>`,
  'Kubernetes': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#326CE5"><path d="M12 1.5L2.5 7v10l9.5 5.5 9.5-5.5V7L12 1.5zm0 4l6.5 3.8v7.4L12 20.5 5.5 16.7V9.3L12 5.5z"/></svg>`,
  'GitHub Actions': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#2088FF"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">CI/CD</text></svg>`,
  'Bitrise': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#9B26AF"><rect width="24" height="24" rx="4" fill="#8E44AD"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="8.5" fill="#fff" text-anchor="middle">Bitrise</text></svg>`,
  'CI/CD': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#2088FF"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">CI/CD</text></svg>`,

  // Testing
  'Jest': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#C21325"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">Jest</text></svg>`,
  'Appium': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#5F3FAE"><rect width="24" height="24" rx="4" fill="#5F3FAE"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">Appium</text></svg>`,
  'Selenium': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#00B000"><rect width="24" height="24" rx="4" fill="#00B000"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11" fill="#fff" text-anchor="middle">Se</text></svg>`,
  'JUnit': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3E8624"><rect width="24" height="24" rx="4" fill="#3E8624"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11" fill="#fff" text-anchor="middle">JUnit</text></svg>`,
  'XCTest': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#1C82F6"><rect width="24" height="24" rx="4" fill="#1C82F6"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8.5" fill="#fff" text-anchor="middle">XCTest</text></svg>`,

  // Design
  'Adobe XD': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF26C8"><rect width="24" height="24" rx="4" fill="#470037"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11" fill="#FF26C8" text-anchor="middle">Xd</text></svg>`,
  'Sketch': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FDB300"><path d="M12 2L2 8l10 12 10-12L12 2z"/></svg>`,
  'Photoshop': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#00C8FF"><rect width="24" height="24" rx="4" fill="#001C2E"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11" fill="#00C8FF" text-anchor="middle">Ps</text></svg>`,
  'Illustrator': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF9A00"><rect width="24" height="24" rx="4" fill="#331A00"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11" fill="#FF9A00" text-anchor="middle">Ai</text></svg>`,

  // Databases & Backend
  'Firebase': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FFCA28"><path d="M3.9 18.5l9-16.2c.2-.4.8-.4 1 0l2 3.6-12 12.6zm16.2-2.1L16 11l-3 5.4 7.1 2.1z"/></svg>`,
  'MySQL': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#4479A1"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">MySQL</text></svg>`,
  'PostgreSQL': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#336791"><circle cx="12" cy="12" r="11" fill="none" stroke="#336791" stroke-width="2"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="9" fill="#336791" text-anchor="middle">SQL</text></svg>`,
  'MongoDB': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#47A248"><path d="M12 1.5c-.3 0-6 4.5-6 10.5s2.7 8.5 6 10.5c3.3-2 6-4.5 6-10.5S12.3 1.5 12 1.5zm0 15c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3z"/></svg>`,
  'SQLite': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#003B57"><rect width="24" height="24" rx="4"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">Lite</text></svg>`,
  'Realm': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#F30067"><rect width="24" height="24" rx="4" fill="#39477C"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8.5" fill="#fff" text-anchor="middle">Realm</text></svg>`,
  'Supabase': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3ECF8E"><path d="M13.5 1.5L4 12.5h7.5V22.5L20 11.5h-6.5z"/></svg>`,
  'AWS Amplify': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF9900"><rect width="24" height="24" rx="4" fill="#FF9900"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">Amplify</text></svg>`,

  // CMS
  'WordPress': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#21759B"><circle cx="12" cy="12" r="12" fill="#21759B"/><path d="M12.2 2.2C6.8 2.2 2.4 6.6 2.4 12c0 2.2.7 4.2 2 5.8l3-8.6c.4-1 .7-1.8.7-2.4 0-.5-.4-.9-1-.9h-.2V5.4h4.4v.5h-.3c-.6 0-.9.4-1.2 1.2l-2.2 6.8 2.4 7.2c.5-1.4.9-2.5 1-3.2.3-.9.5-1.5.5-2 0-.5-.3-.9-1-.9h-.3v-.5h4.6v.5h-.3c-.6 0-.8.4-1.1 1.2l-2 5.8 2.7 8.2c1.7-1.5 2.7-3.7 2.7-6.2 0-5.4-4.4-9.8-9.8-9.8zm0 .5c4.7 0 8.5 3.8 8.5 8.5v.3L16 2.7c-1.3 0-2.3.8-2.3 2.1 0 .7.3 1.3.6 1.8l3.3 9.4c1.3-3.6 1.3-6.1 1.3-6.6 0-1.1-.7-1.6-1.6-1.6h-.3v-.5h4.7v.5h-.3c-.6 0-.9.4-1.1 1.2l-3.3 9.4 6.7-18.9c-.3-.2-.6-.4-1-.5z" fill="#fff"/></svg>`,
  'Shopify': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#96BF48"><path d="M19.5 6.5L16 2.2c-.3-.4-.8-.6-1.3-.6H9.3c-.5 0-1 .2-1.3.6L4.5 6.5c-.3.4-.5.9-.5 1.5v12.4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-.6-.2-1.1-.5-1.5z" fill="#96BF48"/><path d="M12 1C9.2 1 7 3.2 7 6h10c0-2.8-2.2-5-5-5z" fill="#7A9C39"/><path d="M9.5 9.5c0-.8.7-1.5 1.5-1.5h2c.8 0 1.5.7 1.5 1.5v2c0 .8-.7 1.5-1.5 1.5h-2c-.8 0-1.5-.7-1.5-1.5v-2z" fill="#fff"/></svg>`,
  'Webflow': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#4353FF"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">webflow</text></svg>`,
  'Wix': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#000"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">Wix</text></svg>`,
  'Squarespace': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#000"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="800" font-size="6.5" fill="#fff" text-anchor="middle">SQUARE</text></svg>`,
  'Joomla': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#F44321"><circle cx="12" cy="12" r="12" fill="#F44321"/><path d="M12 6c-3.3 0-6 2.7-6 6s2.7 6 6 6 6-2.7 6-6-2.7-6-6-6zm0 2c2.2 0 4 1.8 4 4s-1.8 4-4 4-4-1.8-4-4 1.8-4 4-4z" fill="#fff"/></svg>`,
  'Drupal': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#0678BE"><circle cx="12" cy="12" r="12" fill="#0678BE"/><path d="M12 3.5c0 0-6 6.5-6 10.5 0 3.3 2.7 6 6 6s6-2.7 6-6c0-4-6-10.5-6-10.5zm0 13c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3z" fill="#fff"/></svg>`,
  'Magento': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#EE672F"><path d="M12 2L3 7v10l9 5 9-5V7l-9-5zm7 14.2l-7 3.8-7-3.8V8.8l7-3.8 7 3.8v7.4z"/><path d="M12 7.5l4.5 2.5v4.5L12 17l-4.5-2.5v-4.5L12 7.5z" fill="#EE672F"/></svg>`,
  'PrestaShop': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#DF0067"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">Presta</text></svg>`,
  'OpenCart': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#43A8E0"><circle cx="12" cy="12" r="12" fill="#43A8E0"/><path d="M7 8h10l-1.5 5h-7L7 8zm0 0l-1-3H4v1h1.5l1.5 5h10l2-6H7zm2 10a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm8 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" fill="#fff"/></svg>`,
  'Strapi': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#4945FF"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">strapi</text></svg>`,
  'Contentful': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#2478CC"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">content</text></svg>`
}

const getLogoSvg = (name) => {
  return svgLogos[name] || `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#6366F1"><rect width="24" height="24" rx="4"/><text x="12" y="15" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">${name.slice(0,3)}</text></svg>`
}

const process = [
  { num: '1. Discovery & Planning', title: 'Discovery & Planning', desc: 'We analyze your idea, target audience, and market to create a clear roadmap.' },
  { num: '2. UI/UX Design', title: 'UI/UX Design', desc: 'We design intuitive, engaging, and user-friendly interfaces.' },
  { num: '3. Development', title: 'Development', desc: 'We build clean, scalable, and high-performance applications.' },
  { num: '4. Testing & QA', title: 'Testing & QA', desc: 'We ensure your app is bug-free, secure, and performs flawlessly.' },
  { num: '5. Deployment', title: 'Deployment', desc: 'We deploy your app to stores or servers with a smooth launch.' },
  { num: '6. Support & Maintenance', title: 'Support & Maintenance', desc: 'We provide ongoing support and updates for your app.' }
]

const whatYouGet = [
  { title: 'Custom App Solutions', desc: 'Tailored apps to meet your business goals.' },
  { title: 'High Performance', desc: 'Fast, reliable, and smooth performance.' },
  { title: 'Scalable Architecture', desc: 'Built to grow with your business needs.' },
  { title: 'Secure & Compliant', desc: 'Data security and compliance at every step.' },
  { title: 'User-Centric Design', desc: 'Intuitive design that enhances user experience.' },
  { title: 'App Store Optimization', desc: 'We optimize your app for better visibility.' },
  { title: 'Timely Delivery', desc: 'On-time delivery with agile methodology.' },
  { title: 'Ongoing Support', desc: 'Continuous support & maintenance.' }
]

const addServices = [
  { label: 'Enterprise Mobility Solutions' },
  { label: 'Wearable App Development' },
  { label: 'API Development & Integration' },
  { label: 'IoT App Development' },
  { label: 'Chatbots & AI Integration' },
  { label: 'Maintenance & Support' }
]

const features = [
  { label: 'User-Centric Design' },
  { label: 'High Performance' },
  { label: 'Secure & Scalable' },
  { label: 'Cross-Platform' }
]

// Scroll Reveal Observer
const observers = []
onMounted(() => {
  document.querySelectorAll('[data-rev]').forEach(el => {
    const delay = parseFloat(el.dataset.delay || 0)
    el.style.opacity = '0'
    el.style.transform = 'translateY(28px)'
    el.style.transition = `opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s, transform 0.7s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s`
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.opacity = '1'
          e.target.style.transform = 'translateY(0)'
          obs.unobserve(e.target)
        }
      })
    }, { threshold: 0.05 })
    obs.observe(el)
    observers.push(obs)
  })
})
onUnmounted(() => observers.forEach(o => o.disconnect()))
</script>

<template>
  <Head>
    <title>App Development Services — RankQuill</title>
    <meta name="description" content="We design and develop high-performance mobile apps that engage users, drive growth, and deliver exceptional experiences across all devices." />
  </Head>

  <PublicLayout>
    <div class="wd-page select-none selection:bg-indigo-500 selection:text-white bg-slate-50 dark:bg-[#070b14] text-slate-800 dark:text-slate-100 transition-colors duration-300">

      <!-- ═══════════════════════════════════════════════════
           HERO SECTION
      ═══════════════════════════════════════════════════ -->
      <section class="wd-hero relative overflow-hidden bg-white dark:bg-[#070b14]">
        <div class="wd-hero__bg-glow-1"></div>
        <div class="wd-hero__bg-glow-2"></div>
        <div class="wd-hero__dots-pattern"></div>
        
        <div class="wd-container relative z-10">
          
          <!-- Breadcrumb -->
          <nav class="wd-breadcrumb mb-6 lg:mb-8" aria-label="Breadcrumb">
            <Link href="/" class="wd-breadcrumb__link text-slate-500 dark:text-slate-400">Home</Link>
            <span class="wd-breadcrumb__sep text-slate-300 dark:text-slate-600">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
            <Link href="/services" class="wd-breadcrumb__link text-slate-500 dark:text-slate-400">Services</Link>
            <span class="wd-breadcrumb__sep text-slate-300 dark:text-slate-600">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
            <span class="wd-breadcrumb__active text-slate-800 dark:text-slate-200">App Development</span>
          </nav>

          <div class="wd-hero__grid">
            
            <!-- Hero Info Left -->
            <div class="wd-hero__left flex flex-col items-start text-left">
              <div class="wd-pill mb-5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                <!-- App icon -->
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                App Development
              </div>

              <h1 class="wd-hero__heading mb-5 text-slate-900 dark:text-white">
                Powerful Apps.<br/>
                <span class="gradient-text-animated">Seamless Experiences.</span><br/>
                Real Results.
              </h1>

              <p class="wd-hero__para mb-6 text-slate-500 dark:text-slate-400">
                We design and develop high-performance mobile apps that engage users, drive growth, and deliver exceptional experiences across all devices.
              </p>

              <!-- Hero Feature Badges -->
              <div class="wd-features-row mb-8">
                <div v-for="f in features" :key="f.label" class="wd-feature-item">
                  <span class="wd-feature-item__icon-bg bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                  </span>
                  <span class="wd-feature-item__label text-slate-700 dark:text-slate-300">{{ f.label }}</span>
                </div>
              </div>

              <!-- Hero CTA Buttons -->
              <div class="wd-hero__btns">
                <Link href="/contact-us" class="wd-btn wd-btn--primary">
                  <span>Get a Free Quote</span>
                  <span class="wd-btn__circle">
                    <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                  </span>
                </Link>
                <Link href="/portfolio" class="wd-btn wd-btn--outline border-indigo-500/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-500/10">
                  <span>View Our Work</span>
                  <span class="wd-btn__circle bg-indigo-500/10">
                    <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                  </span>
                </Link>
              </div>
            </div>

            <!-- Hero Visual Right (Diagonally overlapping smartphone mockups) -->
            <div class="wd-hero__right">
              <div class="wd-mockup-wrap">
                
                <!-- Phone Mockup 1 (Dark dashboard template, rotated left) -->
                <div class="wd-diagonal-phone wd-diagonal-phone--left">
                  <div class="wd-phone-outer border-slate-200 dark:border-slate-800">
                    <div class="wd-phone-inner bg-[#0b0f19] text-white">
                      <div class="wd-phone-status-bar">
                        <span>9:41</span>
                        <div class="wd-phone-notch bg-slate-850"></div>
                      </div>
                      
                      <!-- Dark App UI content -->
                      <div class="wd-phone-app-content p-4">
                        <div class="flex items-center justify-between mb-4">
                          <span class="text-[9px] text-slate-400">Hello, User</span>
                          <div class="w-6 h-6 rounded-full bg-slate-800"></div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl p-3.5 text-left mb-4 shadow-lg">
                          <span class="text-[8px] text-indigo-100">Total Balance</span>
                          <h4 class="text-lg font-bold mt-1">$24,610.50</h4>
                          <span class="text-[8px] text-indigo-200 mt-2 block">**** **** **** 1408</span>
                        </div>
                        
                        <div class="text-left mb-2">
                          <span class="text-[9px] font-bold text-slate-300">Recent Transactions</span>
                        </div>
                        <div class="space-y-1.5">
                          <div v-for="t in [{n:'Netflix',a:'-$14.99',c:'text-rose-500'},{n:'PayPal',a:'+$150.00',c:'text-emerald-500'},{n:'Spotify',a:'-$9.99',c:'text-rose-500'}]" :key="t.n"
                            class="flex items-center justify-between bg-slate-900 border border-slate-800/50 rounded-lg p-2">
                            <span class="text-[8px] font-medium text-slate-200">{{ t.n }}</span>
                            <span class="text-[8px] font-bold" :class="t.c">{{ t.a }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Phone Mockup 2 (Light statistics template, rotated right) -->
                <div class="wd-diagonal-phone wd-diagonal-phone--right">
                  <div class="wd-phone-outer border-slate-200 dark:border-slate-800">
                    <div class="wd-phone-inner bg-white text-slate-800">
                      <div class="wd-phone-status-bar bg-white">
                        <span class="text-slate-800">9:41</span>
                        <div class="wd-phone-notch bg-slate-800"></div>
                      </div>
                      
                      <!-- Light App UI content -->
                      <div class="wd-phone-app-content p-4">
                        <div class="text-left mb-3">
                          <span class="text-[9px] font-bold text-slate-500">Statistics</span>
                          <h4 class="text-lg font-black text-slate-900 mt-1">$7,540</h4>
                        </div>
                        
                        <!-- Mini Bar Chart -->
                        <div class="flex items-end gap-1.5 h-16 mb-4 bg-slate-50 border border-slate-100 rounded-lg p-2">
                          <div v-for="(h, i) in [40,65,48,85,72,100]" :key="i"
                            class="flex-1 rounded-t bg-indigo-500 transition-all"
                            :style="`height:${h}%`"></div>
                        </div>
                        
                        <!-- Categories grid -->
                        <div class="grid grid-cols-2 gap-2 text-left">
                          <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-2.5">
                            <span class="text-[7px] text-indigo-600 block">Conversion</span>
                            <span class="text-xs font-bold text-indigo-900 mt-0.5">+15.4%</span>
                          </div>
                          <div class="bg-purple-50 border border-purple-100 rounded-lg p-2.5">
                            <span class="text-[7px] text-purple-600 block">Growth</span>
                            <span class="text-xs font-bold text-purple-900 mt-0.5">+34.8%</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Floating Glassmorphism Info Badge 1 (Top right) -->
                <div class="wd-glass-badge wd-glass-badge--top-right bg-white/80 dark:bg-slate-900/80 border-slate-200/50 dark:border-slate-800/80">
                  <span class="wd-glass-badge__icon text-indigo-500 dark:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </span>
                  <div class="flex flex-col">
                    <span class="wd-glass-badge__title text-slate-800 dark:text-slate-100">Cross-Platform</span>
                    <span class="wd-glass-badge__desc text-slate-500 dark:text-slate-400">iOS, Android &amp; Web</span>
                  </div>
                </div>

                <!-- Floating Glassmorphism Info Badge 2 (Bottom center) -->
                <div class="wd-glass-badge wd-glass-badge--bottom-center bg-white/80 dark:bg-slate-900/80 border-slate-200/50 dark:border-slate-800/80">
                  <span class="wd-glass-badge__icon text-purple-500 dark:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                  </span>
                  <div class="flex flex-col">
                    <span class="wd-glass-badge__title text-slate-800 dark:text-slate-100">Performance Optimized</span>
                  </div>
                </div>

                <!-- Floating Glassmorphism Info Badge 3 (Bottom right) -->
                <div class="wd-glass-badge wd-glass-badge--bottom-right bg-white/80 dark:bg-slate-900/80 border-slate-200/50 dark:border-slate-800/80">
                  <span class="wd-glass-badge__icon text-emerald-500 dark:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  </span>
                  <div class="flex flex-col">
                    <span class="wd-glass-badge__title text-slate-800 dark:text-slate-100">Security</span>
                    <span class="wd-glass-badge__desc text-slate-500 dark:text-slate-400">Enterprise Grade</span>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           SECTION 2: PROCESS
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-white dark:bg-[#0b0f19] border-b border-slate-100 dark:border-slate-800/60">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-16">
            <h2 class="wd-section-title text-slate-900 dark:text-white">Our App Development Process</h2>
            <p class="wd-section-sub text-slate-500 dark:text-slate-400">A transparent process to turn your ideas into powerful digital products.</p>
          </div>
          
          <div class="wd-process-grid">
            <div v-for="(step, i) in process" :key="step.title" class="wd-process-item">
              <div data-rev :data-delay="(i * 0.08).toFixed(2)" class="wd-process-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
                <div class="wd-process-card__icon-wrap bg-indigo-500/10 dark:bg-indigo-500/20">
                  <!-- Discovery & Planning Icon -->
                  <svg v-if="i === 0" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m4-3H8"/>
                  </svg>
                  <!-- UI/UX Design Icon -->
                  <svg v-else-if="i === 1" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <!-- Development Icon -->
                  <svg v-else-if="i === 2" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                  </svg>
                  <!-- Testing & QA Icon -->
                  <svg v-else-if="i === 3" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <!-- Deployment Icon -->
                  <svg v-else-if="i === 4" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13h2v2h-2v-2zm0-4h2v2h-2V9z"/>
                  </svg>
                  <!-- Support & Maintenance Icon -->
                  <svg v-else-if="i === 5" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <h3 class="wd-process-card__title text-slate-900 dark:text-white">{{ step.num }}</h3>
                <p class="wd-process-card__desc text-slate-500 dark:text-slate-400">{{ step.desc }}</p>
              </div>
              
              <!-- Connection arrow -->
              <div v-if="i < process.length - 1" class="wd-process-arrow bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           SECTION 3: TECHNOLOGIES & TOOLS
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-slate-50 dark:bg-[#070b14] border-b border-slate-100 dark:border-slate-800/60">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-10">
            <h2 class="wd-section-title text-slate-900 dark:text-white">Technologies &amp; Tools We Use</h2>
            <p class="wd-section-sub text-slate-500 dark:text-slate-400">We use the latest technologies and industry-leading tools to build robust and future-ready applications.</p>
          </div>

          <!-- Tabs Switcher -->
          <div data-rev class="wd-tabs-wrapper mb-10">
            <button v-for="tab in tabs" :key="tab"
              @click="activeTab = tab"
              class="wd-tab-btn bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400"
              :class="activeTab === tab ? 'wd-tab-btn--active bg-indigo-600 border-indigo-600 text-white' : ''">
              {{ tab }}
            </button>
          </div>

          <!-- Tech Cards Grid -->
          <div class="wd-tech-grid">
            <div v-for="(t, i) in techData[activeTab]" :key="t.name"
              data-rev :data-delay="(i * 0.03).toFixed(2)"
              class="wd-tech-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
              <div class="wd-tech-card__logo" v-html="getLogoSvg(t.name)"></div>
              <div class="wd-tech-card__name text-slate-700 dark:text-slate-300">{{ t.name }}</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           SECTION 4: MOBILE APP FRAMEWORKS
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-white dark:bg-[#0b0f19] border-b border-slate-100 dark:border-slate-800/60">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-12">
            <h2 class="wd-section-title text-slate-900 dark:text-white">Mobile App Frameworks (All Types)</h2>
            <p class="wd-section-sub text-slate-500 dark:text-slate-400">We build Native, Hybrid, Cross-Platform &amp; Progressive Web Apps for every business need.</p>
          </div>

          <div class="wd-frameworks-grid">
            <!-- Native -->
            <div data-rev data-delay="0.0" class="wd-framework-category-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
              <h3 class="wd-fw-card-title text-slate-900 dark:text-white border-slate-100 dark:border-slate-800">Native Development</h3>
              <div class="wd-fw-card-items">
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('Swift')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Swift (iOS)</span>
                </div>
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('Kotlin')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Kotlin (Android)</span>
                </div>
              </div>
            </div>

            <!-- Cross-Platform -->
            <div data-rev data-delay="0.1" class="wd-framework-category-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
              <h3 class="wd-fw-card-title text-slate-900 dark:text-white border-slate-100 dark:border-slate-800">Cross-Platform</h3>
              <div class="wd-fw-card-items">
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('Flutter')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Flutter</span>
                </div>
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('React Native')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">React Native</span>
                </div>
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('Xamarin')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Xamarin</span>
                </div>
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('.NET MAUI')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">.NET MAUI</span>
                </div>
              </div>
            </div>

            <!-- Hybrid -->
            <div data-rev data-delay="0.2" class="wd-framework-category-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
              <h3 class="wd-fw-card-title text-slate-900 dark:text-white border-slate-100 dark:border-slate-800">Hybrid Development</h3>
              <div class="wd-fw-card-items">
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('Ionic')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Ionic</span>
                </div>
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('Apache Cordova')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Apache Cordova</span>
                </div>
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap" v-html="getLogoSvg('PhoneGap')"></span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">PhoneGap</span>
                </div>
              </div>
            </div>

            <!-- PWA -->
            <div data-rev data-delay="0.3" class="wd-framework-category-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
              <h3 class="wd-fw-card-title text-slate-900 dark:text-white border-slate-100 dark:border-slate-800">PWA Development</h3>
              <div class="wd-fw-card-items">
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap">
                    <svg class="w-9 h-9 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                  </span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Progressive Web Apps</span>
                </div>
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap">
                    <svg class="w-9 h-9 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                  </span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Service Workers</span>
                </div>
                <div class="wd-fw-item">
                  <span class="wd-fw-logo-wrap">
                    <svg class="w-9 h-9 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  </span>
                  <span class="wd-fw-name text-slate-700 dark:text-slate-300">Web App Manifest</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           SECTION 5: DATABASES & DEVOPS
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-slate-50 dark:bg-[#070b14] border-b border-slate-100 dark:border-slate-800/60">
        <div class="wd-container">
          <div class="wd-two-col-grid">
            
            <!-- Column 1: Databases -->
            <div data-rev data-delay="0.0" class="wd-col-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
              <h2 class="wd-col-card__title text-slate-900 dark:text-white">Databases &amp; Backend</h2>
              <p class="wd-col-card__sub text-slate-500 dark:text-slate-400 mb-8">Powerful databases and backend technologies for secure &amp; scalable apps.</p>
              <div class="wd-sub-tech-grid">
                <div v-for="t in ['Firebase', 'MySQL', 'PostgreSQL', 'MongoDB', 'SQLite', 'Realm', 'Supabase', 'AWS Amplify']" :key="t"
                  class="wd-tech-card-sub bg-white dark:bg-slate-950 border-slate-100 dark:border-slate-800/60">
                  <div class="wd-tech-card__logo" v-html="getLogoSvg(t)"></div>
                  <div class="wd-tech-card__name text-slate-600 dark:text-slate-400 text-xs">{{ t }}</div>
                </div>
              </div>
            </div>

            <!-- Column 2: DevOps -->
            <div data-rev data-delay="0.15" class="wd-col-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
              <h2 class="wd-col-card__title text-slate-900 dark:text-white">DevOps &amp; Cloud Platforms</h2>
              <p class="wd-col-card__sub text-slate-500 dark:text-slate-400 mb-8">We leverage cloud and DevOps to ensure scalability, security &amp; reliability.</p>
              <div class="wd-sub-tech-grid">
                <div v-for="t in ['AWS', 'Google Cloud', 'Microsoft Azure', 'Docker', 'Kubernetes', 'CI/CD', 'GitHub Actions', 'Bitrise']" :key="t"
                  class="wd-tech-card-sub bg-white dark:bg-slate-950 border-slate-100 dark:border-slate-800/60">
                  <div class="wd-tech-card__logo" v-html="getLogoSvg(t)"></div>
                  <div class="wd-tech-card__name text-slate-600 dark:text-slate-400 text-xs">{{ t }}</div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           SECTION 6: WHAT YOU GET
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-white dark:bg-[#0b0f19] border-b border-slate-100 dark:border-slate-800/60">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-12 text-center">
            <h2 class="wd-section-title text-slate-900 dark:text-white">What You Get With Our App Development Services</h2>
          </div>
          
          <div class="wd-wyg-grid">
            <div v-for="(item, i) in whatYouGet" :key="item.title"
              data-rev :data-delay="(i * 0.05).toFixed(2)"
              class="wd-wyg-card bg-white dark:bg-slate-900 border-slate-150 dark:border-slate-800/80">
              <div class="wd-wyg-card__icon-bg bg-indigo-500/10 dark:bg-indigo-500/20">
                <!-- Custom app solutions icon -->
                <svg v-if="i === 0" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <!-- Performance icon -->
                <svg v-else-if="i === 1" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <!-- Architecture icon -->
                <svg v-else-if="i === 2" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <!-- Security icon -->
                <svg v-else-if="i === 3" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <!-- UI/UX icon -->
                <svg v-else-if="i === 4" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <!-- App Store Optimization icon -->
                <svg v-else-if="i === 5" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <!-- Timely delivery icon -->
                <svg v-else-if="i === 6" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <!-- Support icon -->
                <svg v-else-if="i === 7" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
              </div>
              <div class="wd-wyg-card__info text-left">
                <h3 class="wd-wyg-card__title text-slate-900 dark:text-white">{{ item.title }}</h3>
                <p class="wd-wyg-card__desc text-slate-500 dark:text-slate-400">{{ item.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           SECTION 7: ADDITIONAL SERVICES
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-white dark:bg-[#0b0f19]">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-12 text-center">
            <h2 class="wd-section-title text-slate-900 dark:text-white">Additional App Development Services</h2>
          </div>
          
          <div data-rev class="wd-add-badge-list">
            <div v-for="(s, i) in addServices" :key="s.label" class="wd-add-badge bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-300 hover:bg-indigo-600 hover:text-white hover:border-indigo-600">
              <span class="wd-add-badge__icon-bg bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                <!-- Enterprise icon -->
                <svg v-if="i === 0" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <!-- Wearable icon -->
                <svg v-else-if="i === 1" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <!-- API icon -->
                <svg v-else-if="i === 2" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <!-- IoT icon -->
                <svg v-else-if="i === 3" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                <!-- Chatbot icon -->
                <svg v-else-if="i === 4" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <!-- Support icon -->
                <svg v-else-if="i === 5" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </span>
              <span>{{ s.label }}</span>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           SECTION 8: CTA BANNER
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-slate-50 dark:bg-[#070b14] wd-section-cta">
        <div class="wd-container">
          <div data-rev class="wd-cta-banner">
            <!-- Shifting Gradient Background overlay inside layout -->
            <div class="wd-cta-banner__device-bg">
              <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="55" y="30" width="90" height="150" rx="15" stroke="rgba(255,255,255,0.06)" stroke-width="2"/>
                <circle cx="100" cy="42" r="4" fill="rgba(255,255,255,0.06)"/>
                <line x1="80" y1="170" x2="120" y2="170" stroke="rgba(255,255,255,0.06)" stroke-width="2"/>
              </svg>
            </div>
            
            <div class="wd-cta-banner__left">
              <h2 class="wd-cta-banner__title text-white">Ready to Build Your Next Big App?</h2>
              <p class="wd-cta-banner__sub text-indigo-100">Let's turn your idea into a high-performance mobile application.</p>
            </div>
            
            <div class="wd-cta-banner__right">
              <Link href="/contact-us" class="wd-cta-btn bg-white hover:bg-slate-100 text-indigo-600">
                <span>Get a Free Quote</span>
                <span class="wd-cta-btn__circle bg-indigo-500/10">
                  <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                  </svg>
                </span>
              </Link>
            </div>
          </div>
        </div>
      </section>

    </div>
  </PublicLayout>
</template>

<style scoped>
/* ── Global Styles & Design System ───────────────────────── */
.wd-page {
  font-family: 'Inter', system-ui, sans-serif;
  color: #1e293b;
  overflow-x: hidden;
  background-color: #ffffff;
}

.wd-container {
  width: 100%;
  max-width: 1320px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 20px;
  padding-right: 20px;
}

.wd-section {
  padding-top: 56px;
  padding-bottom: 56px;
}
@media (min-width: 768px) {
  .wd-section {
    padding-top: 88px;
    padding-bottom: 88px;
  }
}

.wd-section-header {
  text-align: center;
}
.wd-section-title {
  font-size: 28px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.2;
  letter-spacing: -0.025em;
}
@media (min-width: 768px) {
  .wd-section-title {
    font-size: 38px;
  }
}
.wd-section-sub {
  font-size: 15px;
  color: #64748b;
  margin-top: 12px;
  max-width: 580px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
}

/* ═══════════════════════════════════════════════════════════
   HERO SECTION
   ═══════════════════════════════════════════════════════════ */
.wd-hero {
  background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 100%);
  position: relative;
  overflow: hidden;
  padding-top: 100px;
  padding-bottom: 56px;
}
@media (min-width: 1024px) {
  .wd-hero {
    padding-top: 140px;
    padding-bottom: 96px;
  }
}

.wd-hero__bg-glow-1 {
  position: absolute;
  top: -150px;
  right: -100px;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, rgba(255,255,255,0) 70%);
  pointer-events: none;
  animation: driftGlow 15s ease-in-out infinite alternate;
}
.wd-hero__bg-glow-2 {
  position: absolute;
  bottom: -100px;
  left: -150px;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99,102,241,0.04) 0%, rgba(255,255,255,0) 70%);
  pointer-events: none;
}
.wd-hero__dots-pattern {
  position: absolute;
  right: 5%;
  top: 20%;
  width: 200px;
  height: 200px;
  background-image: radial-gradient(#6366f112 1.5px, transparent 1.5px);
  background-size: 16px 16px;
  pointer-events: none;
}

/* Breadcrumb */
.wd-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13.5px;
  color: #64748b;
  flex-wrap: wrap;
}
.wd-breadcrumb__link {
  color: #64748b;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}
.wd-breadcrumb__link:hover {
  color: #6366f1;
}
.wd-breadcrumb__sep {
  color: #cbd5e1;
}
.wd-breadcrumb__active {
  color: #0f172a;
  font-weight: 600;
}

/* Hero Pill Badge */
.wd-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: rgba(99, 102, 241, 0.07);
  border: 1px solid rgba(99, 102, 241, 0.15);
  color: #6366f1;
  font-size: 13.5px;
  font-weight: 600;
  padding: 6px 16px;
  border-radius: 100px;
}

/* Typography scale */
.wd-hero__heading {
  font-size: 34px;
  font-weight: 800;
  line-height: 1.15;
  color: #0f172a;
  letter-spacing: -0.03em;
}
@media (min-width: 768px) {
  .wd-hero__heading {
    font-size: 52px;
  }
}
@media (min-width: 1280px) {
  .wd-hero__heading {
    font-size: 60px;
  }
}

.wd-hero__para {
  font-size: 15px;
  line-height: 1.7;
  color: #64748b;
  max-width: 520px;
}
@media (min-width: 768px) {
  .wd-hero__para {
    font-size: 16px;
  }
}

/* Features List */
.wd-features-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  width: 100%;
}
@media (max-width: 480px) {
  .wd-features-row {
    grid-template-columns: 1fr;
  }
}
.wd-feature-item {
  display: flex;
  align-items: center;
  gap: 10px;
}
.wd-feature-item__icon-bg {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.07);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.wd-feature-item__label {
  font-size: 14.5px;
  font-weight: 600;
  color: #334155;
}

/* Hero CTA Buttons */
.wd-hero__btns {
  display: flex;
  flex-wrap: wrap;
  gap: 14px;
  width: 100%;
}
.wd-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  font-size: 14.5px;
  font-weight: 700;
  padding: 13px 24px;
  border-radius: 14px;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  text-decoration: none;
}
.wd-btn--primary {
  background-color: #6366f1;
  color: #ffffff;
  box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
}
.wd-btn--primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(99, 102, 241, 0.35);
}
.wd-btn--outline {
  background-color: #ffffff;
  color: #6366f1;
  border: 1.5px solid rgba(99, 102, 241, 0.15);
}
.wd-btn--outline:hover {
  background-color: rgba(99, 102, 241, 0.03);
  border-color: #6366f1;
  transform: translateY(-2px);
}
.wd-btn__circle {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Hero Grid responsive layout */
.wd-hero__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 48px;
  align-items: center;
}
@media (min-width: 1024px) {
  .wd-hero__grid {
    grid-template-columns: 48% 52%;
  }
}

/* Mockups container */
.wd-hero__right {
  display: flex;
  justify-content: center;
  width: 100%;
}
.wd-mockup-wrap {
  position: relative;
  width: 100%;
  max-width: 480px;
  aspect-ratio: 4/3;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Diagonal Phones Styling */
.wd-diagonal-phone {
  position: absolute;
  width: 42%;
  aspect-ratio: 9/19;
  z-index: 2;
}
.wd-diagonal-phone--left {
  left: 14%;
  top: 8%;
  transform: rotate(-10deg);
  animation: floatPhoneLeft 7s ease-in-out infinite;
}
.wd-diagonal-phone--right {
  right: 18%;
  top: 14%;
  transform: rotate(8deg);
  animation: floatPhoneRight 8s ease-in-out infinite;
}

.wd-phone-outer {
  border-radius: 20px;
  padding: 4px;
  border: 1.5px solid #f1f5f9;
  box-shadow: 0 25px 60px -15px rgba(0,0,0,0.15);
  height: 100%;
}
.wd-phone-inner {
  border-radius: 17px;
  overflow: hidden;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.wd-phone-status-bar {
  height: 14px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 10px;
  font-size: 5px;
  font-weight: 600;
  position: relative;
  z-index: 10;
}
.wd-phone-notch {
  width: 25px;
  height: 5px;
  border-radius: 0 0 4px 4px;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  top: 0;
}

.wd-phone-app-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* Floating Badges */
.wd-glass-badge {
  position: absolute;
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 12px;
  padding: 8px 12px;
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
  display: flex;
  align-items: center;
  gap: 10px;
  z-index: 4;
  text-align: left;
  animation: floatGlassBadge 5s ease-in-out infinite alternate;
}
.wd-glass-badge__icon {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.07);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.wd-glass-badge__title {
  font-size: 10px;
  font-weight: 700;
  color: #0f172a;
  white-space: nowrap;
}
.wd-glass-badge__desc {
  font-size: 8px;
  color: #64748b;
  margin-top: 1px;
  white-space: nowrap;
}

.wd-glass-badge--top-right {
  top: 6%;
  right: -2%;
}
.wd-glass-badge--bottom-center {
  bottom: 6%;
  left: 12%;
}
.wd-glass-badge--bottom-right {
  bottom: 12%;
  right: 0%;
}

/* Animations */
@keyframes driftGlow {
  0% { transform: translate(0, 0) scale(1); }
  100% { transform: translate(30px, -20px) scale(1.05); }
}
@keyframes floatPhoneLeft {
  0%, 100% { transform: rotate(-10deg) translateY(0); }
  50% { transform: rotate(-10deg) translateY(-15px); }
}
@keyframes floatPhoneRight {
  0%, 100% { transform: rotate(8deg) translateY(0); }
  50% { transform: rotate(8deg) translateY(15px); }
}
@keyframes floatGlassBadge {
  0% { transform: translateY(0); }
  100% { transform: translateY(-6px); }
}

/* ═══════════════════════════════════════════════════════════
   PROCESS SECTION
   ═══════════════════════════════════════════════════════════ */
.wd-process-grid {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
@media (min-width: 1024px) {
  .wd-process-grid {
    flex-direction: row;
    gap: 0;
  }
}

.wd-process-item {
  flex: 1;
  display: flex;
  align-items: center;
  position: relative;
}
.wd-process-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  text-align: center;
  width: 100%;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.wd-process-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 50px rgba(99,102,241,0.15);
  border-color: rgba(99,102,241,0.2);
}
.wd-process-card__icon-wrap {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background-color: rgba(99, 102, 241, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
  transition: transform 0.25s;
}
.wd-process-card:hover .wd-process-card__icon-wrap {
  transform: scale(1.1);
}
.wd-process-card__title {
  font-size: 15.5px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}
.wd-process-card__desc {
  font-size: 12.5px;
  color: #64748b;
  line-height: 1.6;
}

.wd-process-arrow {
  display: none;
  position: absolute;
  right: -13px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 2;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background-color: #ffffff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.06);
  border: 1px solid #f1f5f9;
  align-items: center;
  justify-content: center;
}
@media (min-width: 1024px) {
  .wd-process-arrow {
    display: flex;
  }
}

/* ═══════════════════════════════════════════════════════════
   TECHNOLOGIES & TOOLS SECTION
   ═══════════════════════════════════════════════════════════ */
.wd-tabs-wrapper {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  justify-content: center;
  width: 100%;
}
.wd-tab-btn {
  font-size: 13.5px;
  font-weight: 600;
  padding: 8px 18px;
  border-radius: 100px;
  background-color: #ffffff;
  color: #64748b;
  border: 1.5px solid #e2e8f0;
  cursor: pointer;
  transition: all 0.2s;
}
.wd-tab-btn:hover {
  border-color: #6366f1;
  color: #6366f1;
}
.wd-tab-btn--active {
  background-color: #6366f1;
  color: #ffffff !important;
  border-color: #6366f1;
  box-shadow: 0 8px 20px rgba(99, 102, 241, 0.25);
}

.wd-tech-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  width: 100%;
}
@media (min-width: 480px) {
  .wd-tech-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 768px) {
  .wd-tech-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
@media (min-width: 1024px) {
  .wd-tech-grid {
    grid-template-columns: repeat(6, 1fr);
  }
}

.wd-tech-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 18px;
  padding: 20px 12px;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.02);
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.wd-tech-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(99,102,241,0.15);
  border-color: rgba(99,102,241,0.2);
}
.wd-tech-card__logo {
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 12px;
  transition: transform 0.2s;
}
.wd-tech-card:hover .wd-tech-card__logo {
  transform: scale(1.1);
}
.wd-tech-card__name {
  font-size: 13.5px;
  font-weight: 700;
  color: #334155;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 4: MOBILE APP FRAMEWORKS
   ═══════════════════════════════════════════════════════════ */
.wd-frameworks-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  width: 100%;
}
@media (min-width: 768px) {
  .wd-frameworks-grid {
    grid-template-columns: 1fr 1fr;
  }
}
@media (min-width: 1024px) {
  .wd-frameworks-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.wd-framework-category-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.02);
  text-align: left;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.wd-framework-category-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 35px rgba(99,102,241,0.12);
  border-color: rgba(99,102,241,0.2);
}
.wd-fw-card-title {
  font-size: 15.5px;
  font-weight: 700;
  color: #0f172a;
  border-bottom: 1.5px solid #f1f5f9;
  padding-bottom: 12px;
  margin-bottom: 16px;
}
.wd-fw-card-items {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.wd-fw-item {
  display: flex;
  align-items: center;
  gap: 12px;
}
.wd-fw-logo-wrap {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.wd-fw-name {
  font-size: 13.5px;
  font-weight: 600;
  color: #334155;
}

.wd-col-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  text-align: left;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.wd-col-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 45px rgba(99,102,241,0.12);
  border-color: rgba(99,102,241,0.18);
}
.wd-col-card__title {
  font-size: 20px;
  font-weight: 800;
  color: #0f172a;
}
.wd-col-card__sub {
  font-size: 13.5px;
  color: #64748b;
  margin-top: 6px;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 5: DATABASES & DEVOPS
   ═══════════════════════════════════════════════════════════ */
.wd-two-col-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
  width: 100%;
}
@media (min-width: 1024px) {
  .wd-two-col-grid {
    grid-template-columns: 1fr 1fr;
  }
}

.wd-sub-tech-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}
@media (min-width: 480px) {
  .wd-sub-tech-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.wd-tech-card-sub {
  background-color: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 14px;
  padding: 14px 8px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  display: flex;
  flex-direction: column;
  align-items: center;
}
.wd-tech-card-sub:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(99,102,241,0.12);
  border-color: rgba(99,102,241,0.2);
}

/* ═══════════════════════════════════════════════════════════
   SECTION 6: WHAT YOU GET
   ═══════════════════════════════════════════════════════════ */
.wd-wyg-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
  width: 100%;
}
@media (min-width: 640px) {
  .wd-wyg-grid {
    grid-template-columns: 1fr 1fr;
  }
}
@media (min-width: 1024px) {
  .wd-wyg-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.wd-wyg-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 16px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.02);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.wd-wyg-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 45px rgba(99,102,241,0.12);
  border-color: rgba(99,102,241,0.18);
}
.wd-wyg-card__icon-bg {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background-color: rgba(99, 102, 241, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: transform 0.25s;
}
.wd-wyg-card:hover .wd-wyg-card__icon-bg {
  transform: rotate(5deg) scale(1.05);
}
.wd-wyg-card__info {
  display: flex;
  flex-direction: column;
}
.wd-wyg-card__title {
  font-size: 15px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 6px;
}
.wd-wyg-card__desc {
  font-size: 12.5px;
  color: #64748b;
  line-height: 1.6;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 7: ADDITIONAL SERVICES
   ═══════════════════════════════════════════════════════════ */
.wd-add-badge-list {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  justify-content: center;
  width: 100%;
}
.wd-add-badge {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background-color: #ffffff;
  border: 1.5px solid #e2e8f0;
  border-radius: 100px;
  padding: 10px 20px;
  font-size: 14px;
  font-weight: 700;
  color: #334155;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.wd-add-badge:hover {
  background-color: #6366f1;
  color: #ffffff;
  border-color: #6366f1;
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
}
.wd-add-badge__icon-bg {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.wd-add-badge:hover .wd-add-badge__icon-bg {
  background-color: rgba(255, 255, 255, 0.15);
}
.wd-add-badge:hover .wd-add-badge__icon-bg svg {
  color: #ffffff;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 8: CTA BANNER
   ═══════════════════════════════════════════════════════════ */
.wd-section-cta {
  padding-bottom: 60px;
}
.wd-cta-banner {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
  background-size: 200% 200%;
  border-radius: 24px;
  padding: 40px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 24px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 20px 45px rgba(99, 102, 241, 0.25);
  animation: shiftCtaGradient 12s ease infinite alternate;
}
@media (min-width: 768px) {
  .wd-cta-banner {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 48px;
  }
}

.wd-cta-banner__device-bg {
  position: absolute;
  right: 4%;
  bottom: -20px;
  width: 140px;
  opacity: 0.08;
  pointer-events: none;
}
@media (max-width: 768px) {
  .wd-cta-banner__device-bg {
    display: none;
  }
}

.wd-cta-banner__left {
  position: relative;
  z-index: 2;
  flex: 1;
}
.wd-cta-banner__title {
  font-size: 24px;
  font-weight: 800;
  color: #ffffff;
  line-height: 1.25;
  margin-bottom: 8px;
}
@media (min-width: 768px) {
  .wd-cta-banner__title {
    font-size: 28px;
  }
}
.wd-cta-banner__sub {
  font-size: 14.5px;
  color: rgba(255, 255, 255, 0.85);
  line-height: 1.5;
}

.wd-cta-banner__right {
  position: relative;
  z-index: 2;
  flex-shrink: 0;
}
.wd-cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  background-color: #ffffff;
  color: #6366f1;
  font-size: 14.5px;
  font-weight: 700;
  padding: 13px 26px;
  border-radius: 14px;
  text-decoration: none;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.wd-cta-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.wd-cta-btn__circle {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
}

@keyframes shiftCtaGradient {
  0% { background-position: 0% 50%; }
  100% { background-position: 100% 50%; }
}
</style>
