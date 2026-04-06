<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */


declare(strict_types=1);

namespace App\Http\Requests\Payment\Flutterwave;

use Illuminate\Foundation\Http\FormRequest;

class FlutterwaveInitRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'amount'         => 'required|numeric|min:100',
            'order_id'       => 'nullable|string|exists:orders,id',
        ];


    }
}
