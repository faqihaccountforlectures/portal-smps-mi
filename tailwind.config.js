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
                'deep-space': '#0A1026',
                'midnight-blue': '#142B52',
                'constellation': '#254A7A',
                'moonlight': '#5D7EA8',
                'starlight': '#D4B06A',
                'soft-gold': '#F0D79A',
                'ivory': '#F8F6EE',
            },
            fontFamily: {
                'heading': ['"Playfair Display"', 'serif'],
                'body': ['Poppins', 'sans-serif'],
            }
        },
    },
    plugins: [],
};
