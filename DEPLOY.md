# Despliegue en VPS

El servicio `proxy` es el único punto de entrada de Docker. `frontend`, `backend` y `mysql` solo son accesibles dentro de la red `dumath`. Adminer se activa únicamente con el perfil `tools` y se publica en `127.0.0.1`.

## Preparación

1. Instalar Docker Engine y el plugin Docker Compose en la VPS.
2. Clonar el repositorio y copiar las variables:

```sh
cp .env.example .env
```

3. Cambiar `DOMAIN`, `APP_URL`, `FRONTEND_URL`, `SANCTUM_STATEFUL_DOMAINS`, `DB_PASSWORD` y `DB_ROOT_PASSWORD`.
4. Generar una clave Laravel y guardarla en `APP_KEY`:

```sh
cd SistemaPrubasBeta
php artisan key:generate --show
cd ..
```

5. Mantener `HTTP_BIND=127.0.0.1` y `HTTP_PORT=8080` si Nginx del VPS administra los puertos 80/443.

## Nginx del VPS

El Nginx del host termina TLS y reenvía al proxy Nginx de Docker:

```nginx
server {
    listen 80;
    server_name example.com www.example.com;

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Después de emitir el certificado con Certbot, agregar `listen 443 ssl` y sus rutas de certificado al mismo bloque.

## Arranque

```sh
docker compose --env-file .env -f docker-compose.prod.yml up -d --build
docker compose --env-file .env -f docker-compose.prod.yml ps
docker compose --env-file .env -f docker-compose.prod.yml logs -f proxy backend
```

La aplicación queda disponible por el dominio configurado. El endpoint de comprobación es `/health`.

## Adminer

Activarlo solo cuando sea necesario:

```sh
docker compose --env-file .env -f docker-compose.prod.yml --profile tools up -d adminer
```

Usar un túnel SSH hacia `127.0.0.1:8080` de la VPS. En Adminer, el servidor MySQL es `mysql`, el usuario es `DB_USERNAME` y la base es `DB_DATABASE`.

## Actualización

```sh
git pull origin main
docker compose --env-file .env -f docker-compose.prod.yml up -d --build
```

El volumen `mysql_data` conserva la base de datos durante las actualizaciones. No ejecutar `docker compose down -v` en producción.
