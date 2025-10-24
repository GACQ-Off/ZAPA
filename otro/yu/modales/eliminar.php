<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 100;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        position: relative;
        color: #333;
    }
    .close-button {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 25px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close-button:hover,
    .close-button:focus {
        color: black;
        text-decoration: none;
    }
    .success-icon {
        color: #4CAF50;
        font-size: 60px;
        margin-bottom: 10px;
    }
</style>
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('successModal')">&times;</span>
        <span class="success-icon">&#10004;</span>
        <h2>Éxito</h2>
        <p>Elemento eliminado con éxito.</p>
    </div>
</div>
<script>
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'flex';
    }
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';
    }
    window.onclick = function(event) {
        const modal = document.getElementById('successModal');
        if (event.target === modal) {
            closeModal('successModal');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
    });
</script>