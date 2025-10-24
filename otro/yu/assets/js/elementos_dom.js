 const DOM = {
            // Buscadores
            providerSearch: document.getElementById('provider-search'),
            providerResults: document.getElementById('provider-results'),
            clearProviderSearch: document.getElementById('clear-provider-search'),
            productSearch: document.getElementById('product-search'),
            productResults: document.getElementById('product-results'),
            clearProductSearch: document.getElementById('clear-product-search'),
            newProductBtn: document.getElementById('new-product-btn'),
            
            // Carrito y compra
            purchaseItemsContainer: document.getElementById('purchase-items-container'),
            emptyCartMessage: document.getElementById('empty-cart-message'),
            registerPurchaseForm: document.getElementById('register-purchase-form'), // New: Reference to the main purchase form
            hiddenProviderRif: document.getElementById('hidden-provider-rif'), // New hidden field for provider RIF
            hiddenPurchaseItemsData: document.getElementById('hidden-purchase-items-data'), // New hidden field for purchase items JSON
            registerBtn: document.getElementById('register-purchase-btn'),
            
            // Opciones de pago
            creditCheckbox: document.getElementById('credit-checkbox'),
            creditTermContainer: document.getElementById('credit-term-container'),
            paymentMethod: document.getElementById('payment-method'),
            exchangeRateGroup: document.getElementById('exchange-rate-group'),
            exchangeRate: document.getElementById('exchange-rate'),
            paymentMethodContainer: document.getElementById('payment-method-container'), 
            
            // Totales
            summarySubtotal: document.getElementById('summary-subtotal'),
            summaryTax: document.getElementById('summary-tax'),
            summaryTotal: document.getElementById('summary-total'),
            
            // Modales
            loadingOverlay: document.getElementById('loading-overlay'),
            messageModal: document.getElementById('message-modal'),
            messageTitle: document.getElementById('message-modal-title'),
            messageBody: document.getElementById('message-modal-body'),
            messageClose: document.getElementById('message-modal-close'),
            newProductModal: document.getElementById('new-product-modal'),
            closeModalBtn: document.querySelector('#new-product-modal .close-modal-btn'), 
            newProductForm: document.getElementById('new-product-form'),
            paymentReference: document.getElementById('payment-reference'),

            // Nuevos campos del modal de nuevo producto
            newProductName: document.getElementById('new-product-name'),
            newProductCost: document.getElementById('new-product-cost'),
            newProductCostBsDisplay: document.getElementById('new-product-cost-bs-display'),
            newProductCostBsValor: document.getElementById('new-product-cost-bs-valor'),
            newProductCategoryInput: document.getElementById('new-product-category-input'),
            newProductIdCategoriaHidden: document.getElementById('new-product-id-categoria-hidden'),
            newProductCategoriesDatalist: document.getElementById('new-product-categories-datalist'),
            newProductIdIvaRadios: document.querySelectorAll('input[name="new_product_id_iva"]'),
            newProductIdTipoCuentaRadios: document.querySelectorAll('input[name="new_product_id_tipo_cuenta"]'),
            newProductCode: document.getElementById('new-product-code'),
            newProductDescription: document.getElementById('new-product-description')
        };
        export default DOM;