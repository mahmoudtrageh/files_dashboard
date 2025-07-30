<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from various sources (priority order)
        $locale = $this->getLocale($request);

        // Set application locale
        App::setLocale($locale);

        // Store in session for future requests
        Session::put('locale', $locale);

        // Set direction for RTL languages
        $direction = $this->getDirection($locale);
        view()->share('direction', $direction);
        view()->share('locale', $locale);

        return $next($request);
    }

    /**
     * Get locale from various sources
     */
    private function getLocale(Request $request): string
    {
        // 1. Check URL parameter (highest priority)
        if ($request->has('lang') && $this->isValidLocale($request->get('lang'))) {
            return $request->get('lang');
        }

        // 2. Check session
        if (Session::has('locale') && $this->isValidLocale(Session::get('locale'))) {
            return Session::get('locale');
        }

        // 3. Check user preference (if authenticated)
        if (auth()->guard('admin')->check()) {
            $user = auth()->guard('admin')->user();
            if (isset($user->locale) && $this->isValidLocale($user->locale)) {
                return $user->locale;
            }
        }

        // 4. Check browser preference
        $browserLocale = $request->getPreferredLanguage($this->getAvailableLocales());
        if ($browserLocale && $this->isValidLocale($browserLocale)) {
            return $browserLocale;
        }

        // 5. Fall back to default
        return config('app.locale', 'ar');
    }

    /**
     * Check if locale is valid/supported
     */
    private function isValidLocale(string $locale): bool
    {
        return in_array($locale, $this->getAvailableLocales());
    }

    /**
     * Get list of available locales
     */
    private function getAvailableLocales(): array
    {
        return ['en', 'ar'];
    }

    /**
     * Get text direction for locale
     */
    private function getDirection(string $locale): string
    {
        $rtlLocales = ['ar', 'fa', 'he', 'ur'];

        return in_array($locale, $rtlLocales) ? 'rtl' : 'ltr';
    }
}
