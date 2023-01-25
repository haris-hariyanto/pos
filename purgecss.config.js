const cssFileName = 'app.348d43a9.css';
const jsFileName = 'app.c36a5768.js';

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