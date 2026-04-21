<p>

## Evolvere - Sistema de Gestión Inmobiliaria

Evolvere es una plataforma web desarrollada en Laravel 12 diseñada para optimizar la administración de propiedades. El sistema permite gestionar un catálogo inmobiliario de forma eficiente, ofreciendo herramientas de búsqueda, control de estados de las propiedades y un registro detallado de auditoría para garantizar la integridad de la información.


## Funcionalidades Implementadas
- **Gestión de Propiedades:** Alta, baja, modificación y visualización de inmuebles (Casas, Departamentos, etc.).

- **Buscador:** Filtrado dinámico por nombre, tipo de propiedad y rango de precios.

- **Sistema de Autenticación:** Acceso restringido mediante login para personal autorizado.

- **Auditoría de Cambios:** Historial detallado que registra qué usuario realizó cambios, en qué fecha y qué valores específicos fueron modificados (Antes vs. Después).



## Perfiles de Usuario y Permisos

- **Administrador.** 
Control total del sistema: Crear, editar, eliminar propiedades y visualizar el historial de auditoría completo.

**Para el acceso al perfil ADMINISTRADOR tendremos los siguientes datos:**
-> Usuario: admin 
-> Password: admin123

- **Operador.** Permisos limitados: Puede cargar y modificar propiedades, pero tiene restringida la edición de precios y ciertas acciones administrativas.

**Para el acceso al perfil OPERADOR tendremos los siguientes datos:**
-> Usuario: operador1 
-> Password: operador123


## Instrucciones para correr el proyecto de manera Local

**Para configurar el entorno de desarrollo:**

- 1.**Clonar el repositorio:** 
        git clone https://github.com/ferrdel/inmobiliaria.git
        cd inmobiliaria

- 2.**Instalar dependencias de PHP (Composer):** 
        composer install

- 3.**Configurar el archivo de entorno:**
        - Copia el archivo .env.example y cámbiale el nombre a .env.
        - Configura tus credenciales de base de datos (MySQL) y de Correo (Gmail) dentro del archivo .env.

- 4.**Generar la clave de la aplicación:**
        php artisan key:generate

- 5.**Ejecutar migraciones y seeders:**
        php artisan migrate --seed

- 6.**Iniciar el servidor local:**
        php artisan serve

        El proyecto estará disponible en http://127.0.0.1:8000.



