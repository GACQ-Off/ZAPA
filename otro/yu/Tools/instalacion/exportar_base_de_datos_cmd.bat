@echo off
set sql_file="C:\xamp\htdocs\yu\Tools\instalacion\vertex.sql"
set mysql_path="C:\xamp\mysql\bin\mysql.exe"
set mysql_user="root"
set mysql_password=""

echo Importando la base de datos desde %sql_file%...

"%mysql_path%" -u %mysql_user% -p%mysql_password% < "%sql_file%"

if errorlevel 0 (
    echo La base de datos ha sido importada exitosamente.
) else (
    echo Ha ocurrido un error durante la importación.
)

pause