<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
// rahul code written
class RolePermissionMiddleware
{
    public function handle($request, Closure $next)
    {
        $roleId = session('user.role_id');

        // Handle unauthenticated or missing role
        if (!$roleId) {
            return redirect()->route('index')->with('error', 'Unauthorized access.');
        }

        $requestedPath = '/' . $request->path(); // Example: /admin/hc-other-copy

        // ✅ Always allow dashboard path
        if ($requestedPath === '/admin/index') {
            return $next($request);
        }

        // ✅ Check if this path exists in submenu_master
        $allMenuUrls = DB::table('submenu_master')->pluck('url')->filter()->toArray();

        // ✅ If current path is not defined in submenu_master, allow it
        if (!in_array($requestedPath, $allMenuUrls)) {
            return $next($request);
        }

        // ✅ Check permission only if path exists in submenu_master
        if (!Session::has('allowed_urls')) {
            $allowedUrls = DB::table('role_permissions')
                ->join('submenu_master', 'submenu_master.submenu_id', '=', 'role_permissions.submenu_id')
                ->where('role_permissions.role_id', $roleId)
                ->pluck('submenu_master.url')
                ->filter()
                ->toArray();

            Session::put('allowed_urls', $allowedUrls);
        } else {
            $allowedUrls = Session::get('allowed_urls');
        }

        // ✅ If the user does not have permission to the matched URL
        if (!in_array($requestedPath, $allowedUrls)) {
            return response()->view('admin.unauthorized'); // create this blade file
        }

        return $next($request);
    }
}
