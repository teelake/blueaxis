<?php

declare(strict_types=1);

namespace App\Core;

use App\Services\Validator;

trait ValidatesForms
{
    /** @param array<string, mixed> $old */
    protected function validateOrRedirect(
        Validator $validator,
        string $redirectPath,
        array $old = [],
        string $message = 'Please correct the highlighted fields.'
    ): void {
        if ($validator->fails()) {
            Session::setErrors($validator->errors());
            if ($old !== []) {
                Session::setOld($old);
            }
            Session::flash('error', $validator->firstError() ?? $message);
            redirect($redirectPath);
        }
        Session::clearErrors();
    }
}
