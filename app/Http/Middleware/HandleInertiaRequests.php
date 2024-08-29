<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'companyLogo' => fn () => Helper::getCompanyLogo(),
            'auth.user' => fn () => $this->getUserData($request),
            'userPermissions' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
            'userRoles' => $request->user() ? $request->user()->role : [],
            'breadcrumb' => Breadcrumbs::exists() ? Breadcrumbs::generate() : [],
        ]);
    }

    /**
     * Retrieve user data for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request  The request object.
     * @return \App\Models\User|null The user data or null if no user is authenticated.
     */
    private function getUserData(Request $request): ?User
    {
        // Retrieve the authenticated user from the request.
        $user = $request->user();

        // If a user is authenticated, hide certain attributes
        // and return the user data. Otherwise, return null.
        return $user
            ? $user->makeHidden(
                'email_token', // Hide the email token attribute.
                'email_token_expired_at', // Hide the email token expiration attribute.
                'must_change_password' // Hide the must change password attribute.
            )
            : null;
    }
}
