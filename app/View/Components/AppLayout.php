<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $backUrl;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->backUrl = $this->determineBackUrl();
    }

    /**
     * Determine the logical back URL based on current route.
     */
    protected function determineBackUrl(): ?string
    {
        $route = request()->route();
        if (! $route) {
            return null;
        }

        $name = $route->getName();
        if (! $name || $name === 'dashboard') {
            return null;
        }

        // If it's a sub-page (create, edit, show), go back to index
        if (preg_match('/^(.*)\.(create|edit|show)$/', $name, $matches)) {
            $indexRoute = $matches[1].'.index';
            if (Route::has($indexRoute)) {
                return route($indexRoute);
            }
        }

        // If it's an index page or anything else not dashboard, go to dashboard
        return route('dashboard');
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
