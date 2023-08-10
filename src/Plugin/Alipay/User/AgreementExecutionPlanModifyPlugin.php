<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\User;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class AgreementExecutionPlanModifyPlugin
 * @package MiniPay\Plugin\Alipay\User
 * @see https://opendocs.alipay.com/open/02fkaq?ref=api
 */
class AgreementExecutionPlanModifyPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.user.agreement.executionplan.modify';
    }
}
