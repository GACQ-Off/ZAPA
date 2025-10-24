// Utilidades
        const CurrencySymbols = { 'USD': '$', 'BS': 'Bs.' };
        const Utils = {
            showMessage: (title, message, isError = false) => {
                // For client-side validation, we still use the modal.
                // Server-side messages are handled by PHP directly.
                DOM.messageTitle.textContent = title;
                DOM.messageBody.textContent = message;
                DOM.messageTitle.style.color = isError ? '#d32f2f' : '#2e7d32';
                DOM.messageModal.style.display = 'flex';
            },
            
            formatCurrency: (amount, currency = AppState.currentCurrency) => {
                return `${CurrencySymbols[currency]} ${parseFloat(amount).toFixed(2)}`;
            },
            
            validateForm: () => {
                let isValid = true;
                
                if (!AppState.selectedProvider) isValid = false;
                if (AppState.purchaseItems.length === 0) isValid = false;
                
                AppState.purchaseItems.forEach(item => {
                    if (item.quantity <= 0 || item.cost < 0) isValid = false;
                });
                
                if (DOM.creditCheckbox.checked && !document.getElementById('credit-term').value) {
                    isValid = false;
                }
                
                if (!DOM.creditCheckbox.checked && 
                    DOM.paymentMethod.options[DOM.paymentMethod.selectedIndex].dataset.moneda === 'BS' &&
                    (!DOM.exchangeRate.value || parseFloat(DOM.exchangeRate.value) <= 0)) {
                    isValid = false;
                }
                
                DOM.registerBtn.disabled = !isValid;
                return isValid;
            },
            
            handlePaymentMethodChange: () => {
                const selected = DOM.paymentMethod.options[DOM.paymentMethod.selectedIndex];
                const currency = selected.dataset.moneda;
                
                DOM.exchangeRateGroup.style.display = currency === 'BS' ? 'block' : 'none';
                if (currency === 'BS' && !DOM.exchangeRate.value && AppState.exchangeRate > 0) {
                    DOM.exchangeRate.value = AppState.exchangeRate.toFixed(4);
                }
            },
            
            debounceSearch: (callback, delay = 300) => {
                clearTimeout(AppState.searchTimeout);
                AppState.searchTimeout = setTimeout(callback, delay);
            },

            updateNewProductCostBs: () => {
                const precioUsdString = DOM.newProductCost.value;
                const precioUsd = parseFloat(precioUsdString.replace(',', '.'));

                if (!isNaN(precioUsd) && precioUsd > 0 && AppState.exchangeRate > 0) {
                    const precioBs = precioUsd * AppState.exchangeRate;
                    DOM.newProductCostBsValor.textContent = precioBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    DOM.newProductCostBsDisplay.style.display = 'block';
                } else if (AppState.exchangeRate <= 0) {
                    DOM.newProductCostBsValor.textContent = 'Tasa de cambio no disponible';
                    DOM.newProductCostBsDisplay.style.display = 'block';
                } else {
                    DOM.newProductCostBsValor.textContent = '0.00';
                }
            },

            validateNewProductForm: () => {
                let isValid = true;
                
                // Validate required text/number inputs
                if (!DOM.newProductName.value.trim()) { isValid = false; Utils.showMessage('Error de Validación', 'El nombre del producto es requerido.', true); return false; }
                if (parseFloat(DOM.newProductCost.value) <= 0) { isValid = false; Utils.showMessage('Error de Validación', 'El costo unitario debe ser un valor positivo.', true); return false; }
                if (!DOM.newProductDescription.value.trim()) { isValid = false; Utils.showMessage('Error de Validación', 'La descripción del producto es requerida.', true); return false; }

                // Validate category selection from datalist
                if (!DOM.newProductCategoryInput.value.trim()) {
                    isValid = false;
                    Utils.showMessage('Error de Categoría', 'Por favor, seleccione una categoría válida de la lista de sugerencias.', true);
                    return false; 
                } else {
                    let isValidCategory = false;
                    for (let i = 0; i < DOM.newProductCategoriesDatalist.options.length; i++) {
                        if (DOM.newProductCategoriesDatalist.options[i].value === DOM.newProductCategoryInput.value) {
                            isValidCategory = true;
                            break;
                        }
                    }
                    if (!isValidCategory || DOM.newProductIdCategoriaHidden.value === '') {
                        isValid = false;
                        Utils.showMessage('Error de Categoría', 'Por favor, seleccione una categoría válida de la lista de sugerencias.', true);
                        return false; 
                    }
                }

                // Validate IVA radio button selection
                let ivaSelected = false;
                DOM.newProductIdIvaRadios.forEach(radio => {
                    if (radio.checked) ivaSelected = true;
                });
                if (!ivaSelected) { isValid = false; Utils.showMessage('Error de Validación', 'Debe seleccionar un tipo de IVA.', true); return false; }

                // Validate Tipo de Cantidad radio button selection
                let tipoCuentaSelected = false;
                DOM.newProductIdTipoCuentaRadios.forEach(radio => {
                    if (radio.checked) tipoCuentaSelected = true;
                });
                if (!tipoCuentaSelected) { isValid = false; Utils.showMessage('Error de Validación', 'Debe seleccionar un tipo de cantidad (stock).', true); return false; }

                return isValid;
            }
        };
        export default Utils;