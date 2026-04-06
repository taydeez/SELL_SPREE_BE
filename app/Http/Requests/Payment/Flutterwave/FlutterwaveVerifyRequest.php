<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */


/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */


declare(strict_types=1);

namespace App\Http\Requests\Payment\Flutterwave;

use Illuminate\Foundation\Http\FormRequest;

class FlutterwaveVerifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'transaction_id' => 'required|integer',
            'tx_ref'         => 'required|string',
        ];
    }
}
