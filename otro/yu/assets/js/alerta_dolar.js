function mostrarAlertaConfirmacion(mensaje, callbackSi, callbackNo) {
    const overlay = document.createElement('div');
    overlay.id = 'modal-overlay';
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    overlay.style.display = 'flex';
    overlay.style.justifyContent = 'center';
    overlay.style.alignItems = 'center';
    overlay.style.zIndex = '1000';

    const modalContent = document.createElement('div');
    modalContent.id = 'modal-content';
    modalContent.style.backgroundColor = '#f9f9f9';
    modalContent.style.padding = '20px';
    modalContent.style.borderRadius = '8px';
    modalContent.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.3)';
    modalContent.style.textAlign = 'center';

    const mensajeElement = document.createElement('p');
    mensajeElement.textContent = mensaje;
    mensajeElement.style.color = 'black';

    const botones = document.createElement('div');
    botones.id = 'modal-buttons';
    botones.style.marginTop = '10px';

    const botonSi = document.createElement('button');
    botonSi.id = 'si-btn';
    botonSi.textContent = 'Sí';
    botonSi.style.padding = '10px 20px';
    botonSi.style.margin = '10px';
    botonSi.style.border = 'none';
    botonSi.style.borderRadius = '5px';
    botonSi.style.cursor = 'pointer';
    botonSi.style.fontWeight = 'bold';
    botonSi.style.backgroundColor = '#3533cd';
    botonSi.style.color = 'white';
    botonSi.addEventListener('click', function() {
        document.body.removeChild(overlay);
        if (callbackSi) {
            callbackSi();
        }
    });

    const botonNo = document.createElement('button');
    botonNo.id = 'no-btn';
    botonNo.textContent = 'No';
    botonNo.style.padding = '10px 20px';
    botonNo.style.margin = '10px';
    botonNo.style.border = 'none';
    botonNo.style.borderRadius = '5px';
    botonNo.style.cursor = 'pointer';
    botonNo.style.fontWeight = 'bold';
    botonNo.style.backgroundColor = '#6c757d';
    botonNo.style.color = 'white';
    botonNo.addEventListener('click', function() {
        document.body.removeChild(overlay);
        if (callbackNo) {
            callbackNo();
        }
    });

    botones.appendChild(botonSi);
    botones.appendChild(botonNo);
    modalContent.appendChild(mensajeElement);
    modalContent.appendChild(botones);
    overlay.appendChild(modalContent);

    document.body.appendChild(overlay);
}

function confirmarActualizacion() {
    mostrarAlertaConfirmacion(
        '¿Quieres actualizar el dólar?',
        function() {
         window.location.href = "../funciones/actualizar_tasa_dolar.php";
        },
        null
    );
}

window.onload = confirmarActualizacion;