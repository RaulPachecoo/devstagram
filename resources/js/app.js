import Dropzone from "dropzone"; // Importa Dropzone desde el archivo correcto

Dropzone.autoDiscover = false; // Desactivar la autodetección de formularios Dropzone


const dropzone = new Dropzone('#dropzone', { // Asegúrate de que el selector esté apuntando correctamente
    dictDefaultMessage: 'Sube aquí tu imagen',
    acceptedFiles: ".png, .jpg, .jpeg, .gif", 
    addRemoveLinks: true,
    dictRemoveFile: 'Borrar Archivo', 
    maxFiles: 1,
    uploadMultiple: false,
    init: function() {
        if(document.querySelector('[name="imagen"]').value.trim()){
            const imagenPublicada = {}
            imagenPublicada.size = 1234; 
            imagenPublicada.name = document.querySelector('[name="imagen"]').value; 
            
            this.options.addedfile.call(this, imagenPublicada); 
            this.options.thumbnail.call(this, imagenPublicada, `/uploads/${imagenPublicada.name}`)

            imagenPublicada.previewElement.classList.add('dz-success', 'dz-complete'); 
        }
            
    }
});

dropzone.on("success", function(file, response) {
    document.querySelector('[name="imagen"]').value = response.imagen;
});

dropzone.on('removedfile', function(){
    document.querySelector('[name="imagen"]').value = ""; 
}); 

