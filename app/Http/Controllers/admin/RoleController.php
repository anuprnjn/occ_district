<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; // Import the base Controller class
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function RoleList()
    {
        return view('admin.role.role_list'); // This looks for resources/views/admin/role-list.blade.php
    }
}