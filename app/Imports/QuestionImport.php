<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionImport implements ToModel, WithHeadingRow, WithValidation
{
    public function headingRow(): int
    {
        return 1; // Use the first row as headers
    }

    public function model(array $row)
    {
        try {
            Log::info('Headers: ' . implode(', ', array_keys($row)));
            Log::info('Row Data: ' . json_encode($row));

            // Check if the question already exists
            $isQuestionExists = Question::where('description', $row['description'])->exists();
            if ($isQuestionExists) {
                noty()->livewire()->addWarning("Question already exists: " . $row['description']);
                return null;
            }

            // Retrieve the topic by name
            $topic = Topic::whereRaw('LOWER(name) = ?', [strtolower($row['topic'])])->first();
            if (!$topic) {
                noty()->livewire()->addError("Topic not found: " . $row['topic']);
                return null;
            }
            $topic_id = $topic->id; // Get the topic ID

            // Create a new question
            $question = new Question([
                'topic_id' => $topic_id,
                'description' => $row['description'] ?? "Data",
                'explanation' => $row['explanation'] ?? null,
                'enabled' => $row['isEnabled'] ?? 1,
                'type' => $row['type'] ?? 1,
                'difficulty' => $row['difficulty'] ?? 1,
                'timer' => $row['timer'] ?? null,
                'fullscreen' => $row['isFullScreen'] ?? 1,
                'inline_answers' => $row['isInlineAnswer'] ?? 1,
                'auto_next' => $row['isAutoNext'] ?? 1,
                'created_by' => Auth::user()->name,
            ]);

            // Determine position
            if (isset($row['position'])) {
                $question->position = $row['position'];
            } else {
                $maxPosition = Question::where('topic_id', $question->topic_id)->max('position');
                $question->position = $maxPosition ? $maxPosition + 1 : 1;
            }

            // Increment positions of existing questions
            DB::transaction(function () use ($question) {
                $topic_id = $question->topic_id;
                $position = $question->position;

                Question::where('topic_id', $topic_id)
                    ->where('position', '>=', $position)
                    ->increment('position');

                // Save the new question
                $question->save();
            });
            noty()->livewire()->addSuccess('Question imported successfully!');
            return $question;
        } catch (\Throwable $th) {
            noty()->livewire()->addError($th->getMessage());
        }
    }

    public function rules(): array
    {
        return [
            'description' => 'required|string',
            'topic' => 'required|string',
            'position' => 'nullable|integer|min:1',
            'type' => 'nullable|integer',
            'difficulty' => 'nullable|integer',
            'timer' => 'nullable|integer',
            'isEnabled' => 'nullable|boolean',
            'isFullScreen' => 'nullable|boolean',
            'isInlineAnswer' => 'nullable|boolean',
            'isAutoNext' => 'nullable|boolean',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'description.required' => 'The description field is required.',
            'topic.required' => 'The topic field is required.',
            'topic.string' => 'The topic must be a string.',
            'position.integer' => 'The position must be an integer.',
            'position.min' => 'The position must be at least 1.',
        ];
    }
}
// class QuestionImport implements ToModel, WithHeadingRow, WithValidation
// {
//     public function headingRow(): int
//     {
//         return 2; // Use the second row as headers
//     }
//     public function model(array $row)

//     {
//         //Check if the question already exists

//         try {
//             Log::info('Headers: ' . implode(', ', array_keys($row)));
//             Log::info('Row Data: ' . json_encode($row));

//             // Check if the question already exists
//             $isQuestionExists = Question::where('description', $row['description'])->exists();
//             if ($isQuestionExists) {
//                 noty()->livewire()->addWarning("Question already exists: " . $row['description']);
//                 return;
//             }

//             // Retrieve the topic by name
//             $topic = Topic::whereRaw('LOWER(name) = ?', [strtolower($row['topic'])])->first();
//             if (!$topic) {
//                 noty()->livewire()->addError("Topic not found: " . $row['topic']);
//                 return;
//             }
//             $topic_id = $topic->id; // Get the topic ID

//             // Create a new question
//             $question = new Question([
//                 'topic_id' => $topic_id,
//                 'description' => $row['description'] ?? "Data",
//                 'explanation' => $row['explanation'] ?? null,
//                 'enabled' => $row['isEnabled'] ?? 1,
//                 'type' => $row['type'] ?? 1,
//                 'difficulty' => $row['difficulty'] ?? 1,
//                 'timer' => $row['timer'] ?? null,
//                 'fullscreen' => $row['isFullScreen'] ?? 1,
//                 'inline_answers' => $row['isInlineAnswer'] ?? 1,
//                 'auto_next' => $row['isAutoNext'] ?? 1,
//                 'created_by' => Auth::user()->name,
//             ]);

//             // Determine position
//             if (isset($row['position'])) {
//                 $question->position = $row['position'];
//             } else {
//                 $maxPosition = Question::where('topic_id', $question->topic_id)->max('position');
//                 $question->position = $maxPosition ? $maxPosition + 1 : 1;
//             }
//             $question->description = $row['description'] ?? "Data";
//             //I'm writing this code from my tv interface
//             // Increment positions of existing questions
//             DB::transaction(function () use ($question) {
//                 $topic_id = $question->topic_id;
//                 $position = $question->position;

//                 // Fixing the typo in the SQL query
//                 //$sql_update_position = "UPDATE questions SET position = position + 1 WHERE topic_id = :topic_id AND position >= :position";
//                 //DB::update($sql_update_position, ['topic_id' => $topic_id, 'position' => $position]);

//                 // Save the new question
//                 $question->save();
//             });

//             noty()->livewire()->addSuccess('Question imported successfully!');
//         } catch (\Throwable $th) {
//             noty()->livewire()->addError($th->getMessage());
//         }

//         return $question;
//     }
//     public function rules(): array
//     {

//         return [
//             'description' => 'nullable',
//             'topic_id' => 'nullable|integer|exists:topics,id',
//             'position' => 'nullable|integer|min:1'
//         ];
//     }
//     public function customValidationMessages()
//     {
//         return [
//             'description.nullable' => 'The description field is required.',
//             'topic_id.exists'      => 'The selected topic does not exist.',
//         ];
//     }
// }
