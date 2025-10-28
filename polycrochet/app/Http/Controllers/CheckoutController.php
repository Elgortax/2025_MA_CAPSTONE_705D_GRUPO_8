<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Support\CartManager;
use App\Support\SettingsStore;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartManager $cart,
        protected SettingsStore $settings
    ) {
    }

    /**
     * Display the checkout page with the required location metadata.
     */
    public function index(Request $request)
    {
        if ($this->cart->items()->isEmpty()) {
            return redirect()
                ->route('cart')
                ->with('status', 'Tu carrito está vacío. Agrega productos antes de continuar al checkout.');
        }

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
            'cartSummary' => $this->cart->summary(),
            'shippingMethod' => $this->settings->get('shipping.method', 'Chilexpress'),
        ]);
    }
}
