<?php

namespace Kafyx3\PermissionGate;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class PermissionGateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    protected function registerBladeDirectives(): void
    {
        // Override @can to support inline role arrays.
        // @can('edit-alat', ['admin', 'petugas']) — handled by this package
        // @can('edit-alat') or @can('update', $post) — falls back to Laravel Gate/Policy
        Blade::directive('can', function (string $expression) {
            return "<?php if (\\Kafyx3\\PermissionGate\\PermissionGate::can({$expression})): ?>";
        });

        Blade::directive('endcan', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('elsecan', function (string $expression) {
            return "<?php elseif (\\Kafyx3\\PermissionGate\\PermissionGate::can({$expression})): ?>";
        });

        Blade::directive('cannot', function (string $expression) {
            return "<?php if (!\\Kafyx3\\PermissionGate\\PermissionGate::can({$expression})): ?>";
        });

        Blade::directive('endcannot', function () {
            return '<?php endif; ?>';
        });

        // @hasRole('admin', 'petugas') or @hasRole(['admin', 'petugas'])
        Blade::directive('hasRole', function (string $expression) {
            return "<?php if (\\Kafyx3\\PermissionGate\\PermissionGate::hasRole({$expression})): ?>";
        });

        Blade::directive('endhasRole', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('elsehasRole', function (string $expression) {
            return "<?php elseif (\\Kafyx3\\PermissionGate\\PermissionGate::hasRole({$expression})): ?>";
        });
    }
}
