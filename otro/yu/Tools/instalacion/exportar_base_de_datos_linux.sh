#!/bin/bash

# Define las variables para las rutas y credenciales
SQL_FILE="/opt/lampp/htdocs/yu/Tools/instalacion/vertex.sql"
# En Linux, el ejecutable de mysql generalmente está en el PATH del sistema,
# por lo que no es necesario especificar la ruta completa.
MYSQL_CMD="mysql"
MYSQL_USER="root"
MYSQL_PASSWORD=""

echo "Importando la base de datos desde $SQL_FILE..."

# Comando para importar la base de datos.
# El -p (password) debe ir sin espacio con el valor de la contraseña.
# Si no hay contraseña (como en este caso), se deja -p sin nada.
# Nota: es más seguro usar `read` para la contraseña para que no se muestre en pantalla.
$MYSQL_CMD -u $MYSQL_USER -p"$MYSQL_PASSWORD" < "$SQL_FILE"

# Verifica el estado de salida del comando anterior ($? es 0 si fue exitoso)
if [ $? -eq 0 ]; then
    echo "La base de datos ha sido importada exitosamente."
else
    echo "Ha ocurrido un error durante la importación."
fi

# Pausa la ejecución del script para que puedas ver el resultado
read -p "Presiona Enter para continuar..."