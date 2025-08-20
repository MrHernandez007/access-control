const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Ruta absoluta a la carpeta 'public/uploads' en la raíz del proyecto
const carpetaDestino = path.join(process.cwd(), 'public/uploads');

// Crear carpeta si no existe
if (!fs.existsSync(carpetaDestino)) {
    fs.mkdirSync(carpetaDestino, { recursive: true });
}

const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, carpetaDestino);
    },
    filename: function (req, file, cb) {
        const nombreUnico = Date.now() + path.extname(file.originalname);
        cb(null, nombreUnico);
    }
});

const upload = multer({ storage });

module.exports = upload;
