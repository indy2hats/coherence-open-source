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
            'auth.user' => fn () => $this->getUserData($request),
            'breadcrumb' => fn () => Breadcrumbs::exists() ? Breadcrumbs::generate() : [],
            'site_settings' => fn () => $this->getSiteSettings(),
            'user_permissions' => fn () => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
            'user_roles' => fn () => $request->user() ? $request->user()->role : [],
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

    /**
     * Retrieve the site settings.
     *
     * @return array<string, mixed> The site settings.
     */
    private function getSiteSettings(): array
    {
        return [
            'company_logo' => Helper::getCompanyLogo(),
            'project_view' => Helper::getProjectView(),
            'santa_enabled' => config('general.santa.enabled', false),
            'show_daily_status_report_page' => Helper::showDailyStatusReportPage(),
        ];
    }
}
