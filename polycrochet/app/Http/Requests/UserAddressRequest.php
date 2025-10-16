<?php

namespace App\Http\Requests;

use App\Models\Commune;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UserAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'street' => ['required', 'string', 'max:120'],
            'number' => ['required', 'string', 'max:20'],
            'apartment' => ['nullable', 'string', 'max:80'],
            'reference' => ['nullable', 'string', 'max:150'],
            'postal_code' => ['nullable', 'string', 'max:15'],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'commune_id' => ['required', 'integer', 'exists:communes,id'],
            'redirect_to' => ['nullable', 'string'],
            'make_default' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->filled(['region_id', 'commune_id'])) {
                return;
            }

            $belongsToRegion = Commune::whereKey($this->integer('commune_id'))
                ->where('region_id', $this->integer('region_id'))
                ->exists();

            if (! $belongsToRegion) {
                $validator->errors()->add(
                    'commune_id',
                    'La comuna seleccionada no pertenece a la región elegida.'
                );
            }
        });
    }

    /**
     * Sanitized payload for persistence.
     *
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->safe()->only([
            'street',
            'number',
            'apartment',
            'reference',
            'postal_code',
            'region_id',
            'commune_id',
        ]);
    }

    /**
     * Determine if the request wants the address as default.
     */
    public function wantsDefault(): bool
    {
        if ($this->has('make_default')) {
            return $this->boolean('make_default');
        }

        return false;
    }
}
