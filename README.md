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
 -Creacion de alta de calendarios: php artisan make:filament-resource Calendar --generate
 -Creacion de alta de departamentos: php artisan make:filament-resource Department
 -Creacion del recurso de Timesheet: php artisan make:filament-resource Timesheet --generate

-Creacion del recurso Holiday: php artisan make:filament-resource Holiday --generate

- protected $guarded = [];? Es como un comodin para que la validacion lo salte, y se puedan crear los datos sin necesidad de validar los campos. Solo se usa mientras estamos en desarrollo, para cuando estemos en produccion se debe usar en la raiz de fillable con los campos que se van a usar.

Usando la documentacion de filament para cambiar estilos a los tipos de holidays: usamos el badge() y el color() para cambiar el color de los badges. Ejemplo:

```
Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'declined' => 'danger',
                        'approved' => 'success',
                        'pending' => 'warning',
                    })
```
-Creacion de widget de filamento para nuestro panel de dashboard. Para ello vamos a la pagina de filament y buscamos el widget que queremos usar. En este caso usamos el widget de informacion de filamento (Stats overview widgets).
En la terminal ponemos: php artisan make:filament-widget StatsOverview --stats-overview.
Se trabaja en el archivo StatsOverview.php. Ejemplo:

```
use Filament\Widgets\StatsOverviewWidget\Stat;

protected function getStats(): array
{
    return [
        Stat::make('Unique views', '192.1k'),
        Stat::make('Bounce rate', '21%'),
        Stat::make('Average time on page', '3:12'),
    ];
}
```
Y de esta forma agregamos tres widgets a nuestro dashboard.

