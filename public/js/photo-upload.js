document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del DOM
    const profileAvatarContainer = document.getElementById('profile-avatar-container');
    const photoModal = document.getElementById('photo-modal');
    const closeModal = document.querySelector('.close-modal');
    const photoInput = document.getElementById('photo');
    const previewContainer = document.getElementById('photo-preview');
    const previewImage = document.getElementById('preview-image');
    const cameraOption = document.querySelector('.camera-option');
    const cameraContainer = document.querySelector('.camera-container');
    const cameraPreview = document.getElementById('camera-preview');
    const captureButton = document.getElementById('capture-button');
    const cancelCameraButton = document.getElementById('cancel-camera-button');
    const savePhotoButton = document.getElementById('save-photo-button');
    const cancelPhotoButton = document.getElementById('cancel-photo-button');
    const cameraCanvas = document.getElementById('camera-canvas');
    const cameraInput = document.getElementById('camera-input');
    const profileForm = document.querySelector('.profile-form');
    const profileAvatarImg = document.getElementById('profile-avatar-img');
    
    let stream = null;
    let photoChanged = false;
    
    // Abrir el modal cuando se hace clic en la foto de perfil
    if (profileAvatarContainer) {
        profileAvatarContainer.addEventListener('click', function() {
            if (photoModal) {
                photoModal.style.display = 'block';
            }
        });
    }
    
    // Cerrar el modal cuando se hace clic en la X
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            closePhotoModal();
        });
    }
    
    // Cerrar el modal cuando se hace clic fuera del contenido
    window.addEventListener('click', function(event) {
        if (event.target === photoModal) {
            closePhotoModal();
        }
    });
    
    // Mostrar vista previa cuando se selecciona un archivo
    if (photoInput) {
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                    document.querySelector('.photo-options').style.display = 'none';
                    cameraContainer.style.display = 'none';
                    photoChanged = true;
                    
                    // Si la cámara está activa, detenerla
                    stopCamera();
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Mostrar la cámara cuando se hace clic en la opción de cámara
    if (cameraOption) {
        cameraOption.addEventListener('click', function() {
            document.querySelector('.photo-options').style.display = 'none';
            cameraContainer.style.display = 'block';
            startCamera();
        });
    }
    
    // Cancelar la cámara
    if (cancelCameraButton) {
        cancelCameraButton.addEventListener('click', function() {
            stopCamera();
            cameraContainer.style.display = 'none';
            document.querySelector('.photo-options').style.display = 'flex';
        });
    }
    
    // Capturar foto cuando se hace clic en el botón de captura
    if (captureButton) {
        captureButton.addEventListener('click', function() {
            capturePhoto();
        });
    }
    
    // Guardar la foto y cerrar el modal
    if (savePhotoButton) {
        savePhotoButton.addEventListener('click', function() {
            // Actualizar la foto de perfil en la página
            if (profileAvatarImg) {
                profileAvatarImg.src = previewImage.src;
            }
            
            // Enviar el formulario automáticamente para guardar la foto
            if (photoChanged && profileForm) {
                profileForm.submit();
            } else {
                closePhotoModal();
            }
        });
    }
    
    // Cancelar la vista previa
    if (cancelPhotoButton) {
        cancelPhotoButton.addEventListener('click', function() {
            previewContainer.style.display = 'none';
            document.querySelector('.photo-options').style.display = 'flex';
            photoChanged = false;
        });
    }
    
    // Función para cerrar el modal y limpiar
    function closePhotoModal() {
        if (photoModal) {
            photoModal.style.display = 'none';
        }
        stopCamera();
        previewContainer.style.display = 'none';
        if (document.querySelector('.photo-options')) {
            document.querySelector('.photo-options').style.display = 'flex';
        }
        if (cameraContainer) {
            cameraContainer.style.display = 'none';
        }
    }
    
    // Función para iniciar la cámara
    function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(mediaStream) {
                    stream = mediaStream;
                    cameraPreview.srcObject = mediaStream;
                    cameraPreview.play();
                })
                .catch(function(error) {
                    console.error('Error al acceder a la cámara: ', error);
                    alert('No se pudo acceder a la cámara. Asegúrate de que tienes una cámara conectada y has dado permiso para usarla.');
                    cameraContainer.style.display = 'none';
                    document.querySelector('.photo-options').style.display = 'flex';
                });
        } else {
            alert('Tu navegador no soporta la API de MediaDevices.');
            cameraContainer.style.display = 'none';
            document.querySelector('.photo-options').style.display = 'flex';
        }
    }
    
    // Función para detener la cámara
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => {
                track.stop();
            });
            stream = null;
        }
    }
    
    // Función para capturar la foto
    function capturePhoto() {
        const context = cameraCanvas.getContext('2d');
        
        // Configurar el tamaño del canvas para que coincida con el video
        cameraCanvas.width = cameraPreview.videoWidth;
        cameraCanvas.height = cameraPreview.videoHeight;
        
        // Dibujar el fotograma actual del video en el canvas
        context.drawImage(cameraPreview, 0, 0, cameraCanvas.width, cameraCanvas.height);
        
        // Convertir el canvas a una URL de datos
        const dataURL = cameraCanvas.toDataURL('image/png');
        
        // Mostrar la imagen capturada en la vista previa
        previewImage.src = dataURL;
        previewContainer.style.display = 'block';
        cameraContainer.style.display = 'none';
        
        // Guardar la imagen en el campo oculto para enviarla con el formulario
        cameraInput.value = dataURL;
        photoChanged = true;
        
        // Detener la cámara después de capturar la foto
        stopCamera();
    }
});
