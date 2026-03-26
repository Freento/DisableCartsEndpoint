# Freento_DisableCartsEndpoint

Magento 2 module that blocks the `PUT /V1/guest-carts/:cartId/order` REST API endpoint to prevent card testing (carding) attacks via guest checkout.

## Problem

Magento's core exposes `PUT /V1/guest-carts/:cartId/order` as an anonymous endpoint (`ref="anonymous"`). It is not used by the default frontend checkout but is fully functional. Bots exploit it to test stolen credit cards — they can place orders with minimal API calls and no authentication.

## How it works

The module intercepts requests at the WebAPI validation layer via a plugin on `RequestValidatorInterface`. When enabled, any `PUT` request matching `/V1/guest-carts/:cartId/order` is rejected with a 404 response, making the endpoint appear non-existent.

The standard checkout endpoint `POST /V1/guest-carts/:cartId/payment-information` is **not affected**.

## Installation

```bash
composer require freento/module-disable-carts-endpoint
bin/magento module:enable Freento_DisableCartsEndpoint
bin/magento setup:upgrade
```

## Configuration

The module is **disabled by default**. Enable it in the admin panel:

**Stores → Configuration → Freento → Disable Carts Endpoint → General Settings → Disable PUT /V1/guest-carts/:cartId/order endpoint → Yes**

## Compatibility

- Magento 2.4.x
- Adobe Commerce / Magento Open Source
