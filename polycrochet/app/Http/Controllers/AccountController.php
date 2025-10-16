<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display the customer account page.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return view('pages.account', [
                'regions' => collect(),
                'address' => null,
                'addresses' => collect(),
            ]);
        }

        $addresses = $user->addresses()
            ->with(['region:id,name', 'commune:id,name,region_id'])
            ->orderByDesc('is_default')
            ->orderBy('created_at')
            ->get();

        $regions = Region::with([
            'communes' => fn ($query) => $query->orderBy('name'),
        ])
            ->ordered()
            ->get();

        $address = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        return view('pages.account', [
            'regions' => $regions,
            'address' => $address,
            'addresses' => $addresses,
        ]);
    }
}
