<?php

namespace App\Imports;

use App\Models\Module;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModulesImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1; // Use the second row as headers
    }
    public function model(array $row)
    {

        try {
            if (empty($row['name'])) {
                return null;  // Skip the row if the 'name' field is empty
            }
            // Using updateOrCreate for simplicity
            $module = Module::updateOrCreate(
                ['name' => $row['name']], // Finding criteria
                [
                    'enabled' => $row['enabled'] ?? 1,
                    'user_id' => $row['user_id'] ?? Auth::id(),
                ]
            );

            // Notify user whether the module was created or updated
            if ($module->wasRecentlyCreated) {
                noty()->livewire()->addSuccess("Module added successfully: " . $row['name']);
            } else {
                noty()->livewire()->addWarning("Module already exists: " . $row['name']);
            }
        } catch (\Throwable $th) {
            noty()->livewire()->addError($th->getMessage());
            Log::error('Error processing row: ' . json_encode($row), ['error' => $th->getMessage()]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'enabled' => 'required|boolean',
            'user_id' => 'required|integer',
        ];
    }
}
