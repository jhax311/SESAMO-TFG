#!/usr/bin/bash

# datos del servidor
DB_USER="root"
DB_PASSWORD="..1791.."
DB_NAME="sesamo"
DB_HOST="localhost"

# Ruta del archivo de log
LOG_FILE="/etc/sesamo/bash/eliminar_tokens_antiguos.log"

# eliminar tokens de usuarios que tengan más de 3 días
SQL_QUERY="DELETE FROM usuarios_token WHERE Fecha < NOW() - INTERVAL 3 DAY;"

# Ejecutar el comando SQL y registrar la salida y errores en el archivo de log
{
  echo "[$(date)] Ejecutando eliminación de tokens antiguos."
  mysql -u$DB_USER -p$DB_PASSWORD -h$DB_HOST $DB_NAME -e "$SQL_QUERY"
  echo "[$(date)] Completado."
} >> $LOG_FILE 2>&1
