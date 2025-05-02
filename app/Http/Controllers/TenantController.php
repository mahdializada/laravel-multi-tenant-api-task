<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TenantController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:tenants,data->>email',
                'domain' => 'required|string|unique:domains,domain|max:255',
                'password' => 'required|string|min:8',
            ]);

            $tenantId = Str::uuid();

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
                'success' => true,
                'message' => 'Tenant registered successfully',
                'tenant_id' => $tenantId
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
