<?php

/**
 * Service that wraps up JustGiving API endpoints starting with /account.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use JustGivingApi\Models\Account;

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

    /**
     * Create a user account.
     *
     * @param  Account $account The account object to create.
     * @return Object containing the response data:
     * {
     *   "email": "string",
     *   "country": "string",
     *   "Error.id": "string",
     *   "Error.desc": "string",
     *   "errorMessage": "string"
     * }
     */
    public function accountCreate(Account $account)
    {
        try {
            $data = $this->transport->put('account', $account->toArray());
        } catch (ApiException $e) {
            if ($e->getCode() == 404) {
                return false;
            }
            throw $e;
        }
        return $data;
    }

    /**
     * @todo RetrieveAccount
     * - AccountRegistration
     * @todo Validate
     * @todo GetFundraisingPagesForUser
     * @todo GetDonationsForUser
     * - AccountAvailabilityCheck
     * @todo ChangePassword
     * @todo RequestPasswordReminder
     */
}
