<?php

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;

class AccountsService extends Service
{
    public function accountExists($emailAddress)
    {
        try {
            $data = $this->transport->get('account/' . urlencode($emailAddress));
        } catch (ApiException $e) {
            if ($e->getCode() == 404) {
                return false;
            }
            throw $e;
        }
        return true;
    }
}
