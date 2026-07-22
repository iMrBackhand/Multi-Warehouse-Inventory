<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    public function downloadDatabase()
    {
        // 1. Get DB credentials from .env / config/database.php
        $connection = config('database.default');
        $db = config("database.connections.{$connection}");

        if (empty($db) || $connection !== 'mysql') {
            return response('This backup tool only supports MySQL. Current connection: '.$connection, 500);
        }

        // 2. Locate mysqldump.exe (adjust if your XAMPP is on a different drive/folder)
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';

        if (! file_exists($mysqldumpPath)) {
            return response("mysqldump.exe not found at: {$mysqldumpPath}. Please check your XAMPP install path.", 500);
        }

        // 3. Prepare output file
        $filename = $db['database'].'_backup_'.now()->format('Y-m-d_His').'.sql';
        $folder = storage_path('app/backups');
        $fullPath = $folder.DIRECTORY_SEPARATOR.$filename;

        if (! is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        // 4. Build and run the mysqldump command
        $command = [
            $mysqldumpPath,
            '--host='.$db['host'],
            '--port='.$db['port'],
            '--user='.$db['username'],
        ];

        if (! empty($db['password'])) {
            $command[] = '--password='.$db['password'];
        }

        $command[] = $db['database'];

        $process = new Process($command);
        $process->setTimeout(300);

        try {
            $process->mustRun(function ($type, $buffer) use ($fullPath) {
                file_put_contents($fullPath, $buffer, FILE_APPEND);
            });
        } catch (ProcessFailedException $e) {
            Log::error('Database backup failed: '.$e->getMessage());

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            return response('Backup failed: '.$e->getMessage(), 500);
        }

        // 5. Make sure the file was actually created and isn't empty
        if (! file_exists($fullPath) || filesize($fullPath) === 0) {
            return response('Backup file was not created or is empty. Check DB credentials and mysqldump path.', 500);
        }

        // 6. Send it to the browser as a download
        return response()->download($fullPath, $filename)->deleteFileAfterSend(true);
    }
}
