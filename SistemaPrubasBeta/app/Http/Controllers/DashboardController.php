<?php

namespace App\Http\Controllers;

use App\Models\JugadorVisitante;
use App\Models\User;

class DashboardController extends Controller
{
    public function summary()
    {
        $registeredUsers = User::count();
        $registeredVisitors = JugadorVisitante::count();

        return response()->json([
            'players' => $registeredVisitors,
            'students' => $registeredUsers,
            'challenges' => 16,
            'teachers' => $registeredUsers,
        ]);
    }
}
