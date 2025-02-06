Aprendiendo Filament PHP desde cero a experto

Instale la extencion Conventional Commits en VSCode: permite crear los commits desde la propia herramienta de VSCode.

Para crear una base de dato comando:

```
php artisan migrate
```
Instalar filament:

```
composer require filament/filament
```
php artisan filament:install --panels: donde agregamos el nombre del panel o url (dashboard).
php artisan make:filament-user: para crear un usuario administrador.
php artisan make:filament-resource User --generate: para crear un recurso de usuario.

composer require altwaireb/laravel-world

php artisan world:install: para instalar el paquete de paises, estados y ciudades (desde la pagina https://packagist.org/packages/altwaireb/laravel-world?query=altwaireb).
php artisan world:seeder: para crear los datos de paises, estados y ciudades. Se debe agregar antes de artisan -d memory_limit=350M para que no se salga del limite de memoria.
php artisan make:filament-resource Country --generate: para crear un recurso de paises.
php artisan make:filament-resource State --generate: para crear un recurso de estados.
php artisan make:filament-resource City --generate: para crear un recurso de ciudades.
-Agregamos iconos a los recursos: https://filamentphp.com/plugins/icons
-Creacion de modelo Calendar: php artisan make:model Calendar -m
-Creacion de modelo Department: php artisan make:model Department -m
-Creacion de tabla pivot (van a tener relacion con otra tabla):
 php artisan make:migration create_table_user_calendar
 php artisan make:migration create_table_user_department


