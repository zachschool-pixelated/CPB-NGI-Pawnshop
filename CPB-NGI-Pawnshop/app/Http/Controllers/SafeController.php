<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use App\Models\Customer;
use Illuminate\Http\Request;

class SafeController extends Controller
{
    /**
     * Display a listing of the safes.
     */
    public function index()
    {
        $safes = Safe::withCount('items')->latest()->paginate(15);
        return view('safes.index', compact('safes'));
    }

    /**
     * Show the form for creating a new safe.
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $locations = config('safes.locations');
        return view('safes.create', compact('customers', 'locations'));
    }

    /**
     * Store a newly created safe in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'is_personal' => 'nullable|boolean',
            'description' => 'nullable|string',
            'location' => 'required|string|in:' . implode(',', array_keys(config('safes.locations'))),
            'items_capacity' => 'required|integer|min:1',
            'capacity' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        // Convert is_personal to boolean
        $validated['is_personal'] = $request->has('is_personal') ? true : false;

        Safe::create($validated);

        return redirect()->route('safes.index')->with('success', 'Safe created successfully!');
    }

    /**
     * Display the specified safe.
     */
    public function show(Safe $safe)
    {
        $safe->load('items.category', 'items.transactions.customer');
        return view('safes.show', compact('safe'));
    }

    /**
     * Show the form for editing the specified safe.
     */
    public function edit(Safe $safe)
    {
        $customers = Customer::where('is_active', true)->get();
        $locations = config('safes.locations');
        return view('safes.edit', compact('safe', 'customers', 'locations'));
    }

    /**
     * Update the specified safe in storage.
     */
    public function update(Request $request, Safe $safe)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'is_personal' => 'nullable|boolean',
            'description' => 'nullable|string',
            'location' => 'required|string|in:' . implode(',', array_keys(config('safes.locations'))),
            'items_capacity' => 'required|integer|min:1',
            'capacity' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        // Convert is_personal to boolean
        $validated['is_personal'] = $request->has('is_personal') ? true : false;

        $safe->update($validated);

        return redirect()->route('safes.show', $safe)->with('success', 'Safe updated successfully!');
    }

    /**
     * Remove the specified safe from storage.
     */
    public function destroy(Safe $safe)
    {
        if ($safe->items()->count() > 0) {
            return redirect()->route('safes.index')->with('error', 'Cannot delete safe with items. Please move items first.');
        }

        $safe->delete();
        return redirect()->route('safes.index')->with('success', 'Safe deleted successfully!');
    }
}
