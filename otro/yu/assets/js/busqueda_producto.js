// Contenido para assets/js/product_search.js
import DOM from './elementos_dom.js';
import AppState from './estado.js';
import Utils from './utilidad.js';
import Cart from './cart.js'; // Necesita Cart para agregar productos

const ProductSearch = {
    init: () => {
        DOM.productSearch.addEventListener('input', (e) => {
            const term = e.target.value.trim();
            Utils.debounceSearch(() => {
                ProductSearch.performSearch(term.toLowerCase());
            });
        });

        DOM.clearProductSearch.addEventListener('click', () => {
            ProductSearch.clear();
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-wrapper')) {
                DOM.productResults.style.display = 'none';
            }
        });
    },

    performSearch: (term) => {
        DOM.productResults.innerHTML = '';

        if (term.length < 2) {
            DOM.productResults.style.display = 'none';
            return;
        }

        const results = AppData.productos.filter(p => 
            p.nombre_producto.toLowerCase().includes(term)
        );

        if (results.length > 0) {
            results.forEach(product => {
                const item = document.createElement('div');
                item.className = 'search-result-item';
                item.innerHTML = `
                    <div>${product.nombre_producto}</div>
                    <div class="product-type">${product.id_tipo_cuenta == 1 ? 'Servicio' : 'Producto Físico'}</div>
                `;
                item.addEventListener('click', () => {
                    ProductSearch.selectProduct(product);
                });
                DOM.productResults.appendChild(item);
            });
            DOM.productResults.style.display = 'block';
        } else {
            const noResults = document.createElement('div');
            noResults.className = 'no-results';
            noResults.textContent = 'No se encontraron productos';
            DOM.productResults.appendChild(noResults);
            DOM.productResults.style.display = 'block';
        }
    },

    selectProduct: (product) => {
        Cart.addProduct(product);
        DOM.productResults.style.display = 'none';
    },

    clear: () => {
        DOM.productSearch.value = '';
        DOM.productResults.style.display = 'none';
    }
};

export default ProductSearch;