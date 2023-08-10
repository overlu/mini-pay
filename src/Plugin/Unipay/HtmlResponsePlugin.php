<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay;

use Closure;
use GuzzleHttp\Psr7\Response;
use Mini\Support\Collection;
use MiniPay\Contract\PluginInterface;
use MiniPay\Logger;
use MiniPay\Rocket;

/**
 * Class HtmlResponsePlugin
 * @package MiniPay\Plugin\Unipay
 */
class HtmlResponsePlugin implements PluginInterface
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[unipay][HtmlResponsePlugin] 插件开始装载', ['rocket' => $rocket]);

        $radar = $rocket->getRadar();

        $response = $this->buildHtml($radar->getUri()->__toString(), $rocket->getPayload());

        $rocket->setDestination($response);

        Logger::info('[unipay][HtmlResponsePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }

    /**
     * @param string $endpoint
     * @param Collection $payload
     * @return Response
     */
    protected function buildHtml(string $endpoint, Collection $payload): Response
    {
        $sHtml = "<form id='pay_form' name='pay_form' action='" . $endpoint . "' method='POST'>";
        foreach ($payload->all() as $key => $val) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $sHtml .= "<input type='submit' value='ok' style='display:none;'></form>";
        $sHtml .= "<script>document.forms['pay_form'].submit();</script>";

        return new Response(200, [], $sHtml);
    }
}
