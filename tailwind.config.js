/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'blue-zodiac': '#0f2f57',
                'bismark': '#4b6b8b',
                'gull-gray': '#95a6b7',
                'botticelli': '#c9d6e4',
                'catskill-white': '#f8fafc',
            },
            fontFamily: {
                'heading': ['Lora', 'serif'],
                'body': ['"Nunito Sans"', 'sans-serif'],
            }
        },
    },
    plugins: [],
};
