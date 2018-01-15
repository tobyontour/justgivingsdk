<?php

/**
 * Service that wraps up JustGiving API endpoints starting with /account.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;

/**
 * Service that wraps up JustGiving API endpoints starting with /account.
 */
class AccountsService extends Service
{
    /**
     * Finds out if an account already exists for a given email address.
     *
     * @param  string $emailAddress The email address to search for an account for.
     * @return boolean True if an account with that email address exists.
     */
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
