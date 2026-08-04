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
            'cliente', 'correo', 'telefono', 'tel_casa', 'recepcion_hora', 'inicio_hora', 
            'tipo_evento', 'festejado', 'cliente_domicilio', 'cliente_ine', 'manteleria_color', 'cp', 
            'invitacion_estado', 'invitacion_detalle'
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
            'tel_casa' => 'nullable|string|max:40',
            'cp' => 'nullable|string|max:10',
            'invitacion_estado' => 'nullable|string|max:50',
            'invitacion_detalle' => 'nullable|string|max:255',
            'evento_fecha' => 'required|date',
            'recepcion_hora' => 'nullable|string|max:20',
            'inicio_hora' => 'nullable|string|max:20',
            'tipo_evento' => 'required|string|max:80',
            'festejado' => 'required|string|max:120',
            'estado' => 'required|in:cotizacion,confirmado,finalizado,cancelado',
            'salon_id' => 'required|integer|exists:salones,id',
            'horas_evento' => 'required|numeric|min:0',
            'horas_adicionales' => 'nullable|numeric|min:0',
            'num_adultos' => 'required|integer|min:0',
            'num_ninos' => 'required|integer|min:0',
            'vendedoras_ids' => 'nullable|array',
            'vendedoras_ids.*' => 'exists:vendedoras,id',
            'cliente_domicilio' => 'nullable|string|max:500',
            'cliente_ine' => 'nullable|string|max:50',
            'manteleria_color' => 'nullable|string|max:50',
            'platillo_ids' => 'sometimes|array',
            'platillo_ids.*' => 'integer|exists:platillos,id',
            'servicio_gastronomico' => 'required|integer|exists:servicios_gastronomicos,id',
            'servicio_externo' => 'nullable|boolean',
            'extras' => 'sometimes|array',
            'extras.*' => 'nullable|boolean',
            'hora_por_definir' => 'nullable|boolean',
            'tiene_misa' => 'nullable|boolean',
            'c_renta_salon' => 'nullable|numeric|min:0',
            'c_otras_bebidas' => 'nullable|numeric|min:0',
            'c_pinata' => 'nullable|numeric|min:0',
            'c_mesa_dulces' => 'nullable|numeric|min:0',
            'c_show' => 'nullable|numeric|min:0',
            'c_usb_video' => 'nullable|numeric|min:0',
            'c_album_digital' => 'nullable|numeric|min:0',
            'c_album_paquete' => 'nullable|numeric|min:0',
            'c_derecho_pista' => 'nullable|numeric|min:0',
            'c_hora_extra' => 'nullable|numeric|min:0',
            'quien_vendio_hora_extra' => 'nullable|string|max:50',
            'c_camara_360' => 'nullable|numeric|min:0',
            'c_amenizacion' => 'nullable|numeric|min:0',
            'c_personas_adicionales' => 'nullable|numeric|min:0',
            'c_cafe' => 'nullable|numeric|min:0',
            'c_mickey_movil' => 'nullable|numeric|min:0',
            'c_otros' => 'nullable|numeric|min:0',
            'pagos' => 'nullable|array',
            'pagos.*.monto' => 'required_with:pagos|numeric|min:0',
            'pagos.*.recibo' => 'nullable|string|max:100',
            'pagos.*.fecha' => 'required_with:pagos|date',
            'monto_total' => 'nullable|string',
        ];
    }
}
