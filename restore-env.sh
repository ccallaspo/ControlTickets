#!/bin/bash

# Restaurar archivos si existen los backups
if [ -f .backup_config/.env.backup ]; then
    cp .backup_config/.env.backup .env
fi

if [ -f .backup_config/filesystems.php.backup ]; then
    cp .backup_config/filesystems.php.backup config/filesystems.php
fi

# Limpiar directorio de backup
rm -rf .backup_config

echo "Configuration files restored" 