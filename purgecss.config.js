const cssFileName = 'app.deb29847.css';
const jsFileName = 'app.63078355.js';

module.exports = {
    content: [
        'resources/views/main/**/*.blade.php',
        'resources/views/components/main/**/*.blade.php',
        `public/build/assets/${jsFileName}`,
    ],
    css: [
        `public/build/assets/${cssFileName}`,
    ],
    output: `public/build/assets/${cssFileName}`,
    safelist: ['h2', 'fs-4', 'fs-5'],
}