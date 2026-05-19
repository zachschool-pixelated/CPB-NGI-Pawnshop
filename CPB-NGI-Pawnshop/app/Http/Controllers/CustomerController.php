<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Region;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index()
    {
        $customers = Customer::with('barangay', 'city', 'province', 'region')
            ->latest()
            ->paginate(15);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        $regions = Region::orderBy('name')->get();
        return view('customers.create', compact('regions'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        // Handle ID image upload
        if ($request->hasFile('id_image')) {
            $data['id_image_path'] = $request->file('id_image')->store('customer-ids', 'public');
        }

        // Remove id_image from data (it's not a column)
        unset($data['id_image']);

        // Format phone number to strict E.164 standard (e.g. +639171234567)
        $data['phone_number'] = phone($data['phone_number'], 'PH')->formatE164();

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer registered successfully!');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load('transactions.items', 'region', 'province', 'city', 'barangay');
        return view('customers.show', compact('customer'));
    }

}
