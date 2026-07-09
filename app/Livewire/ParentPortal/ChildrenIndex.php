<?php

namespace App\Livewire\ParentPortal;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ChildrenIndex extends Component
{
    public function mount(): void
    {
        abort_unless(Auth::user()->guardian, 403);
    }

    public function render()
    {
        return view('livewire.parent-portal.children-index', [
            'children' => Auth::user()->guardian->students()->with(['user', 'schoolClass', 'section'])->get(),
        ]);
    }
}
