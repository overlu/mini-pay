<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Data;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class BillDownloadUrlQueryPlugin
 * @package MiniPay\Plugin\Alipay\Data
 * @see https://opendocs.alipay.com/open/02fkbl
 */
class BillDownloadUrlQueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.data.dataservice.bill.downloadurl.query';
    }
}
