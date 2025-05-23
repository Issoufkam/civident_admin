<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\User;
use App\Enums\UserRole;

class SidebarComposer
{
    public function compose(View $view)
    {
        $totalAgents = User::count();
        $view->with('totalAgents', $totalAgents);
    }
}
