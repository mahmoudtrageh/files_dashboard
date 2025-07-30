<?php

namespace App\Livewire\Traits;

use Livewire\WithPagination;

trait WithDataTable
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 3;
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 3],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];
    
    public function updating($propertyName)
    {
        if (in_array($propertyName, ['search', 'perPage', 'status', 'sortField', 'sortDirection'])) {
            $this->resetPage();
            $this->dispatch('filterUpdated');
        }
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function resetFilters()
    {
        $this->reset(['search', 'perPage', 'status']);
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
        $this->dispatch('filterUpdated');
    }

    public function paginationView()
    {
        return 'admin.components.pagination';
    }
}