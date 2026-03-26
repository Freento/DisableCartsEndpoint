<?php

declare(strict_types=1);

namespace Freento\DisableCartsEndpoint\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Webapi\Rest\RequestValidatorInterface;

class DisableGuestPlaceOrder
{
    private const CONFIG_PATH_ENABLED = 'freento_disable_carts_endpoint/general/enabled';
    private const ROUTE_PATTERN = '#/V1/guest-carts/[^/]+/order#';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Block PUT /V1/guest-carts/:cartId/order endpoint when config is enabled
     *
     * @param RequestValidatorInterface $subject
     * @param Request $request
     * @return void
     * @throws WebapiException
     */
    public function beforeValidate(RequestValidatorInterface $subject, Request $request): void
    {
        if (!$this->scopeConfig->isSetFlag(self::CONFIG_PATH_ENABLED)) {
            return;
        }

        if ($request->getMethod() === 'PUT' && preg_match(self::ROUTE_PATTERN, $request->getPathInfo())) {
            throw new WebapiException(
                __('Request does not match any route.'),
                0,
                WebapiException::HTTP_NOT_FOUND
            );
        }
    }
}
