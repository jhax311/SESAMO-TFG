#!/bin/bash

# datos del servidor
DB_USER="root"
DB_PASSWORD="..1791.."
DB_NAME="sesamo"
DB_HOST="localhost"

# Ruta del archivo de log
LOG_FILE="/etc/sesamo/bash/eliminar_codigos_recuperacion.log"

# eliminar códigos de recuperación de mas de 30 min
SQL_QUERY="DELETE FROM recuperarContrasena WHERE expiracion < NOW() - INTERVAL 30 MINUTE;"

# Ejecutar el comando SQL y registrar la salida y errores en el archivo de log
{
  echo "[$(date)] Ejecutando eliminación de códigos de recuperación antiguos."
  mysql -u$DB_USER -p$DB_PASSWORD -h$DB_HOST $DB_NAME -e "$SQL_QUERY"
  echo "[$(date)] Completado."
} >> $LOG_FILE 2>&1
