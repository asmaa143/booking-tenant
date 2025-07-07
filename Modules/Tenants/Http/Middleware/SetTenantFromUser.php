<?php
namespace Modules\Tenants\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Tenants\Models\Tenant;

class SetTenantFromUser
{
    public function handle(Request $request, Closure $next)
    {
        if ($user = auth()->user()) {
            $tenantId = $user->tenant_id;

            if ($tenantId) {
                $tenant = Tenant::find($tenantId);

                if ($tenant) {
                    $tenant->makeCurrent();
                }
            }
        }

        return $next($request);
    }
}
