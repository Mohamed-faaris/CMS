<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Redirect based on user role
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('learner')) {
            return redirect()->route('learner.dashboard');
        }

        // No valid role found, logout
        return redirect()->route('login');
    }

    public function render()
    {
        return <<<'HTML'
            <div class="flex items-center justify-center min-h-screen">
                <div class="text-center">
                    <p class="text-gray-600">Redirecting...</p>
                </div>
            </div>
        HTML;
    }
}
