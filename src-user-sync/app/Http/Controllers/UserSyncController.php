<?php

namespace App\Http\Controllers;

use App\Models\UserSynced;
use Illuminate\Http\Request;

class UserSyncController extends Controller
{
    public function sync(Request $request)
    {
        try {
            \Log::info("creating sync");
            $data = $request->validate([
                'email' => 'required|email',
                'firstName' => 'required|string',
                'lastName' => 'required|string',
            ]);

            UserSynced::create($data);

            return response()->json(['message' => 'User synced successfully']);
        } catch (\Throwable $e) {
            \Log::error('Sync failed: '.$e->getMessage());
            return response()->json(['error' => 'Sync failed'], 500);
        }
    }

}
