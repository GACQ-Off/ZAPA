// Contenido para assets/js/provider_search.js
import DOM from './elementos_dom.js';
import AppState from './estado.js';
import Utils from './utilidad.js';
import Cart from './cart.js'; // Necesita Cart para limpiar

const ProviderSearch = {
    init: () => {
        DOM.providerSearch.addEventListener('input', (e) => {
            const term = e.target.value.trim();
            Utils.debounceSearch(() => {
                ProviderSearch.performSearch(term.toLowerCase());
            });
        });

        DOM.clearProviderSearch.addEventListener('click', () => {
            ProviderSearch.clear();
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-wrapper')) {
                DOM.providerResults.style.display = 'none';
            }
        });
    },

    performSearch: (term) => {
        DOM.providerResults.innerHTML = '';

        if (term.length < 2) {
            DOM.providerResults.style.display = 'none';
            return;
        }

        const results = AppData.proveedores.filter(p => 
            p.nombre_provedor.toLowerCase().includes(term) || 
            p.RIF.toLowerCase().includes(term)
        );

        if (results.length > 0) {
            results.forEach(provider => {
                const item = document.createElement('div');
                item.className = 'search-result-item';
                item.innerHTML = `
                    <div class="provider-info">
                        <span class="provider-name">${provider.nombre_provedor}</span>
                        <span class="provider-rif">${provider.RIF}</span>
                    </div>
                `;
                item.addEventListener('click', () => {
                    ProviderSearch.selectProvider(provider);
                });
                DOM.providerResults.appendChild(item);
            });
            DOM.providerResults.style.display = 'block';
        } else {
            const noResults = document.createElement('div');
            noResults.className = 'no-results';
            noResults.textContent = 'No se encontraron proveedores';
            DOM.providerResults.appendChild(noResults);
            DOM.providerResults.style.display = 'block';
        }
    },

    selectProvider: (provider) => {
        AppState.selectedProvider = provider;
        DOM.providerSearch.value = provider.nombre_provedor;
        DOM.hiddenProviderRif.value = provider.RIF;
        DOM.providerResults.style.display = 'none';
        DOM.productSearch.disabled = false;
        Cart.clear();
        Utils.validateForm();
    },

    clear: () => {
        DOM.providerSearch.value = '';
        DOM.hiddenProviderRif.value = '';
        DOM.providerResults.style.display = 'none';
        AppState.selectedProvider = null;
        DOM.productSearch.disabled = true;
        DOM.productSearch.value = '';
        DOM.productResults.style.display = 'none';
        Cart.clear();
        Utils.validateForm();
    }
};

export default ProviderSearch;