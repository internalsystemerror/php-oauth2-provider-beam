<?php

namespace Ise\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Beam extends AbstractProvider
{
    
    use BearerAuthorizationTrait;
    
    /**
     * @var string
     */
    protected static $apiEndpoint = 'https://beam.pro/api/v1';

    /**
     * {@inheritDoc}
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://beam.pro/oauth/authorize';
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return static::$apiEndpoint . '/oauth/token';
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return static::$apiEndpoint . '/users/current';
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultScopes()
    {
        return [
            'chat:bypass_links',
            'chat:bypass_slowchat',
            'chat:change_ban',
            'chat:change_role',
            'chat:chat',
            'chat:clear_messages',
            'chat:connect',
            'chat:edit_options',
            'chat:giveaway_start',
            'chat:poll_start',
            'chat:poll_vote',
            'chat:purge',
            'chat:remove_message',
            'chat:timeout',
            'chat:view_deleted',
            'chat:whisper',
        ];
    }


    /**
     * {@inheritDoc}
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * {@inheritDoc}
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error']) && $data['error']) {
            throw new IdentityProviderException($data['error'], 0, $data);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new GenericResourceOwner($response, 'id');
    }
}
