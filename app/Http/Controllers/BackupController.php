<?php

// app/Http/Controllers/API/BackupController.php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function backupDatabase(Request $request)
    {
        try {
            Artisan::call('backup:database');
            $message = 'Database backup completed successfully.';
            $status = 'success';
        } catch (\Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $status = 'error';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }
}
