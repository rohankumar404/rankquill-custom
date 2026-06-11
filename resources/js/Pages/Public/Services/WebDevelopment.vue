<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({ service: { type: Object, required: true } })

const activeTab = ref('Frontend')
const tabs = ['Frontend', 'Backend', 'Databases', 'DevOps & Cloud', 'Tools & Others']

const techData = {
  Frontend: [
    { name: 'HTML5', bg: '#E34F26' },
    { name: 'CSS3', bg: '#1572B6' },
    { name: 'JavaScript', bg: '#F7DF1E' },
    { name: 'TypeScript', bg: '#3178C6' },
    { name: 'React.js', bg: '#61DAFB' },
    { name: 'Next.js', bg: '#000000' },
    { name: 'Vue.js', bg: '#42B883' },
    { name: 'Angular', bg: '#DD0031' },
    { name: 'Svelte', bg: '#FF3E00' },
    { name: 'Tailwind CSS', bg: '#38BDF8' },
    { name: 'Bootstrap', bg: '#7952B3' },
    { name: 'jQuery', bg: '#0769AD' },
  ],
  Backend: [
    { name: 'Node.js', bg: '#339933' },
    { name: 'Laravel', bg: '#FF2D20' },
    { name: 'Django', bg: '#092E20' },
    { name: 'Express', bg: '#000000' },
    { name: 'FastAPI', bg: '#009688' },
    { name: 'Rails', bg: '#CC0000' },
    { name: 'Spring Boot', bg: '#6DB33F' },
    { name: 'ASP.NET', bg: '#512BD4' },
    { name: 'Go', bg: '#00ADD8' },
    { name: 'GraphQL', bg: '#E10098' },
    { name: 'REST API', bg: '#6366f1' },
    { name: 'Socket.io', bg: '#010101' },
  ],
  Databases: [
    { name: 'MySQL', bg: '#4479A1' },
    { name: 'PostgreSQL', bg: '#336791' },
    { name: 'MongoDB', bg: '#47A248' },
    { name: 'Redis', bg: '#DC382D' },
    { name: 'SQLite', bg: '#003B57' },
    { name: 'Firebase', bg: '#FFCA28' },
    { name: 'Supabase', bg: '#3ECF8E' },
    { name: 'DynamoDB', bg: '#527FFF' },
    { name: 'Elasticsearch', bg: '#005571' },
    { name: 'Cassandra', bg: '#1287B1' },
    { name: 'MariaDB', bg: '#003545' },
    { name: 'Neo4j', bg: '#008CC1' },
  ],
  'DevOps & Cloud': [
    { name: 'AWS', bg: '#FF9900' },
    { name: 'GCP', bg: '#4285F4' },
    { name: 'Azure', bg: '#0089D6' },
    { name: 'Docker', bg: '#2496ED' },
    { name: 'Kubernetes', bg: '#326CE5' },
    { name: 'GitHub Actions', bg: '#2088FF' },
    { name: 'Terraform', bg: '#7B42BC' },
    { name: 'Nginx', bg: '#009639' },
    { name: 'Cloudflare', bg: '#F38020' },
    { name: 'Vercel', bg: '#000000' },
    { name: 'Netlify', bg: '#00C7B7' },
    { name: 'Linux', bg: '#FCC624' },
  ],
  'Tools & Others': [
    { name: 'Figma', bg: '#F24E1E' },
    { name: 'Git', bg: '#F05032' },
    { name: 'VS Code', bg: '#007ACC' },
    { name: 'Postman', bg: '#FF6C37' },
    { name: 'Jira', bg: '#0052CC' },
    { name: 'Slack', bg: '#4A154B' },
    { name: 'Notion', bg: '#000000' },
    { name: 'Sentry', bg: '#362D59' },
    { name: 'Jest', bg: '#C21325' },
    { name: 'Webpack', bg: '#8DD6F9' },
    { name: 'Vite', bg: '#646CFF' },
    { name: 'ESLint', bg: '#4B32C3' },
  ],
}

const cmsData = [
  { name: 'WordPress', bg: '#21759B' },
  { name: 'Shopify', bg: '#96BF48' },
  { name: 'Webflow', bg: '#4353FF' },
  { name: 'Wix', bg: '#000000' },
  { name: 'Squarespace', bg: '#000000' },
  { name: 'Joomla', bg: '#F44321' },
  { name: 'Drupal', bg: '#0678BE' },
  { name: 'Magento', bg: '#EE672F' },
  { name: 'PrestaShop', bg: '#DF0067' },
  { name: 'OpenCart', bg: '#43A8E0' },
  { name: 'Strapi', bg: '#4945FF' },
  { name: 'Contentful', bg: '#2478CC' },
]

// Custom Brand SVGs
const svgLogos = {
  // Frontend
  'HTML5': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#E34F26"><path d="M1.5 0h21l-1.9 21.2L12 24l-8.6-2.8L1.5 0zm16.5 6H7l.3 3.3h10.4l-.5 5.5-4.7 1.3-4.7-1.3-.3-3.1h-3l.5 5.9 7.5 2.1 7.5-2.1 1-11.6H18z"/></svg>`,
  'CSS3': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#1572B6"><path d="M1.5 0h21l-1.9 21.2L12 24l-8.6-2.8L1.5 0zm16.5 6h-11l.3 3.3h10.4l-.5 5.5-4.7 1.3-4.7-1.3-.3-3.1H8l-.3-3.3h10.4l.6-5.7z"/></svg>`,
  'JavaScript': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#F7DF1E"><rect width="24" height="24" rx="4"/><text x="20" y="20" font-family="system-ui" font-weight="900" font-size="10.5" fill="#000" text-anchor="end">JS</text></svg>`,
  'TypeScript': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3178C6"><rect width="24" height="24" rx="4"/><text x="20" y="20" font-family="system-ui" font-weight="900" font-size="10" fill="#fff" text-anchor="end">TS</text></svg>`,
  'React.js': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="#61DAFB" stroke-width="2"><ellipse cx="12" cy="12" rx="3" ry="9" transform="rotate(30 12 12)"/><ellipse cx="12" cy="12" rx="3" ry="9" transform="rotate(90 12 12)"/><ellipse cx="12" cy="12" rx="3" ry="9" transform="rotate(150 12 12)"/><circle cx="12" cy="12" r="1.5" fill="#61DAFB"/></svg>`,
  'Next.js': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#000"><circle cx="12" cy="12" r="12" fill="#000"/><path d="M7.5 17.5V6.5h2l6.5 7.5V6.5h1.5v11h-2L9 9.5v8H7.5z" fill="#fff"/></svg>`,
  'Vue.js': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 21L22 4h-4.5L12 14 6.5 4H2l10 17z" fill="#42B883"/><path d="M12 21L18.5 4h-3.5L12 10 9 4H5.5l6.5 17z" fill="#35495E"/></svg>`,
  'Angular': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#DD0031"><path d="M12 1.5L21.5 5l-1.5 13.5L12 23.5l-8-5L2.5 5 12 1.5z"/><path d="M12 3.5L4.5 17h3l1.5-3.5h6l1.5 3.5h3L12 3.5zm2 7.5h-4l2-4.5 2 4.5z" fill="#fff"/></svg>`,
  'Svelte': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF3E00"><path d="M19 8.5c0-3.6-2.9-6.5-6.5-6.5-2.2 0-4.1 1.1-5.3 2.8L4.6 3.2C3.1 4.7 2.2 6.8 2.2 9.2c0 2.2.8 4.2 2.2 5.7l1.6-.9c.4-3.1 3-5.5 6.2-5.5 3.5 0 6.3 2.8 6.3 6.3s-2.8 6.3-6.3 6.3c-2.4 0-4.5-1.3-5.6-3.2L5 19.3c1.5 1.7 3.7 2.7 6.2 2.7 4.6 0 8.3-3.7 8.3-8.3 0-1.8-.6-3.4-1.6-4.7l1.1-.5z"/></svg>`,
  'Tailwind CSS': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#06B6D4"><path d="M12 3c-2.8 0-4.8 1.6-6 4.8 1.2-1.6 2.6-2.4 4.2-2.4 2.4 0 4 1.2 4.8 3.6.8 2.4.2 4.4-1.8 6-2 1.6-4 2.4-6 2.4-1.2 0-2.2-.4-3-1.2 1 1.6 2.4 2.4 4.2 2.4 2.8 0 4.8-1.6 6-4.8-1.2 1.6-2.6 2.4-4.2 2.4-2.4 0-4-1.2-4.8-3.6-.8-2.4-.2-4.4 1.8-6 2-1.6 4-2.4 6-2.4z"/></svg>`,
  'Bootstrap': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#7952B3"><rect width="24" height="24" rx="5"/><text x="12" y="17" font-family="system-ui" font-weight="900" font-size="14.5" fill="#fff" text-anchor="middle">B</text></svg>`,
  'jQuery': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#0769AD"><rect width="24" height="24" rx="4"/><text x="12" y="15" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">jQuery</text></svg>`,

  // Backend
  'Node.js': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#339933"><path d="M12 1L3 6.2v10.5L12 22l9-5.3V6.2L12 1zm7 14.5l-7 4.1-7-4.1V7.3l7-4.1 7 4.1v8.2z"/></svg>`,
  'Laravel': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF2D20"><path d="M20 3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 13H9v-2h6v2zm3-4H6v-2h12v2zm0-4H6V6h12v2z"/></svg>`,
  'Django': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#092E20"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="11" fill="#fff" text-anchor="middle">dj</text></svg>`,
  'Express': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#000"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">Express</text></svg>`,
  'FastAPI': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#009688"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">Fast</text></svg>`,
  'Rails': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#CC0000"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8.5" fill="#fff" text-anchor="middle">Rails</text></svg>`,
  'Spring Boot': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#6DB33F"><circle cx="12" cy="12" r="11" fill="none" stroke="#6DB33F" stroke-width="2"/><path d="M12 6.5c-3 0-5.5 2.5-5.5 5.5s2.5 5.5 5.5 5.5 5.5-2.5 5.5-5.5S15 6.5 12 6.5zm0 9c-1.9 0-3.5-1.6-3.5-3.5S10.1 8.5 12 8.5s3.5 1.6 3.5 3.5-1.6 3.5-3.5 3.5z"/></svg>`,
  'ASP.NET': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#512BD4"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7" fill="#fff" text-anchor="middle">.NET</text></svg>`,
  'Go': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#00ADD8"><rect width="24" height="24" rx="4"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="12" fill="#fff" text-anchor="middle">Go</text></svg>`,
  'GraphQL': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#E10098"><path d="M12 2L2 7.8v11.4L12 22l10-5.8V7.8L12 2zm8 14.5l-8 4.6-8-4.6V8.7l8-4.6 8 4.6v11.2z"/></svg>`,
  'REST API': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#6366f1"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8.5" fill="#fff" text-anchor="middle">API</text></svg>`,
  'Socket.io': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#010101"><circle cx="12" cy="12" r="12" fill="#010101"/><path d="M12 5.5l4.5 4.5h-3v8.5h-3V10h-3l4.5-4.5z" fill="#fff"/></svg>`,

  // Databases
  'MySQL': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#4479A1"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">MySQL</text></svg>`,
  'PostgreSQL': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#336791"><circle cx="12" cy="12" r="11" fill="none" stroke="#336791" stroke-width="2"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="9" fill="#336791" text-anchor="middle">SQL</text></svg>`,
  'MongoDB': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#47A248"><path d="M12 1.5c-.3 0-6 4.5-6 10.5s2.7 8.5 6 10.5c3.3-2 6-4.5 6-10.5S12.3 1.5 12 1.5zm0 15c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3z"/></svg>`,
  'Redis': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#DC382D"><path d="M12 2L2 7.5 12 13l10-5.5L12 2zm0 8.5L5 7.5 12 4l7 3.5-7 3zM2 12.5l10 5.5 10-5.5v2.5l-10 5.5-10-5.5v-2.5z"/></svg>`,
  'SQLite': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#003B57"><rect width="24" height="24" rx="4"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">Lite</text></svg>`,
  'Firebase': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FFCA28"><path d="M3.9 18.5l9-16.2c.2-.4.8-.4 1 0l2 3.6-12 12.6zm16.2-2.1L16 11l-3 5.4 7.1 2.1z"/></svg>`,
  'Supabase': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#3ECF8E"><path d="M13.5 1.5L4 12.5h7.5V22.5L20 11.5h-6.5z"/></svg>`,
  'DynamoDB': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#527FFF"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9.5" fill="#fff" text-anchor="middle">NoSQL</text></svg>`,
  'Elasticsearch': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#005571"><circle cx="12" cy="12" r="11" fill="none" stroke="#005571" stroke-width="2"/><path d="M12 6.5v11m-5.5-5.5h11" stroke="#005571" stroke-width="2"/></svg>`,
  'Cassandra': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#1287B1"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">CQL</text></svg>`,
  'MariaDB': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#003545"><rect width="24" height="24" rx="4"/><text x="12" y="15" font-family="system-ui" font-weight="900" font-size="7" fill="#fff" text-anchor="middle">Maria</text></svg>`,
  'Neo4j': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#008CC1"><circle cx="12" cy="12" r="11" fill="none" stroke="#008CC1" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="#008CC1"/></svg>`,

  // DevOps & Cloud
  'AWS': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF9900"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="8.5" fill="#fff" text-anchor="middle">AWS</text></svg>`,
  'GCP': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#4285F4"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9.5" fill="#fff" text-anchor="middle">GCP</text></svg>`,
  'Azure': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#0089D6"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">Azure</text></svg>`,
  'Docker': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#2496ED"><path d="M1.5 9h21l-1.9 8.2-8.6 2.8-8.6-2.8L1.5 9z" fill="#2496ED"/><rect x="8" y="4" width="3" height="3" fill="#2496ED"/><rect x="13" y="4" width="3" height="3" fill="#2496ED"/></svg>`,
  'Kubernetes': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#326CE5"><path d="M12 1.5L2.5 7v10l9.5 5.5 9.5-5.5V7L12 1.5zm0 4l6.5 3.8v7.4L12 20.5 5.5 16.7V9.3L12 5.5z"/></svg>`,
  'GitHub Actions': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#2088FF"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">CI/CD</text></svg>`,
  'Terraform': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#7B42BC"><path d="M1.5 6.5l9.5-5.5 9.5 5.5v11l-9.5 5.5-9.5-5.5v-11zm9.5 4.5V3.5L4.5 7.5v7.5l6.5-4z"/></svg>`,
  'Nginx': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#009639"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8.5" fill="#fff" text-anchor="middle">NGINX</text></svg>`,
  'Cloudflare': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#F38020"><path d="M12 4.5C8.4 4.5 5.5 7.4 5.5 11v1.5c-1.9 0-3.5 1.6-3.5 3.5S3.6 20.5 5.5 20.5h13c1.9 0 3.5-1.6 3.5-3.5s-1.6-3.5-3.5-3.5V11c0-3.6-2.9-6.5-6.5-6.5zm4 9.5h-8v-3c0-2.2 1.8-4 4-4s4 1.8 4 4v3z"/></svg>`,
  'Vercel': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#000"><path d="M12 2L1 21h22L12 2z"/></svg>`,
  'Netlify': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#00C7B7"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">Netlify</text></svg>`,
  'Linux': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FCC624"><circle cx="12" cy="12" r="11" fill="none" stroke="#000" stroke-width="2"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="14" fill="#000" text-anchor="middle">🐧</text></svg>`,

  // Tools & Others
  'Figma': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M8.5 12a3.5 3.5 0 110-7h7v7h-7z" fill="#F24E1E"/><path d="M8.5 19a3.5 3.5 0 010-7h3.5v3.5A3.5 3.5 0 018.5 19z" fill="#0ACF83"/><circle cx="15.5" cy="8.5" r="3.5" fill="#FF7262"/><circle cx="15.5" cy="15.5" r="3.5" fill="#1ABCFE"/></svg>`,
  'Git': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#F05032"><path d="M22.6 11.3L12.7 1.4c-.4-.4-1-.4-1.4 0L1.4 11.3c-.4.4-.4 1 0 1.4l9.9 9.9c.4.4 1 .4 1.4 0l9.9-9.9c.4-.4.4-1 0-1.4zM12 18c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3z"/></svg>`,
  'VS Code': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#007ACC"><path d="M22 6.5L14.5 1 2 12l12.5 11 7.5-5.5V6.5zm-4.5 8l-3-2.5 3-2.5v5z"/></svg>`,
  'Postman': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF6C37"><path d="M12 1C5.9 1 1 5.9 1 12s4.9 11 11 11 11-4.9 11-11S18.1 1 12 1zm3 14.5l-3-3-3 3v-7.5l3 3 3-3v7.5z"/></svg>`,
  'Jira': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#0052CC"><rect width="24" height="24" rx="4"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="12" fill="#fff" text-anchor="middle">J</text></svg>`,
  'Slack': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#4A154B"><rect width="24" height="24" rx="4"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="12" fill="#fff" text-anchor="middle">S</text></svg>`,
  'Notion': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#000"><rect width="24" height="24" rx="4"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="13" fill="#fff" text-anchor="middle">N</text></svg>`,
  'Sentry': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#362D59"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">Sentry</text></svg>`,
  'Jest': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#C21325"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">Jest</text></svg>`,
  'Webpack': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#8DD6F9"><path d="M12 1L2 6.8v10.5L12 23l10-5.8V6.8L12 1zm0 4.1l6.5 3.8-6.5 3.7L5.5 8.9 12 5.1zM5.5 11.2l5.5 3.2v6.2l-5.5-3.2v-6.2zm13 0v6.2l-5.5 3.2v-6.2l5.5-3.2z"/></svg>`,
  'Vite': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 19.5h20L12 2z" fill="#646CFF"/><path d="M12.5 6.5l-5 9.5h4.5v6.5l6-10.5H12.5z" fill="#FFC517"/></svg>`,
  'ESLint': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#4B32C3"><rect width="24" height="24" rx="4"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9" fill="#fff" text-anchor="middle">Lint</text></svg>`,

  // CMS & Platforms
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
  'Contentful': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#2478CC"><rect width="24" height="24" rx="4"/><text x="12" y="15.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#fff" text-anchor="middle">content</text></svg>`,
}

const getLogoSvg = (name) => {
  return svgLogos[name] || `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#6B7280"><rect width="24" height="24" rx="4"/><text x="12" y="15" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">${name.slice(0,3)}</text></svg>`
}

const process = [
  { num: '1. Discover & Plan', title: 'Discover & Plan', desc: 'We understand your goals, research your market, and plan the best strategy.' },
  { num: '2. Design & Prototype', title: 'Design & Prototype', desc: 'UI/UX designs that are modern, intuitive, and focused on conversions.' },
  { num: '3. Development', title: 'Development', desc: 'Clean, efficient, and scalable code using the latest technologies.' },
  { num: '4. Testing & QA', title: 'Testing & QA', desc: 'Rigorous testing for performance, security, and cross-browser compatibility.' },
  { num: '5. Launch & Support', title: 'Launch & Support', desc: 'Smooth deployment and ongoing support to keep your website running perfectly.' },
]

const whatYouGet = [
  { title: 'Fully Responsive Design', desc: 'Looks perfect on every device and screen size.' },
  { title: 'SEO Friendly Structure', desc: 'Built with clean code and best SEO practices.' },
  { title: 'Lightning Fast Performance', desc: 'Optimized for speed and better user experience.' },
  { title: 'Secure & Reliable', desc: 'Security-first approach to protect your website.' },
  { title: 'Scalable & Future Ready', desc: 'Built to grow with your business needs.' },
  { title: 'Cross-Browser Compatible', desc: 'Works seamlessly across all modern browsers.' },
  { title: 'Easy to Manage (CMS)', desc: 'Update content, images, and pages with ease.' },
  { title: 'Ongoing Support & Maintenance', desc: 'We are always here to keep your site updated.' },
]

const addServices = [
  { label: 'Custom Web Application' },
  { label: 'E-Commerce Development' },
  { label: 'API Development & Integration' },
  { label: 'Website Migration & Upgradation' },
  { label: 'Speed Optimization & Performance' },
  { label: 'Website Maintenance & Support' },
]

const features = [
  { label: 'Modern & Scalable' },
  { label: 'Secure & Reliable' },
  { label: 'SEO-Optimized' },
  { label: 'Performance Focused' },
]

// Scroll Reveal
const observers = []
onMounted(() => {
  document.querySelectorAll('[data-rev]').forEach(el => {
    const delay = parseFloat(el.dataset.delay || 0)
    el.style.opacity = '0'
    el.style.transform = 'translateY(24px)'
    el.style.transition = `opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s, transform 0.65s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s`
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
    <title>Web Development Services — RankQuill</title>
    <meta name="description" content="We build fast, secure, scalable, and SEO-friendly websites that deliver exceptional user experiences and drive real business results." />
  </Head>

  <PublicLayout>
    <div class="wd-page select-none selection:bg-indigo-500 selection:text-white">

      <!-- ═══════════════════════════════════════════════════
           HERO SECTION
      ═══════════════════════════════════════════════════ -->
      <section class="wd-hero">
        <div class="wd-hero__bg-glow-1"></div>
        <div class="wd-hero__bg-glow-2"></div>
        
        <div class="wd-container relative z-10">
          
          <!-- Breadcrumb -->
          <nav class="wd-breadcrumb mb-6 lg:mb-8" aria-label="Breadcrumb">
            <Link href="/" class="wd-breadcrumb__link">Home</Link>
            <span class="wd-breadcrumb__sep">
              <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
            <Link href="/services" class="wd-breadcrumb__link">Services</Link>
            <span class="wd-breadcrumb__sep">
              <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
            <span class="wd-breadcrumb__active">Web Development</span>
          </nav>

          <div class="wd-hero__grid">
            
            <!-- Hero Info Left -->
            <div class="wd-hero__left flex flex-col items-start text-left">
              <div class="wd-pill mb-5">
                <!-- Code icon -->
                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
                Web Development
              </div>

              <h1 class="wd-hero__heading mb-5">
                High-Performance Websites<br/>
                <span class="text-indigo-600">Built for Growth</span>
              </h1>

              <p class="wd-hero__para mb-6">
                We build fast, secure, scalable, and SEO-friendly websites that deliver exceptional user experiences and drive real business results.
              </p>

              <!-- Hero Feature Badges -->
              <div class="wd-features-row mb-8">
                <div v-for="f in features" :key="f.label" class="wd-feature-item">
                  <span class="wd-feature-item__icon-bg">
                    <!-- Icon placeholder replacement with generic check/shield svg -->
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                  </span>
                  <span class="wd-feature-item__label">{{ f.label }}</span>
                </div>
              </div>

              <!-- Hero CTA Buttons -->
              <div class="wd-hero__btns">
                <Link href="/contact-us" class="wd-btn wd-btn--primary">
                  <span>Get a Free Quote</span>
                  <span class="wd-btn__circle">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                  </span>
                </Link>
                <Link href="/portfolio" class="wd-btn wd-btn--outline">
                  <span>View Our Work</span>
                  <span class="wd-btn__circle-play">
                    <svg class="w-3.5 h-3.5 text-indigo-600 fill-indigo-600" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </span>
                </Link>
              </div>
            </div>

            <!-- Hero Mockup Visual Right -->
            <div class="wd-hero__right">
              <div class="wd-mockup-wrap">
                <!-- Laptop Mockup -->
                <div class="wd-laptop">
                  <div class="wd-laptop__shell">
                    <div class="wd-laptop__screen">
                      <!-- Browser bar -->
                      <div class="wd-browser-header">
                        <div class="wd-browser-dots">
                          <span class="wd-browser-dot wd-browser-dot--red"></span>
                          <span class="wd-browser-dot wd-browser-dot--yellow"></span>
                          <span class="wd-browser-dot wd-browser-dot--green"></span>
                        </div>
                        <div class="wd-browser-address">digitallyyours.com</div>
                      </div>
                      
                      <!-- Screen Content (Dashboard website mockup) -->
                      <div class="wd-dashboard-site">
                        <!-- Navigation mockup -->
                        <div class="wd-db-nav">
                          <div class="wd-db-logo">
                            <span class="wd-db-logo-icon">D</span>
                            <span class="wd-db-logo-text">Digitally</span>
                          </div>
                          <div class="wd-db-links">
                            <span>Home</span>
                            <span>About</span>
                            <span>Services</span>
                            <span>Blog</span>
                            <span>Contact</span>
                          </div>
                          <div class="wd-db-cta-btn">Get Started</div>
                        </div>
                        
                        <!-- Hero main banner mockup -->
                        <div class="wd-db-hero">
                          <div class="wd-db-hero-text">
                            <h2>Digital Solutions</h2>
                            <h2 class="text-indigo-600">That Drive Real</h2>
                            <h2>Results</h2>
                          </div>
                          <div class="wd-db-hero-btn">Get Started</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="wd-laptop__hinge"></div>
                  <div class="wd-laptop__base"></div>
                </div>

                <!-- Phone Mockup overlapping -->
                <div class="wd-phone">
                  <div class="wd-phone__body">
                    <div class="wd-phone__screen">
                      <!-- Status/URL bar -->
                      <div class="wd-phone-header">
                        <span>digitallyyours.com</span>
                      </div>
                      <!-- Phone content -->
                      <div class="wd-phone-content">
                        <div class="wd-phone-logo">
                          <span class="wd-phone-logo-icon">D</span>
                          <span class="wd-phone-logo-text">Digitally</span>
                        </div>
                        <h3 class="wd-phone-title">Digital Solutions <span class="text-indigo-600">That Drive</span> Real Results</h3>
                        <div class="wd-phone-btn">Get Started</div>
                        
                        <!-- Simple stats mockup inside phone -->
                        <div class="wd-phone-stats">
                          <div class="wd-phone-stat-item">
                            <span class="wd-phone-stat-val">120+</span>
                            <span class="wd-phone-stat-lbl">Clients</span>
                          </div>
                          <div class="wd-phone-stat-item">
                            <span class="wd-phone-stat-val text-green-500">98%</span>
                            <span class="wd-phone-stat-lbl">Score</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="wd-phone-home-indicator"></div>
                  </div>
                </div>

                <!-- PageSpeed Score Floating Card -->
                <div class="wd-float-badge wd-float-badge--pagespeed">
                  <div class="wd-pagespeed-circle">
                    <svg class="w-11 h-11" viewBox="0 0 36 36">
                      <path class="text-gray-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                      <path class="text-emerald-500" stroke-width="3" stroke-dasharray="98, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                      <text x="18" y="21.5" class="text-[9px] font-black text-emerald-500" text-anchor="middle">98</text>
                    </svg>
                  </div>
                  <div class="wd-float-badge__details">
                    <span class="wd-float-badge__title">PageSpeed Score</span>
                    <span class="wd-float-badge__value text-emerald-500">98/100</span>
                  </div>
                </div>

                <!-- Core Web Vitals Floating Card -->
                <div class="wd-float-badge wd-float-badge--vitals">
                  <span class="wd-vitals-check-bg">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                  </span>
                  <div class="wd-float-badge__details">
                    <span class="wd-float-badge__title">Core Web Vitals</span>
                    <span class="wd-float-badge__value text-emerald-500">Passed</span>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           OUR PROCESS SECTION
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-white">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-12">
            <h2 class="wd-section-title">Our Web Development Process</h2>
            <p class="wd-section-sub">A proven process that ensures quality, transparency, and timely delivery.</p>
          </div>
          
          <div class="wd-process-grid">
            <div v-for="(step, i) in process" :key="step.title" class="wd-process-item">
              <div data-rev :data-delay="(i * 0.1).toFixed(1)" class="wd-process-card">
                <!-- Rounded Blue Background Icon Container -->
                <div class="wd-process-card__icon-wrap">
                  <!-- Discover & Plan Icon -->
                  <svg v-if="i === 0" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m4-3H8"/>
                  </svg>
                  <!-- Design & Prototype Icon -->
                  <svg v-else-if="i === 1" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <!-- Development Icon -->
                  <svg v-else-if="i === 2" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                  </svg>
                  <!-- Testing & QA Icon -->
                  <svg v-else-if="i === 3" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <!-- Launch & Support Icon -->
                  <svg v-else-if="i === 4" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                </div>
                <h3 class="wd-process-card__title">{{ step.num }}</h3>
                <p class="wd-process-card__desc">{{ step.desc }}</p>
              </div>
              
              <!-- Connection arrow (vanishes on mobile) -->
              <div v-if="i < process.length - 1" class="wd-process-arrow">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           TECHNOLOGIES & TOOLS
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-slate-50 border-t border-b border-slate-100">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-10">
            <h2 class="wd-section-title">Technologies &amp; Tools We Use</h2>
            <p class="wd-section-sub">We use the latest and most reliable technologies to build fast, secure, and future-ready websites.</p>
          </div>

          <!-- Tabs Switched Pill Menu -->
          <div data-rev class="wd-tabs-wrapper mb-10">
            <button v-for="tab in tabs" :key="tab"
              @click="activeTab = tab"
              class="wd-tab-btn"
              :class="activeTab === tab ? 'wd-tab-btn--active' : ''">
              {{ tab }}
            </button>
          </div>

          <!-- Tech Cards Grid -->
          <div class="wd-tech-grid">
            <div v-for="(t, i) in techData[activeTab]" :key="t.name"
              data-rev :data-delay="(i * 0.03).toFixed(2)"
              class="wd-tech-card">
              <div class="wd-tech-card__logo" v-html="getLogoSvg(t.name)"></div>
              <div class="wd-tech-card__name">{{ t.name }}</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           CMS & PLATFORMS
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-white">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-12">
            <h2 class="wd-section-title">CMS &amp; Platforms We Work With</h2>
            <p class="wd-section-sub">We build on the world's most reliable CMS and platforms to give you the power of easy management.</p>
          </div>
          
          <div class="wd-cms-grid">
            <div v-for="(c, i) in cmsData" :key="c.name"
              data-rev :data-delay="(i * 0.03).toFixed(2)"
              class="wd-tech-card">
              <div class="wd-tech-card__logo" v-html="getLogoSvg(c.name)"></div>
              <div class="wd-tech-card__name">{{ c.name }}</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           WHAT YOU GET WITH OUR SERVICES
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-slate-50 border-t border-b border-slate-100">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-12 text-center">
            <h2 class="wd-section-title">What You Get With Our Web Development Services</h2>
          </div>
          
          <div class="wd-wyg-grid">
            <div v-for="(item, i) in whatYouGet" :key="item.title"
              data-rev :data-delay="(i * 0.05).toFixed(2)"
              class="wd-wyg-card">
              <!-- Left side Blue Circle SVG Icon -->
              <div class="wd-wyg-card__icon-bg">
                <!-- Responsive Design Icon -->
                <svg v-if="i === 0" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <!-- SEO Friendly Icon -->
                <svg v-else-if="i === 1" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <!-- Lightning Fast Icon -->
                <svg v-else-if="i === 2" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <!-- Secure Icon -->
                <svg v-else-if="i === 3" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <!-- Scalable Icon -->
                <svg v-else-if="i === 4" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <!-- Cross-Browser Icon -->
                <svg v-else-if="i === 5" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                <!-- Easy Manage Icon -->
                <svg v-else-if="i === 6" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
                <!-- Support Icon -->
                <svg v-else-if="i === 7" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
              </div>
              <div class="wd-wyg-card__info">
                <h3 class="wd-wyg-card__title">{{ item.title }}</h3>
                <p class="wd-wyg-card__desc">{{ item.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           ADDITIONAL SERVICES
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-white">
        <div class="wd-container">
          <div data-rev class="wd-section-header mb-12 text-center">
            <h2 class="wd-section-title">Additional Services We Offer</h2>
          </div>
          
          <div data-rev class="wd-add-badge-list">
            <div v-for="(s, i) in addServices" :key="s.label" class="wd-add-badge">
              <span class="wd-add-badge__icon-bg">
                <!-- Web Custom Icon -->
                <svg v-if="i === 0" class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <!-- E-Commerce Icon -->
                <svg v-else-if="i === 1" class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <!-- API Integration Icon -->
                <svg v-else-if="i === 2" class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <!-- Migration Icon -->
                <svg v-else-if="i === 3" class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <!-- Speed Icon -->
                <svg v-else-if="i === 4" class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <!-- Maintenance Icon -->
                <svg v-else-if="i === 5" class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </span>
              <span>{{ s.label }}</span>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           CTA BANNER SECTION
      ═══════════════════════════════════════════════════ -->
      <section class="wd-section bg-slate-50 wd-section-cta">
        <div class="wd-container">
          <div data-rev class="wd-cta-banner">
            <!-- Rocket outline graphic in background -->
            <div class="wd-cta-banner__rocket-bg">
              <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 20 C85 35 75 75 75 110 L75 140 C75 140 90 148 100 148 C110 148 125 140 125 140 L125 110 C125 75 115 35 100 20Z" stroke="rgba(255,255,255,0.07)" stroke-width="2"/>
                <circle cx="100" cy="80" r="12" stroke="rgba(255,255,255,0.07)" stroke-width="2"/>
                <path d="M75 115 L55 140 L75 133 Z" stroke="rgba(255,255,255,0.07)" stroke-width="2"/>
                <path d="M125 115 L145 140 L125 133 Z" stroke="rgba(255,255,255,0.07)" stroke-width="2"/>
              </svg>
            </div>
            
            <div class="wd-cta-banner__left">
              <h2 class="wd-cta-banner__title">Ready to Build Your Dream Website?</h2>
              <p class="wd-cta-banner__sub">Let's create a powerful digital experience that drives real results.</p>
            </div>
            <div class="wd-cta-banner__right">
              <Link href="/contact-us" class="wd-cta-btn">
                <span>Get a Free Quote</span>
                <span class="wd-cta-btn__circle">
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
.wd-btn__circle-play {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.07);
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

/* Laptop Mockup styling */
.wd-laptop {
  width: 85%;
  position: relative;
  z-index: 2;
  animation: floatLaptop 6s ease-in-out infinite;
}
.wd-laptop__shell {
  background: linear-gradient(135deg, #1e293b, #334155);
  border-radius: 12px 12px 0 0;
  padding: 8px 8px 0;
  box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
}
.wd-laptop__screen {
  background-color: #f8fafc;
  border-radius: 6px 6px 0 0;
  aspect-ratio: 16/10;
  overflow: hidden;
  position: relative;
}
.wd-laptop__hinge {
  background-color: #64748b;
  height: 6px;
  width: 100%;
}
.wd-laptop__base {
  background-color: #94a3b8;
  height: 4px;
  width: 86%;
  margin: 0 auto;
  border-radius: 0 0 8px 8px;
}

/* Browser window headers */
.wd-browser-header {
  background-color: #e2e8f0;
  height: 20px;
  display: flex;
  align-items: center;
  padding: 0 10px;
  position: relative;
}
.wd-browser-dots {
  display: flex;
  gap: 4px;
  position: absolute;
  left: 10px;
}
.wd-browser-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
}
.wd-browser-dot--red { background-color: #ef4444; }
.wd-browser-dot--yellow { background-color: #f59e0b; }
.wd-browser-dot--green { background-color: #10b981; }

.wd-browser-address {
  font-size: 7px;
  color: #64748b;
  background-color: #ffffff;
  border-radius: 3px;
  padding: 1px 16px;
  margin: 0 auto;
  min-width: 120px;
  text-align: center;
  font-weight: 500;
}

/* Site mock inside laptop screen */
.wd-dashboard-site {
  display: flex;
  flex-direction: column;
  height: calc(100% - 20px);
  background-color: #ffffff;
}
.wd-db-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 6px 12px;
  border-bottom: 1px solid #f1f5f9;
}
.wd-db-logo {
  display: flex;
  align-items: center;
  gap: 3px;
}
.wd-db-logo-icon {
  width: 12px;
  height: 12px;
  border-radius: 3px;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff;
  font-size: 7px;
  font-weight: 900;
  display: flex;
  align-items: center;
  justify-content: center;
}
.wd-db-logo-text {
  font-size: 8px;
  font-weight: 800;
  color: #0f172a;
}
.wd-db-links {
  display: flex;
  gap: 8px;
  font-size: 6px;
  font-weight: 600;
  color: #64748b;
}
.wd-db-cta-btn {
  font-size: 5px;
  font-weight: 700;
  background-color: #6366f1;
  color: #ffffff;
  padding: 3px 6px;
  border-radius: 3px;
}

.wd-db-hero {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  padding: 0 16px;
  background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
}
.wd-db-hero-text h2 {
  font-size: 11px;
  font-weight: 800;
  line-height: 1.2;
  color: #0f172a;
}
.wd-db-hero-btn {
  font-size: 5px;
  font-weight: 700;
  background-color: #6366f1;
  color: #ffffff;
  padding: 3px 7px;
  border-radius: 3px;
  margin-top: 5px;
}

/* Smartphone Mockup */
.wd-phone {
  position: absolute;
  right: 2%;
  bottom: 5%;
  width: 19%;
  z-index: 3;
  animation: floatPhone 6s ease-in-out infinite;
}
.wd-phone__body {
  background-color: #1e293b;
  border-radius: 18px;
  padding: 4px;
  box-shadow: 0 20px 40px -10px rgba(0,0,0,0.25);
  border: 1px solid #334155;
  aspect-ratio: 9/19;
  display: flex;
  flex-direction: column;
}
.wd-phone__screen {
  background-color: #0f172a;
  border-radius: 14px;
  overflow: hidden;
  flex: 1;
  display: flex;
  flex-direction: column;
}
.wd-phone-header {
  background-color: #1e293b;
  height: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 4px;
  color: #94a3b8;
  font-weight: 500;
}
.wd-phone-content {
  flex: 1;
  padding: 6px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%);
}
.wd-phone-logo {
  display: flex;
  align-items: center;
  gap: 2px;
  margin-bottom: 6px;
}
.wd-phone-logo-icon {
  width: 8px;
  height: 8px;
  border-radius: 2px;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff;
  font-size: 5px;
  font-weight: 900;
  display: flex;
  align-items: center;
  justify-content: center;
}
.wd-phone-logo-text {
  font-size: 6px;
  font-weight: 800;
  color: #fff;
}
.wd-phone-title {
  font-size: 6px;
  font-weight: 800;
  line-height: 1.3;
  color: #fff;
  margin-bottom: 6px;
  max-width: 60px;
}
.wd-phone-btn {
  font-size: 4px;
  font-weight: 700;
  background-color: #6366f1;
  color: #ffffff;
  padding: 2px 5px;
  border-radius: 2px;
  margin-bottom: 8px;
}
.wd-phone-stats {
  display: flex;
  gap: 4px;
  width: 100%;
  justify-content: center;
}
.wd-phone-stat-item {
  background-color: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 3px;
  padding: 2px 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 20px;
}
.wd-phone-stat-val {
  font-size: 5px;
  font-weight: 800;
  color: #fff;
}
.wd-phone-stat-lbl {
  font-size: 3px;
  color: #94a3b8;
}
.wd-phone-home-indicator {
  width: 25px;
  height: 2px;
  background-color: #475569;
  border-radius: 2px;
  margin: 3px auto;
}

/* Floating status cards */
.wd-float-badge {
  position: absolute;
  background-color: #ffffff;
  border-radius: 12px;
  padding: 8px 12px;
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1.5px solid #f1f5f9;
}
.wd-float-badge__details {
  display: flex;
  flex-direction: column;
}
.wd-float-badge__title {
  font-size: 9px;
  color: #64748b;
  font-weight: 600;
}
.wd-float-badge__value {
  font-size: 13px;
  font-weight: 800;
}

.wd-float-badge--pagespeed {
  left: 2%;
  bottom: 12%;
  z-index: 4;
  animation: floatCardSpeed 5s ease-in-out infinite;
}
.wd-float-badge--vitals {
  right: 2%;
  top: 15%;
  z-index: 4;
  animation: floatCardVitals 5s ease-in-out infinite;
}
.wd-pagespeed-circle {
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.wd-vitals-check-bg {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background-color: rgba(16, 185, 129, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

/* Animation Keyframes */
@keyframes floatLaptop {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}
@keyframes floatPhone {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(8px); }
}
@keyframes floatCardSpeed {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}
@keyframes floatCardVitals {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(5px); }
}
@keyframes shiftCtaGradient {
  0% { background-position: 0% 50%; }
  100% { background-position: 100% 50%; }
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

.wd-tech-grid, .wd-cms-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  width: 100%;
}
@media (min-width: 480px) {
  .wd-tech-grid, .wd-cms-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 768px) {
  .wd-tech-grid, .wd-cms-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
@media (min-width: 1024px) {
  .wd-tech-grid, .wd-cms-grid {
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
   WHAT YOU GET SECTION
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
   ADDITIONAL SERVICES SECTION
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
   CTA BANNER SECTION
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

.wd-cta-banner__rocket-bg {
  position: absolute;
  right: 4%;
  bottom: -20px;
  width: 140px;
  opacity: 0.8;
  pointer-events: none;
}
@media (max-width: 768px) {
  .wd-cta-banner__rocket-bg {
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
</style>
