 // Estado de la aplicación
        const AppState = {
            purchaseItems: [],
            currentCurrency: AppData.config.moneda_principal,
            exchangeRate: parseFloat(AppData.config.tasa_dolar_actual) || 0,
            selectedProvider: null,
            searchTimeout: null,
            tasaDolarActualId: AppData.id_tasa_dolar_actual_db 
        };
        export default AppState;