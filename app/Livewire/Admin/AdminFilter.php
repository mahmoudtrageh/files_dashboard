<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Admin;
use App\Livewire\Traits\WithDataTable;

class AdminFilter extends Component
{
    use WithDataTable;
    
    // Define searchable fields for this component
    protected $searchFields = ['name', 'email'];
    
    // Customizable per page options
    protected $perPageOptions = [10, 25, 50, 100];
    
    protected $listeners = [
        'refresh' => '$refresh'
    ];
    
    public function mount()
    {
        $this->perPage = request()->query('per_page', $this->perPage);
        $this->search = request()->query('search', $this->search);
        $this->status = request()->query('status', $this->status);
    }
    
    protected function getAdmins()
    {
        $status = $this->status === 'active' ? true : ($this->status === 'inactive' ? false : null);
        
        return Admin::query()
            ->with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    foreach ($this->searchFields as $field) {
                        $query->orWhere($field, 'like', '%' . $this->search . '%');
                    }
                });
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $admins = $this->getAdmins()->paginate($this->perPage);
        
        return view('livewire.admin.admin-filter', [
            'admins' => $admins,
            'perPageOptions' => $this->perPageOptions
        ]);
    }
}