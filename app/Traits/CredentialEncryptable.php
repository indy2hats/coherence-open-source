<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait CredentialEncryptable
{
    /**
     * Decrypt data from DB table.
     * Decrypt only in that case if the value is not NULL.
     * Otherwise attribute will be NULL.
     *
     * @param  mixed  $key
     * @return mixed $value
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable) && null !== $value && $this->attributes['form_value_type'] != 'file') {
            $value = Crypt::decrypt($value);
        }

        return $value;
    }

    /**
     * Encrypt incoming data to DB table.
     * Encrypt only in that case if the value is not NULL.
     * Otherwise attribute will be NULL.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return string
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable) && null !== $value && $this->attributes['form_value_type'] != 'file') {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }
}
