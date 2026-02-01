DawaPlus Admin Web

1) Merge this ZIP into Laravel root.
2) Kernel.php: add routeMiddleware alias is_admin => App\Http\Middleware\IsAdmin::class
3) routes/web.php: require base_path('routes/admin_web.php');
4) php artisan migrate
5) php artisan db:seed --class=SampleSeeder
Login: admin@dawaplus.local / Admin@12345
Open: /admin
