<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Livewire\Traits\WithDataTable;
use Spatie\Permission\Models\Role;

class RoleFilter extends Component
{
    use WithDataTable;
    
    // Define searchable fields for this component
    protected $searchFields = ['name'];
    
    // Customizable per page options
    protected $perPageOptions = [10, 25, 50, 100];
    
    protected $listeners = [
        'refresh' => '$refresh'
    ];
    
    public function mount()
    {
        $this->perPage = request()->query('per_page', $this->perPage);
        $this->search = request()->query('search', $this->search);
    }
    
    protected function getRoles()
    {        
        return Role::query()
            ->with('permissions')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    foreach ($this->searchFields as $field) {
                        $query->orWhere($field, 'like', '%' . $this->search . '%');
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $roles = $this->getRoles()->paginate($this->perPage);
        
        return view('livewire.admin.role-filter', [
            'roles' => $roles,
            'perPageOptions' => $this->perPageOptions
        ]);
    }
}