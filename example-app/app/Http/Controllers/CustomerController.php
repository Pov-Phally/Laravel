<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        // Fetch all customers from the database
        $customers = Customer::with('contacts')->get();

        // Return a view with the customers data
        return response()->json($customers);
    }
}
