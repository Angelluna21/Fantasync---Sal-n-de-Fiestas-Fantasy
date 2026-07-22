<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContratoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Add authorization logic if needed, currently open to anyone who can access the route
        return true;
    }

    /**
     * Prepare the data for validation.
     * Sanitizes inputs to prevent XSS.
     */
    protected function prepareForValidation(): void
    {
        $stringFields = [
            'cliente', 'correo', 'telefono', 'recepcion_hora', 'inicio_hora', 
            'tipo_evento', 'festejado', 'cliente_domicilio', 'cliente_ine', 'manteleria_color'
        ];

        foreach ($stringFields as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => strip_tags($this->input($field))
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente' => 'required|string|max:150',
            'correo' => 'required|email|max:150',
            'telefono' => 'required|string|max:40',
            'evento_fecha' => 'required|date',
            'recepcion_hora' => 'required|string|max:20',
            'inicio_hora' => 'required|string|max:20',
            'tipo_evento' => 'required|string|max:80',
            'festejado' => 'required|string|max:120',
            'estado' => 'required|in:cotizacion,confirmado,finalizado,cancelado',
            'salon_id' => 'required|integer|exists:salones,id',
            'horas_evento' => 'required|integer|min:1',
            'num_adultos' => 'required|integer|min:0',
            'num_ninos' => 'required|integer|min:0',
            'cliente_domicilio' => 'nullable|string|max:255',
            'cliente_ine' => 'nullable|string|max:50',
            'manteleria_color' => 'nullable|string|max:50',
            'platillo_ids' => 'sometimes|array',
            'platillo_ids.*' => 'integer|exists:platillos,id',
            'servicio_gastronomico' => 'required|integer|exists:servicios_gastronomicos,id',
            'extras' => 'sometimes|array',
            'extras.*' => 'nullable|boolean',
        ];
    }
}
