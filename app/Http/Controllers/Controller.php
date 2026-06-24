<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

abstract class Controller
{
    /**
     * Standardizes service-to-controller redirection logic.
     *
     * @param callable $callback
     * @param string $route The success redirect route
     * @return RedirectResponse
     */
    protected function handleServiceCall(callable $callback, string $route = 'index'): RedirectResponse
    {
        $result = $callback();

        if (!($result['status'] ?? false)) {
            return back()
                ->withInput()
                ->with('error', $result['message'] ?? __('An unexpected error occurred.'));
        }

        if ($route == 'back') {
            return redirect()
                ->back()
                ->with('success', $result['message']);
        }

        return redirect()
            ->route($route)
            ->with('success', $result['message']);
    }
}
