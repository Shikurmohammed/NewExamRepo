<?php

namespace App\Imports;

use App\Models\Answer;
use App\Models\Topic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TopicsImport  implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 2; // Use the second row as headers
    }
    public function model(array $row)
    {
        // Skip the row if the 'name' field is empty
        if (empty($row['name'])) {
            Log::warning('Skipped empty row: ' . json_encode($row));
            return null; // Skip this row
        }

        try {
            // Using updateOrCreate for simplicity
            $topic = Topic::updateOrCreate(
                ['name' => $row['name']], // Finding criteria
                [
                    'description' => $row['description'],
                    'enabled' => $row['enabled'] ?? 1,
                    'module_id' => $row['module_id'],
                    'user_id' => $row['user_id'] ?? Auth::id(),
                ]
            );

            // Notify user based on whether the topic was created or updated
            if ($topic->wasRecentlyCreated) {
                noty()->livewire()->addSuccess("Topic added successfully: " . $row['name']);
            } else {
                noty()->livewire()->addWarning("Topic already exists: " . $row['name']);
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
