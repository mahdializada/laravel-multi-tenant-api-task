<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:tenants,data->>email', // Note: Changed to ->> for PostgreSQL
            'domain' => 'required|string|unique:domains,domain',
            'password' => 'required|string|min:8',
        ]);

        $tenantId = Str::uuid(); // Generate UUID first

        $tenant = Tenant::create([
            'id' => $tenantId,
            'data' => ['email' => $validated['email']],
        ]);

        $tenant->domains()->create(['domain' => $validated['domain']]);

        $tenant->run(function () use ($validated) {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        });

        return response()->json([
            'message' => 'Tenant registered successfully',
            'tenant_id' => $tenantId
        ], 201);
    }
}
