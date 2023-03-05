const cssFileName = 'app.ab305471.css';
const jsFileName = 'app.65f43da6.js';

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
    safelist: ['h2', 'h4', 'fs-4', 'fs-5'],
}