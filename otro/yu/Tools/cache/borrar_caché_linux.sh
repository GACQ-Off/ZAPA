#!/bin/bash

# Cambia el color del fondo a negro y el texto a verde.
# \033[40m establece el color de fondo a negro
# \033[32m establece el color del texto a verde
# \033[1m hace que el texto sea "negrita" o más brillante
# \033[0m restablece los colores a los valores predeterminados al final de la línea.
echo -e "\033[40;32m"

# Limpia la pantalla de la terminal
clear

# Mensaje para el usuario
echo ""
echo "Eliminando archivos caché del PDF de los reportes y recibos..."
echo ""

# Definimos la ruta de la carpeta donde se borrarán los archivos
FONT_DIR="/opt/lampp/htdocs/yu/assets/fpdf/font/unifont"

# Borramos los archivos específicos uno por uno
rm -f "$FONT_DIR/dejavusans.cw.dat"
rm -f "$FONT_DIR/dejavusans.cw127.php"
rm -f "$FONT_DIR/dejavusans.mtx.php"
rm -f "$FONT_DIR/dejavusans-bold.cw.dat"
rm -f "$FONT_DIR/dejavusans-bold.mtx.php"
rm -f "$FONT_DIR/dejavusans-boldoblique.cw.dat"
rm -f "$FONT_DIR/dejavusans-boldoblique.mtx.php"
rm -f "$FONT_DIR/dejavusanscondensed.cw.dat"
rm -f "$FONT_DIR/dejavusanscondensed.cw127.php"
rm -f "$FONT_DIR/dejavusanscondensed.mtx.php"
rm -f "$FONT_DIR/dejavusanscondensed-bold.cw.dat"
rm -f "$FONT_DIR/dejavusanscondensed-bold.cw127.php"
rm -f "$FONT_DIR/dejavusanscondensed-bold.mtx.php"
rm -f "$FONT_DIR/dejavusanscondensed-oblique.cw.dat"
rm -f "$FONT_DIR/dejavusanscondensed-oblique.mtx.php"
rm -f "$FONT_DIR/dejavusans-oblique.cw.dat"
rm -f "$FONT_DIR/dejavusans-oblique.cw127.php"
rm -f "$FONT_DIR/dejavusans-oblique.mtx.php"

# Se muestra un mensaje de confirmación y se restablece el color de la terminal
echo ""
echo "Los archivos se han eliminado correctamente."
echo ""
echo -e "\033[0m"

# Se espera que el usuario presione una tecla para cerrar la terminal
read -p "Presiona Enter para continuar..."