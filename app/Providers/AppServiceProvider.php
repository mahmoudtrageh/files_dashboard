<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadHelpers();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
          // Register custom Blade directives
        $this->registerBladeDirectives();

        // Set default string length for database
        \Illuminate\Database\Schema\Builder::defaultStringLength(191);

        Paginator::defaultView('admin.components.pagination');
    }

     /**
     * Load helper functions
     */
    private function loadHelpers(): void
    {
        $helpersPath = app_path('Helpers/AppHelpers.php');

        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    /**
     * Register custom Blade directives
     */
    private function registerBladeDirectives(): void
    {
        // RTL directive
        Blade::directive('rtl', function () {
            return "<?php echo is_rtl() ? 'rtl' : ''; ?>";
        });

        // LTR directive
        Blade::directive('ltr', function () {
            return "<?php echo !is_rtl() ? 'ltr' : ''; ?>";
        });

        // Format bytes directive
        Blade::directive('bytes', function ($expression) {
            return "<?php echo formatBytes($expression); ?>";
        });

        // Current locale directive
        Blade::directive('locale', function () {
            return "<?php echo current_locale(); ?>";
        });

        // Check permission directive
        Blade::directive('canAdmin', function ($expression) {
            return "<?php if(auth()->guard('admin')->check() && auth()->guard('admin')->user()->can($expression)): ?>";
        });

        Blade::directive('endcanAdmin', function () {
            return "<?php endif; ?>";
        });

        // File icon directive
        Blade::directive('fileIcon', function ($expression) {
            return "<?php echo getFileIcon($expression); ?>";
        });

        // Truncate text directive
        Blade::directive('truncate', function ($expression) {
            $args = explode(',', $expression);
            $text = trim($args[0]);
            $limit = isset($args[1]) ? trim($args[1]) : 100;
            $end = isset($args[2]) ? trim($args[2], " '\"") : '...';

            return "<?php echo truncate_text($text, $limit, '$end'); ?>";
        });
    }
}
