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
***Creacion de paneles y recursos***
***Panel de dashboard***
php artisan filament:install --panels: donde agregamos el nombre del panel o url (dashboard).
php artisan make:filament-user: para crear un usuario administrador.
php artisan make:filament-resource User --generate: para crear un recurso de usuario.

composer require altwaireb/laravel-world

-php artisan world:install: para instalar el paquete de paises, estados y ciudades (desde la pagina https://packagist.org/packages/altwaireb/laravel-world?query=altwaireb).
-php artisan world:seeder: para crear los datos de paises, estados y ciudades. Se debe agregar antes de artisan -d memory_limit=350M para que no se salga del limite de memoria.
-php artisan make:filament-resource Country --generate: para crear un recurso de paises.
-php artisan make:filament-resource State --generate: para crear un recurso de estados.
-php artisan make:filament-resource City --generate: para crear un recurso de ciudades.
-Agregamos iconos a los recursos: https://filamentphp.com/plugins/icons

 -Creacion de alta de calendarios: php artisan make:filament-resource Calendar --generate
 -Creacion de alta de departamentos: php artisan make:filament-resource Department
 -Creacion del recurso de Timesheet: php artisan make:filament-resource Timesheet --generate

-Creacion del recurso Holiday: php artisan make:filament-resource Holiday --generate

-Para cambiar el nombre del panel de dashboard, vamos a la carpeta Providers y en el archivo DashboardPanelProvider.php, y cambiamos el id y el path.

-Para cambiar el color de los botones en el panel de dashboard, vamos a la carpeta Providers y en el archivo DashboardPanelProvider.php, y cambiamos el color en el array colors.

***Panel de personal***

-Creamos un nuevo panel que sera el personal de cada empleado:
php artisan make:filament-panel. Luego en la pregunta what is the ID? ponemos el nombre del panel que queremos crear.

-Creamos un recurso Holiday para el panel personal: php artisan make:filament-resource

-Para que el usuario solo pueda ver sus propios datos, agregamos el siguiente codigo en el archivo CreateHoliday.php:

protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['user_id'] = auth()->id();
 
    return $data;
}

Esta funcion permite rellenar los campos de user-id y tipo en el momento de enviar y crear el registro.

-Creacion del recurso Timesheet en el panel personal: php artisan make:filament-resource

-Para que se muestre la info solo del usuario logueado, agregamos el siguiente codigo en el archivo TimesheetResource.php y en HolidayResource.php:

public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
}

-Para crear un action(boton)en el recurso Timesheet, agregamos el siguiente codigo en el archivo ListTimesheets.php:

protected function getHeaderActions(): array
{
    return [
        Action::make('In work')
        ->label('Entrar a trabajar')
    ];
}

Para ello vamos a la pagina de la documentacion de filament en: Panel builder > getting started > Pages > header Actions.
Luego para personalizar el boton: Actions > Advanced actions y estraemos el label.

-Para crear un action que requiera confirmacion, agregamos el siguiente codigo en el archivo ListTimesheets.php:

```
->requiresConfirmation()
```

Si quiero ver si la funcion que estoy creando esta funcionando y que se muestre por pantalla mientras estoy en desrrollo agrego en el lugar que quiero verificar el siguiente codigo:

```
dd('test');
```
-Alter table?

-Para cambiar el color de los botones en el panel de personal, vamos a la carpeta Providers y en el archivo PersonalPanelProvider.php, y cambiamos el color en el array colors.


***Creacion de modelos y tablas***
-Creacion de modelo Calendar: php artisan make:model Calendar -m
-Creacion de modelo Department: php artisan make:model Department -m
-Creacion de tabla pivot (van a tener relacion con otra tabla):
 php artisan make:migration create_table_user_calendar
 php artisan make:migration create_table_user_department

***Consideraciones***
- protected $guarded = []; Es como un comodin para que la validacion lo salte, y se puedan crear los datos sin necesidad de validar los campos. Solo se usa mientras estamos en desarrollo, para cuando estemos en produccion se debe usar en la raiz de fillable con los campos que se van a usar. Esto se utiliza en el modelo.

-Para que la pagina principal sea el panel personal y no la pagina de inicio de laravel, en el archivo web.php, cambiamos la ruta de la pagina principal por la ruta del panel personal.

    Route::get('/', function () {
        return redirect('/personal');
    });


***Cambio de estilos a los tipos de holidays***
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


***Creacion de widget de filamento para nuestro panel de dashboard***
-Creacion de widget de filamento para nuestro panel de dashboard. Para ello vamos a la pagina de filament y buscamos el widget que queremos usar. En este caso usamos el widget de informacion de filamento (Stats overview widgets).
-En la terminal ponemos: php artisan make:filament-widget StatsOverview --stats-overview.
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

***Envios de emails***

-Usamos Mailgun para enviar emails: https://mailgun.com/

-Creamos una clase make:mail para enviar emails: php artisan make:mail HolidayPending

-De momento creamos un comando para enviar emails: php artisan make:command TestEmails


-Algunas consideraciones a tener en cuenta a la hora de enviar emails:

    -En el archivo mail.php, en el array mailers, en el array smtp, tenemos que hacer algunos agregados:
        -verify_peer = false.
        -local_domain = env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)). aqui agregamos el parse_url para que tome el host de la url de la app.

    -En el archivo .env, nos aseguramos que este el siguiente codigo:
        MAIL_MAILER=smt

    -En el archivo HolidayPending.php agregamos el From en la siguiente funcion:

        public function envelope(): Envelope
        {
            return new Envelope(
                from: env('MAIL_USERNAME'),
                subject: 'Solicitud de Vacaciones Pendientes',
            );
        }

-php artisan make:mail HolidayApproved. Creamos este mail para que cuando se apruebe una solicitud de vacaciones, se envie un email al usuario.

***Notificaciones***

-Para hacer notificaciones de la base de datos, creamos una tabla de notificaciones: php artisan notifications:table.
-Luego hacemos php artisan migrate. Para crear la tabla en la base de datos.

NOTA: para que se actualice la base de datos y se refresquen las actualizaciones debemos usar las colas y puede que no esten activadas, para ello debemos ejecutar el comando:

php artisan queue:work

hará que estos jobs se lanzen y ya salgan las notificaciones

****Roles y Permisos****

-Utilizaremos un plugin para gestionar roles y permisos: 
en la pagina Filament/pluging/Shield

-Para instalar el plugin: composer require bezhansalleh/filament-shield

En el modelo de User vamos a importar:

use Spatie\Permission\Traits\HasRoles;

-Luego publicamos la configuracion del plugin:
php artisan vendor:publish --tag="filament-shield-config"

-Luego registramos el plugin en el panel que deseamos:
->plugins([
    \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
])

-Con el comando: php artisan shield:publish admin, creamos el recurso de roles


***Importacion y Exportacion de datos***

***Exportacion de datos***

-Primero instalamos el plugin "Excel Export":

    composer require pxlrbt/filament-excel

-Luego en el recurso de personal/TimesheetResource.php, agregamos el siguiente codigo:

    ```
    use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

    ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
                ]),
            ]);
    ```

***Importacion de datos***

-Primero instalamos el plugin "Excel Import":
    composer require eightynine/filament-excel-import

NOTA: para que se puedan importar los datos, debemos tener el campo user_id en la tabla timesheet, y que este campo sea un campo de tipo integer. Las columnas en el excel a importar deben ser las mismas que las columnas de la tabla timesheet.

Para customizar el excel a importar, debemos crear un archivo en la carpeta app/Imports/MyTimesheetImport.php (php artisan make:import MyTimesheetImport). Para ello usamos la documentacion de LARAVEL EXCEL (importing to collection y Heading row) Este archivo debe tener la siguiente estructura:

        ```
        namespace App\Imports;

        use Illuminate\Support\Collection;
        use Maatwebsite\Excel\Concerns\ToCollection;

        class MyTimesheetImport implements ToCollection, WithHeadingRow
        {
            public function collection(Collection $rows)
            {
                //
                foreach ($rows as $row)
                {
                    // dd($row);
                    $calendar_id = Calendar::where('name',$row['calendario'])->first();
                    if($calendar_id != null){

                        Timesheet::create([
                            'calendar_id' => $calendar_id->id,
                            'user_id' => Auth::user()->id,
                            'type' => $row['tipo'],
                            'day_in' => $row['hora_de_entrada'],
                            'day_out' => $row['hora_de_salida'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
        }
        ```
En el archivo ListTimesheets.php, agregamos el siguiente codigo:

        ExcelImportAction::make()->color('primary')->use(MyTimesheetImport::class),

        Para decirle que debe importar del archivo que hayamos creado y no del que viene por defecto en el plugin.


***Crear PFF***
-Para poder crear pdfs, debemos instalar:
    composer require barryvdh/laravel-dompdf

NOTA: filament da un error al intentar mandar pdfs, para ello debemos crear una url.


-Creamos un controlador para crear la url: php artisan make:controller PdfController

