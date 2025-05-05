#!/bin/bash

# Crear directorio de backup si no existe
mkdir -p .backup_config

# Hacer backup de los archivos si existen
if [ -f .env ]; then
    cp .env .backup_config/.env.backup
    git checkout -- .env
fi

if [ -f config/filesystems.php ]; then
    cp config/filesystems.php .backup_config/filesystems.php.backup
    git checkout -- config/filesystems.php
fi

echo "Backup completed and files reset for git pull" 