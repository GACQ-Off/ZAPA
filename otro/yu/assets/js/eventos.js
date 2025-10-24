// Contenido para assets/js/event_handlers.js
import DOM from './elementos_dom.js';
import AppState from './estado.js';
import Utils from './utilidad.js';
import Cart from './cart.js';
import ProviderSearch from './busqueda_proveedor.js';
import ProductSearch from './busqueda_producto.js';

const EventHandlers = {
    init: () => {
        // Eventos del carrito
        DOM.purchaseItemsContainer.addEventListener('input', (e) => {
            const target = e.target;
            if (target.classList.contains('item-quantity') || target.classList.contains('item-cost')) {
                const productId = target.closest('.purchase-item').dataset.productId;
                const field = target.classList.contains('item-quantity') ? 'quantity' : 'cost';
                Cart.updateProduct(productId, field, target.value);
            }
        });

        DOM.purchaseItemsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-item-btn')) {
                const productId = e.target.closest('.purchase-item').dataset.productId;
                Cart.removeProduct(productId);
            }
        });

        // Opción de crédito
        DOM.creditCheckbox.addEventListener('change', (e) => {
            DOM.creditTermContainer.style.display = e.target.checked ? 'block' : 'none';
            DOM.paymentMethodContainer.style.display = e.target.checked ? 'none' : 'block';
            if (e.target.checked) {
                DOM.paymentMethod.value = '';
                DOM.exchangeRate.value = AppState.exchangeRate.toFixed(4);
                DOM.paymentReference.value = '';
            }
            Utils.validateForm();
        });

        // Método de pago
        DOM.paymentMethod.addEventListener('change', Utils.handlePaymentMethodChange);

        // Nuevo Producto
        DOM.newProductBtn.addEventListener('click', () => {
            DOM.newProductModal.style.display = 'flex';
            DOM.newProductForm.reset();
            DOM.newProductIdCategoriaHidden.value = '';
            Utils.updateNewProductCostBs();
            DOM.newProductIdIvaRadios.forEach(radio => radio.checked = false);
            DOM.newProductIdTipoCuentaRadios.forEach(radio => radio.checked = false);
        });

        DOM.closeModalBtn.addEventListener('click', () => {
            DOM.newProductModal.style.display = 'none';
        });

        // Cerrar modales al hacer clic fuera
        [DOM.newProductModal, DOM.messageModal].forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Formulario de nuevo producto
        if (DOM.newProductForm) {
            DOM.newProductCost.addEventListener('input', Utils.updateNewProductCostBs);

            DOM.newProductCategoryInput.addEventListener('input', function() {
                let selectedOption = null;
                for (let i = 0; i < DOM.newProductCategoriesDatalist.options.length; i++) {
                    if (DOM.newProductCategoriesDatalist.options[i].value === DOM.newProductCategoryInput.value) {
                        selectedOption = DOM.newProductCategoriesDatalist.options[i];
                        break;
                    }
                }
                if (selectedOption) {
                    DOM.newProductIdCategoriaHidden.value = selectedOption.getAttribute('data-id');
                } else {
                    DOM.newProductIdCategoriaHidden.value = '';
                }
            });

            DOM.newProductForm.addEventListener('submit', (e) => {
                if (!Utils.validateNewProductForm()) {
                    e.preventDefault();
                }
            });
        }

        // Registrar compra
        DOM.registerPurchaseForm.addEventListener('submit', (e) => {
            if (!Utils.validateForm()) {
                Utils.showMessage('Formulario incompleto', 'Por favor complete todos los campos requeridos para la compra', true);
                e.preventDefault();
                return;
            }

            DOM.hiddenProviderRif.value = AppState.selectedProvider ? AppState.selectedProvider.RIF : '';
            DOM.hiddenPurchaseItemsData.value = JSON.stringify(AppState.purchaseItems);
        });

        // Cerrar modal de mensajes
        DOM.messageClose.addEventListener('click', () => {
            DOM.messageModal.style.display = 'none';
        });
    }
};

export default EventHandlers;