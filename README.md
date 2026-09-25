# MPC Service Desk

Sistema de Mesa de Ayuda para la Municipalidad Provincial de Casma. Gestión de tickets, áreas, personal, bienes y diseño.

## Stack

- **Backend:** Laravel 13 + PHP 8.3 + MySQL 9
- **Frontend:** React 19 + TypeScript + Vite 8 + Tailwind CSS 4
- **Auth:** JWT (tymon/jwt-auth)

## Requisitos

- PHP 8.3+
- Composer
- Node.js 18+
- MySQL 8+ o 9+
- npm

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/Yordax36/Titckets-MPC.git
cd Titckets-MPC
```

### 2. Backend (Laravel)

```bash
cd backend

# Instalar dependencias
composer install

# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Generar JWT secret
php artisan jwt:secret

# Crear base de datos
mysql -u root -e "CREATE DATABASE helpdesk"

# Configurar .env (editar credenciales de BD si es necesario)
# DB_DATABASE=helpdesk
# DB_USERNAME=root
# DB_PASSWORD=

# Ejecutar migraciones + seeders
php artisan migrate --seed
```

### 3. Frontend (React)

```bash
cd frontend

# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev
```

### 4. Iniciar Backend

```bash
cd backend
php artisan serve
```

El backend corre en `http://localhost:8000` y el frontend en `http://localhost:5173`. El proxy de Vite redirige `/api` al backend automáticamente.

## Credenciales por defecto

### Administrador
- **Email:** `otic@municasma.gob.pe`
- **Contraseña:** `OTIC#2026`

### Usuarios de Área
- **Contraseña general:** `MPC@2026!`
- Cada área tiene su correo institucional (ej: `alcaldia@municasma.gob.pe`)

## Estructura del proyecto

```
Tickets/
├── backend/                    # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/   # Controladores API
│   │   ├── Models/             # Modelos Eloquent
│   │   ├── Services/           # Servicios de lógica
│   │   ├── Policies/           # Políticas de autorización
│   │   └── Helpers/            # Helpers (AuditHelper)
│   ├── database/
│   │   ├── migrations/         # Migraciones de BD
│   │   └── seeders/            # Seeders (datos iniciales)
│   ├── routes/api.php          # Rutas API
│   └── config/                 # Configuraciones
│
├── frontend/                   # React App
│   ├── src/
│   │   ├── api/                # Funciones API (axios)
│   │   ├── components/         # Componentes reutilizables
│   │   │   ├── layout/         # Sidebar, Navbar, Layout
│   │   │   ├── tickets/        # Componentes de tickets
│   │   │   └── ui/             # Botones, Modals, Tables, etc.
│   │   ├── pages/              # Páginas/rutas
│   │   ├── hooks/              # Custom hooks (useAuth, etc.)
│   │   ├── store/              # Zustand stores
│   │   ├── routes/             # Router de React
│   │   └── utils/              # Constantes y formateadores
│   └── vite.config.ts
│
└── README.md
```

## Funcionalidades

### Administrador
- Gestión de tickets con workflow de 7 estados
- Asignación de técnicos
- Gestión de áreas, personal, cargos y designaciones
- Inventario de bienes con mantenimiento
- Auditoría completa del sistema
- Configuración del sistema (nombre, logo)
- Generación de PDFs de tickets

### Técnico
- Visualización de tickets asignados
- Cambio de estado y comentarios
- Registro de evidencias (fotos)
- Gestión de bienes y mantenimiento

### Área Usuaria
- Creación de tickets
- Seguimiento de tickets propios
- Visualización de bienes del área
- Perfil del área

## Roles

| Rol | Descripción |
|-----|-------------|
| Administrador | Acceso total al sistema |
| Tecnico | Mesa de ayuda, tickets asignados |
| Area Usuaria | Crear tickets, ver bienes del área |

## Comandos útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Resetear BD y re-crear datos
php artisan migrate:fresh --seed

# Crear JWT secret
php artisan jwt:secret

# Limpiar cache
php artisan cache:clear
php artisan config:clear

# Tinker (consola interactiva)
php artisan tinker
```

## Variables de entorno importantes

```env
# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helpdesk
DB_USERNAME=root
DB_PASSWORD=

# JWT
JWT_SECRET=tu-secret-aqui

# Frontend (en frontend/.env si es necesario)
VITE_API_URL=http://localhost:8000
```

## Licencia

Proyecto privado - Municipalidad Provincial de Casma
