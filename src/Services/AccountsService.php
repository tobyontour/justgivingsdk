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
     * Retrieve details of currently logged in account.
     */
    public function getAccount()
    {
        $data = $this->transport->get('account', true);

        return new Account($data);
    }

    /**
     * Validates the credentials for an account.
     *
     * @param  string $email User's email address
     * @param  string $password The user's password
     * @param  reference $consumerId The consumer ID. Not sure what it is so it's optional.
     * @return [type]
     */
    public function validateAccount($email, $password, &$consumerId = null)
    {
        $data = $this->transport->post(
            'account/validate',
            [
                'email' => $email,
                'password' => $password
            ]
        );

        if ($data->isValid !== true) {
            return false;
        } else {
            $consumerId = $data->consumerId;
            return true;
        }
    }

    /**
     * Gets all the fundraising pages for a user.
     *
     * @param  string $email The user's email address.
     * @return array An array of pages.
     */
    public function getPagesForUser($email)
    {
        $data = $this->transport->get('account/' . urlencode($email) . '/pages');

        return $data;
    }

    /**
     * - RetrieveAccount
     * - AccountRegistration
     * - Validate
     * - GetFundraisingPagesForUser
     * @todo GetDonationsForUser
     * - AccountAvailabilityCheck
     * @todo ChangePassword
     * @todo RequestPasswordReminder
     */
}
