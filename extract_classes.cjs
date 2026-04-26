const fs = require('fs');
const path = require('path');

function parsePHPFiles(dir, data) {
    if (!fs.existsSync(dir)) return;
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) {
            parsePHPFiles(fullPath, data);
        } else if (file.endsWith('.php')) {
            const content = fs.readFileSync(fullPath, 'utf8');
            let className = '';
            const classMatch = content.match(/class\s+([A-Za-z0-9_]+)/);
            if (classMatch) {
                className = classMatch[1];
                let methods = [];
                let attributes = [];
                
                const methodRegex = /(?:public|protected|private)?\s*function\s+([A-Za-z0-9_]+)\s*\(/g;
                let match;
                while ((match = methodRegex.exec(content)) !== null) {
                    methods.push(match[1]);
                }
                
                const fillableMatch = content.match(/protected\s+\$fillable\s*=\s*\[([^\]]+)\]/);
                if (fillableMatch) {
                    const attrs = fillableMatch[1].split(',').map(s => s.replace(/['"\n\r\s]/g, '')).filter(s => s);
                    attributes = attributes.concat(attrs);
                }
                
                data.push({
                    type: fullPath.includes('Models') ? 'Model' : 'Controller',
                    class: className,
                    attributes: attributes,
                    methods: methods
                });
            }
        }
    }
}

let result = [];
parsePHPFiles(path.join(__dirname, 'app', 'Models'), result);
parsePHPFiles(path.join(__dirname, 'app', 'Http', 'Controllers'), result);

fs.writeFileSync('class_diagram_data.json', JSON.stringify(result, null, 2));
console.log("Done");
