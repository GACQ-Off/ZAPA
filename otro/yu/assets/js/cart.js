// Contenido para assets/js/cart.js
import DOM from './elementos_dom.js';
import AppState from './estado.js';
import Utils from './utilidad.js';

const Cart = {
    addProduct: (product) => {
        if (AppState.purchaseItems.some(item => item.id_pro === product.id_pro)) {
            Utils.showMessage('Producto existente', 'Este producto ya está en la compra', true);
            return false;
        }

        AppState.purchaseItems.push({
            ...product,
            quantity: 1,
            cost: 0.00,
            isService: product.id_tipo_cuenta == 1 
        });

        Cart.render();
        DOM.productSearch.value = '';
        DOM.productResults.style.display = 'none';
        return true;
    },

    removeProduct: (productId) => {
        AppState.purchaseItems = AppState.purchaseItems.filter(item => item.id_pro != productId);
        Cart.render();
    },

    updateProduct: (productId, field, value) => {
        const item = AppState.purchaseItems.find(item => item.id_pro == productId);
        if (item) {
            item[field] = parseFloat(value) || 0;
            Cart.render();
        }
    },

    render: () => {
        DOM.purchaseItemsContainer.innerHTML = '';

        if (AppState.purchaseItems.length === 0) {
            DOM.emptyCartMessage.style.display = 'block';
            DOM.emptyCartMessage.textContent = AppState.selectedProvider ? 
                'Busca y agrega productos a la compra.' : 
                'Seleccione un proveedor para empezar.';
            DOM.purchaseItemsContainer.appendChild(DOM.emptyCartMessage);
            return;
        }

        DOM.emptyCartMessage.style.display = 'none';

        AppState.purchaseItems.forEach(item => {
            const subtotal = item.quantity * item.cost;
            const itemEl = document.createElement('div');
            itemEl.className = 'purchase-item';
            itemEl.dataset.productId = item.id_pro;
            itemEl.innerHTML = `
                <div class="product-name">${item.nombre_producto} ${item.isService ? '(Servicio)' : ''}</div>
                <div>
                    <input type="number" class="form-control item-quantity" 
                           value="${item.quantity}" min="1" ${item.isService ? 'disabled' : ''}>
                </div>
                <div>
                    <input type="number" class="form-control item-cost" 
                           value="${item.cost.toFixed(2)}" min="0" step="0.01">
                </div>
                <div class="item-subtotal">${Utils.formatCurrency(subtotal)}</div>
                <div>
                    <button class="btn btn-danger remove-item-btn">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            `;
            DOM.purchaseItemsContainer.appendChild(itemEl);
        });

        Cart.updateTotals();
        Utils.validateForm();
    },

    updateTotals: () => {
        const subtotal = AppState.purchaseItems.reduce((sum, item) => sum + (item.quantity * item.cost), 0);
        const tax = subtotal * 0.16; 
        const total = subtotal + tax;

        DOM.summarySubtotal.textContent = Utils.formatCurrency(subtotal);
        DOM.summaryTax.textContent = Utils.formatCurrency(tax);
        DOM.summaryTotal.textContent = Utils.formatCurrency(total);
    },

    clear: () => {
        AppState.purchaseItems = [];
        Cart.render();
    }
};

export default Cart;