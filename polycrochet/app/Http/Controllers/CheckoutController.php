<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page with the required location metadata.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $regions = Region::with([
            'communes' => fn ($query) => $query->orderBy('name'),
        ])
            ->ordered()
            ->get();

        $addresses = collect();
        $address = null;

        if ($user) {
            $addresses = $user->addresses()
                ->with(['region:id,name', 'commune:id,name,region_id'])
                ->orderByDesc('is_default')
                ->orderBy('created_at')
                ->get();

            $address = $addresses->firstWhere('is_default', true) ?? $addresses->first();
        }

        return view('pages.checkout', [
            'regions' => $regions,
            'address' => $address,
            'addresses' => $addresses,
        ]);
    }
}
