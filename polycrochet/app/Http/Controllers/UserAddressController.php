<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserAddressController extends Controller
{
    /**
     * Persist a new default address for the authenticated user.
     */
    public function store(UserAddressRequest $request)
    {
        $user = $request->user();

        $makeDefault = $request->wantsDefault() || $user->addresses()->doesntExist();

        $address = DB::transaction(function () use ($user, $request, $makeDefault) {
            $address = $user->addresses()->create(array_merge(
                $request->payload(),
                ['is_default' => $makeDefault]
            ));

            if ($makeDefault) {
                $user->addresses()
                    ->whereKeyNot($address->getKey())
                    ->update(['is_default' => false]);
            }

            return $address->fresh(['region:id,name', 'commune:id,name,region_id']);
        });

        return $this->respond($request, 'address-saved', 'Dirección guardada correctamente.', Response::HTTP_CREATED, $address);
    }

    /**
     * Update an existing user address.
     */
    public function update(UserAddressRequest $request, UserAddress $userAddress)
    {
        abort_if($request->user()->id !== $userAddress->user_id, 403);

        $makeDefault = $request->has('make_default')
            ? $request->wantsDefault()
            : $userAddress->is_default;

        $address = DB::transaction(function () use ($request, $userAddress, $makeDefault) {
            $userAddress->update(array_merge(
                $request->payload(),
                ['is_default' => $makeDefault]
            ));

            if ($makeDefault) {
                $userAddress->user
                    ->addresses()
                    ->whereKeyNot($userAddress->getKey())
                    ->update(['is_default' => false]);
            } elseif (! $userAddress->user->addresses()->where('is_default', true)->exists()) {
                $userAddress->update(['is_default' => true]);
            }

            return $userAddress->fresh(['region:id,name', 'commune:id,name,region_id']);
        });

        return $this->respond($request, 'address-saved', 'Dirección actualizada correctamente.', Response::HTTP_OK, $address);
    }

    /**
     * Set the provided address as default for the user.
     */
    public function setDefault(Request $request, UserAddress $userAddress)
    {
        abort_if($request->user()->id !== $userAddress->user_id, 403);

        $user = $userAddress->user;

        DB::transaction(function () use ($user, $userAddress) {
            $user->addresses()->update(['is_default' => false]);
            $userAddress->update(['is_default' => true]);
        });

        return $this->respond($request, 'address-default', 'Dirección predeterminada actualizada.', Response::HTTP_OK, $userAddress->fresh(['region:id,name', 'commune:id,name,region_id']));
    }

    /**
     * Delete the given address.
     */
    public function destroy(Request $request, UserAddress $userAddress)
    {
        abort_if($request->user()->id !== $userAddress->user_id, 403);

        $user = $userAddress->user;
        $wasDefault = $userAddress->is_default;

        DB::transaction(function () use ($userAddress, $user, $wasDefault) {
            $userAddress->delete();

            if ($wasDefault) {
                $next = $user->addresses()->oldest()->first();
                if ($next) {
                    $next->update(['is_default' => true]);
                }
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Dirección eliminada correctamente.',
            ], Response::HTTP_OK);
        }

        return $this->redirectWithStatus($request, 'address-deleted', 'Dirección eliminada correctamente.');
    }

    /**
     * Return a consistent response for both HTML and JSON consumers.
     */
    protected function respond(Request $request, string $statusKey, string $message, int $status, ?UserAddress $address = null)
    {
        if ($request->expectsJson()) {
            return new JsonResponse([
                'message' => $message,
                'address' => $address ? $this->formatAddress($address) : null,
            ], $status);
        }

        return $this->redirectWithStatus($request, $statusKey, $message);
    }

    /**
     * Build redirect response with flash status.
     */
    protected function redirectWithStatus(Request $request, string $statusKey, string $message)
    {
        $fallback = $request->headers->get('referer', route('account'));

        $destinationInput = $request->input('redirect_to', $fallback);
        $destination = Str::startsWith($destinationInput, ['http://', 'https://'])
            ? $destinationInput
            : url($destinationInput);

        return redirect()
            ->to($destination)
            ->with('status', $statusKey)
            ->with('status_message', $message)
            ->header('Cache-Control', 'no-store');
    }

    /**
     * Normalize address payload.
     */
    protected function formatAddress(UserAddress $address): array
    {
        return [
            'id' => $address->id,
            'street' => $address->street,
            'number' => $address->number,
            'apartment' => $address->apartment,
            'reference' => $address->reference,
            'postal_code' => $address->postal_code,
            'is_default' => $address->is_default,
            'region' => [
                'id' => $address->region->id,
                'name' => $address->region->name,
            ],
            'commune' => [
                'id' => $address->commune->id,
                'name' => $address->commune->name,
            ],
        ];
    }
}
