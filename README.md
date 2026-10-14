# PowerFit Gym — Sistema de Gestión

Proyecto final de **ISW304 · Desarrollo de Proyectos con Software Libre** (UAPA, Grupo II).

Aplicación web para administrar un gimnasio: miembros, membresías, pagos, asistencia y clases grupales.
Continúa las Actividades 1 a 3: usa la base de datos MySQL `powerfit_gym` (Actividad 2) y se despliega
sobre Apache + PHP en el servidor Xubuntu con firewall UFW (Actividad 3).

## Tecnologías (todas software libre)

| Capa | Herramienta |
|------|-------------|
| Lenguaje | PHP 8.3 |
| Framework | Laravel 11 |
| Base de datos | MySQL 8.0 (esquema existente, sin migraciones) |
| Servidor web | Apache 2.4 (mod_php) |
| Sistema operativo | Xubuntu 24.04 LTS |

## Módulos (CRUD completos)

| Módulo | Ruta | Acceso | Requerimiento |
|--------|------|--------|---------------|
| Usuarios y miembros | `/usuarios` | Administrador | RF-01, RF-02, RF-08, RF-12 |
| Tipos de membresía | `/tipos` | Administrador | RF-03 |
| Membresías | `/membresias` | Administrador, Recepcionista | RF-04 |
| Pagos | `/pagos` | Administrador, Recepcionista | RF-05, RF-06 |
| Asistencia | `/asistencias` | Administrador, Recepcionista | RF-07 |
| Clases grupales | `/clases` | Administrador, Recepcionista, Entrenador | — |
| Panel con indicadores | `/panel` | Todos los roles (indicadores: Administrador y Recepcionista) | RF-13 |

Seguridad: inicio de sesión con contraseñas cifradas (bcrypt), protección CSRF, validación de formularios
y control de acceso por rol (RF-12, RNF-02, RNF-03).

## Instalación (resumen)

```bash
composer create-project laravel/laravel:^11.0 . --no-scripts   # en un directorio vacío
# copiar el contenido de este repositorio encima del proyecto
cp deploy/env.ejemplo .env       # y editar DB_PASSWORD
php artisan key:generate
sudo mysql powerfit_gym < database/sql/datos_demo.sql   # datos de prueba (opcional)
```

El archivo `deploy/powerfit.conf` es el sitio virtual de Apache (DocumentRoot en `public/`).

## Usuarios de prueba

Contraseña de todos: `PowerFit2026`

| Rol | Correo |
|-----|--------|
| Administrador | admin@powerfit.test |
| Recepcionista | recepcion@powerfit.test |
| Entrenador | entrenador1@powerfit.test |
| Miembro | miembro1@powerfit.test |

## Estructura del código propio

- `app/Models/` — modelos Eloquent mapeados a las tablas existentes.
- `app/Http/Controllers/CrudController.php` — lógica CRUD reutilizable; cada módulo la extiende.
- `app/Http/Middleware/RolRequerido.php` — control de acceso por rol.
- `routes/web.php` — rutas y permisos.
- `resources/views/` — plantilla, login, panel y vistas genéricas de listado y formulario.
- `database/sql/datos_demo.sql` — datos de demostración.
