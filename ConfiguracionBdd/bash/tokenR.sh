#!/usr/bin/bash

# datos del servidor
DB_USER="root"
DB_PASSWORD="..1791.."
DB_NAME="sesamo"
DB_HOST="localhost"
# ruta para lso og
LOG_FILE="/etc/sesamo/bash/eliminar_codigos_recuperacion.log"

# eliminar cdigos de recuperacion de mas de 30 min, se gaurdan, comprobamos con la ctual
SQL_QUERY="DELETE FROM recuperarContrasena WHERE expiracion < NOW();"

# vamos a registralos comands
{
  echo "[$(date)] Eliminando codigos de recuperacion antiguos."
  mysql -u$DB_USER -p$DB_PASSWORD -h$DB_HOST $DB_NAME -e "$SQL_QUERY"
  echo "[$(date)] Completado."
} >> $LOG_FILE 2>&1
