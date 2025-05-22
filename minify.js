const { minify } = require('terser');
const fs = require('fs');
const path = require('path');

const jsDir = path.join(__dirname, 'js');
const distDir = path.join(__dirname, 'dist');

// Create dist directory if it doesn't exist
if (!fs.existsSync(distDir)) {
    fs.mkdirSync(distDir);
}

// Function to minify a single file
async function minifyFile(filePath) {
    try {
        const code = fs.readFileSync(filePath, 'utf8');
        const result = await minify(code, {
            compress: true,
            mangle: true,
            format: {
                comments: false
            }
        });

        if (result.error) {
            console.error(`Error minifying ${filePath}:`, result.error);
            return;
        }

        // Get the filename and create the minified version in dist folder
        const fileName = path.basename(filePath);
        const minifiedPath = path.join(distDir, fileName);
        fs.writeFileSync(minifiedPath, result.code);
        console.log(`Successfully minified ${fileName} to dist/${fileName}`);
    } catch (error) {
        console.error(`Error processing ${filePath}:`, error);
    }
}

// Read all JS files from the directory
fs.readdir(jsDir, async (err, files) => {
    if (err) {
        console.error('Error reading directory:', err);
        return;
    }

    // Filter for .js files
    const jsFiles = files.filter(file => file.endsWith('.js'));

    // Minify each file
    for (const file of jsFiles) {
        const filePath = path.join(jsDir, file);
        await minifyFile(filePath);
    }
});