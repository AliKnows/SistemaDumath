# DUMATH

## Entorno Docker

1. Copia `.env.example` a `.env` y cambia las contraseñas.
2. Ejecuta `docker compose up -d --build`.
3. Abre `http://localhost` para el frontend.
4. Adminer queda en `http://localhost:8080`: servidor `mysql`, usuario y base de datos definidos en `.env`.

El navegador usa `/api`; Nginx lo envía internamente al servicio `backend` en el puerto `8000`. Laravel espera a MySQL, ejecuta migraciones y conserva los datos en el volumen `mysql_data`.
