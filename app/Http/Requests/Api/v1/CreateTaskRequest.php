<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1;

class CreateTaskRequest extends AbstractTaskRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date' => 'required|date_format:Y-m-d|after:' . date('Y-m-D'),
            'location' => 'required|string|max:255',
            'details' => 'nullable|string',
        ];
    }
}
