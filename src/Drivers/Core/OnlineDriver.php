<?php

/**
 * This file is part of the StringTranslator Project.
 *
 * @package     StringTranslator
 * @author      Anatolii Belianin <belianianatoli@gmail.com>
 * @license     See LICENSE.md for license information
 * @link        https://github.com/abeliani/string-translator
 */

declare(strict_types=1);

namespace Abeliani\StringTranslator\Drivers\Core;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

abstract class OnlineDriver extends Driver
{
    protected const METH_GET = 'GET';
    protected const METH_POST = 'POST';
    protected const SCHEME = 'https';

    /**
     * @inheritDoc
     *
     * @param ClientInterface $client - psr7 http client
     * @param RequestInterface $request - psr7 http request
     * @param DriverInterface|null $successor - next driver to call if current fail
     */
    public function __construct(
        protected readonly ClientInterface $client,
        protected readonly RequestInterface $request,
        ?DriverInterface $successor = null,
    ) {
        parent::__construct($successor);
    }

    final public function handle(string $text, string $from, string $to): string
    {
        try {
            return parent::handle($text, $from, $to);
        } catch (\Throwable $e) {
            if ($e->getCode() < 200 || $e->getCode() > 499) {
                throw $e;
            }
        }

        return $this->successor?->processing($text, $from, $to) ?? $text;
    }
}
