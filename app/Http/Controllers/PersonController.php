<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    // Desteklenen Alanlar
    protected $allowedFields = [
        'name', 'surname', 'age', 'tc', 'email', 'birth_date',
        'gender', 'marital_status', 'profession', 'city', 'country'
    ];

    // Özel Değer Kontrolleri
    protected $allowedGenders = ['E', 'K'];
    protected $allowedMaritalStatuses = ['Bekar', 'Evli', 'Dul', 'Boşanmış'];

    public function filter(Request $request)
    {
        $query = Person::query();
        $filters = $request->input('filters', []);

        foreach ($filters as $field => $filter) {
            if (!in_array($field, $this->allowedFields)) continue;

            $operator = $filter['operator'] ?? '==';
            $value = $filter['value'] ?? null;

            $this->applyFilter($query, $field, $operator, $value);
        }

        return $query->get();
    }

    protected function applyFilter($query, $field, $operator, $value)
    {
        $method = 'filter' . Str::studly($field);
        
        if (method_exists($this, $method)) {
            $this->$method($query, $operator, $value);
        } else {
            $this->applyGeneralFilter($query, $field, $operator, $value);
        }
    }

    // Özel Alan Filtreleri
    protected function filterGender($query, $operator, $value)
    {
        if ($operator === '==') {
            $gender = Str::upper($value);
            if (in_array($gender, $this->allowedGenders)) {
                $query->where('gender', $gender);
            }
        }
    }

    protected function filterMaritalStatus($query, $operator, $value)
    {
        if ($operator === '==') {
            $status = Str::title($value);
            if (in_array($status, $this->allowedMaritalStatuses)) {
                $query->where('marital_status', $status);
            }
        }
    }

    protected function filterBirthDate($query, $operator, $value)
    {
        try {
            $date = Carbon::parse($value);
            $validOperators = ['>', '>=', '<', '<=', '==', '!='];
            
            if (in_array($operator, $validOperators)) {
                $query->whereDate('birth_date', $operator, $date);
            }
        } catch (\Exception $e) {
            // Log error if needed
        }
    }

    // Genel Filtreleme Mantığı
    protected function applyGeneralFilter($query, $field, $operator, $value)
    {
        $type = $this->detectFieldType($field);
        
        switch ($type) {
            case 'numeric':
                $this->applyNumericFilter($query, $field, $operator, $value);
                break;
            
            case 'date':
                $this->filterBirthDate($query, $operator, $value);
                break;
            
            default:
                $this->applyStringFilter($query, $field, $operator, $value);
        }
    }

    protected function detectFieldType($field)
    {
        return match($field) {
            'age', => 'numeric',
            'birth_date' => 'date',
            default => 'string'
        };
    }

    protected function applyNumericFilter($query, $field, $operator, $value)
    {
        $allowedOperators = ['>', '>=', '<', '<=', '==', '!='];
        if (in_array($operator, $allowedOperators) && is_numeric($value)) {
            $query->where($field, $operator, $value);
        }
    }

    protected function applyStringFilter($query, $field, $operator, $value)
    {
        $value = Str::lower($value);
        $conditions = [
            'contains' => "%$value%",
            'startswith' => "$value%",
            'endswith' => "%$value",
            'eq' => $value,
            'neq' => $value
        ];

        if (array_key_exists($operator, $conditions)) {
            $query->whereRaw("LOWER($field) LIKE ?", [$conditions[$operator]]);
        }
    }
}