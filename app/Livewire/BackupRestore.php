<?php

namespace App\Livewire;

use App\Models\Backup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class BackupRestore extends Component
{
    use WithFileUploads;
    #[Rule(['required', 'file'])]
    public $backupFile;
    public function backupFiles($sourcePath, $destinationPath)
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupDir = $destinationPath . "/backup_{$timestamp}";

        // Copy the directory or file
        File::copyDirectory($sourcePath, $backupDir);

        // Determine the file type and size
        $fileType = 'directory'; // Default to directory for copied directories
        $fileSize = File::size($backupDir); // Get the size of the directory

        // If you want to handle individual files, you can iterate over them
        $files = File::allFiles($backupDir);
        foreach ($files as $file) {
            $type = pathinfo($file, PATHINFO_EXTENSION); // Get file extension
            $size = $file->getSize(); // Get individual file size
            // Save each file type and size as separate records if desired
            Backup::create([
                'file_path' => $file->getPathname(),
                'file_type' => $type,
                'file_size' => $size,
                'created_by' => Auth::username(),
            ]);
        }

        // Save the directory as a backup (if needed)
        // Backup::create([
        //     'file_path' => $backupDir,
        //     'file_type' => $fileType,
        //     'file_size' => $fileSize,
        //     'created_by'=>Auth::username(),
        // ]);
        noty()->livewire()->addSuccess("Backup created successfully!");

        return $backupDir;
    }

    public function backupDatabase($databaseName, $destinationPath)
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupFile = $destinationPath . "/backup_db_{$timestamp}.sql";

        // Run mysqldump to create the database backup
        $command = "mysqldump -u " . env('DB_USERNAME') . " -p" . env('DB_PASSWORD') . " {$databaseName} > {$backupFile}";
        system($command);

        // Save the database backup details
        Backup::create([
            'file_path' => $backupFile,
            'file_type' => 'database',
            'file_size' => File::size($backupFile),
            'created_by' => Auth::user()->name,
        ]);
    }

    public function createBackup()
    {
        $filesSourcePath = base_path();
        $filesDestinationPath = storage_path('app/AwashExam-backup');
        $databaseName = env('DB_DATABASE');

        if (!File::exists($filesDestinationPath)) {
            File::makeDirectory($filesDestinationPath, 0755, true);
        }
        //$this->backupFiles($filesSourcePath, $filesDestinationPath);
        $this->backupDatabase($databaseName, $filesDestinationPath);
    }

    #[Computed()]
    public function backups()
    {
        return Backup::all();
    }
    public function render()
    {
        return view('livewire.backup-restore');
    }
}
