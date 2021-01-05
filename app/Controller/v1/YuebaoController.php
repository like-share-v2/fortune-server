<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Exception\LogicException;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\YuebaoCoinLogDAO;
use App\Service\DAO\MFIncomeDAO;
use App\Service\YuebaoService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Utils\Context;

/**
 * YuebaoController
 *
 * @Controller()
 * @author  baidu.com
 * @package App\Controller
 */
class YuebaoController extends AbstractController
{
    /**
     * 转入到余额宝
     *
     * @PostMapping(path="transfer_in")
     */
    public function transferIn()
    {
        $user_id = JwtInstance::instance()->build()->getId();
        $amount  = (int)$this->request->input('amount', 0);
        if ($amount <= 0) {
            $this->error('logic.PLEASE_INPUT_AMOUNT');
        }
        // 判断最低转入金额
        $minTransferIn = getConfig('yuebao_transfer_in_min', 0);
        if ($minTransferIn > 0 && $amount < $minTransferIn) {
            $this->error('logic.MIN_TRANSFER_IN', 400, [
                'amount' => $minTransferIn
            ]);
        }
        // 判断最大转入金额
        $maxTransferIn = getConfig('yuebao_transfer_in_max', 0);
        if ($maxTransferIn > 0 && $amount > $maxTransferIn) {
            $this->error('logic.MAX_TRANSFER_IN', 400, [
                'amount' => $maxTransferIn
            ]);
        }

        try {
            $this->container->get(YuebaoService::class)->transferToYuebao($user_id, $amount);
        }
        catch (LogicException $e) {
            $this->error($e->getMessage());
        }

        $this->success();
    }

    /**
     * 转出到余额
     *
     * @PostMapping(path="transfer_out")
     */
    public function transferOut()
    {
        $user_id = JwtInstance::instance()->build()->getId();
        $amount  = (int)$this->request->input('amount', 0);
        if ($amount <= 0) {
            $this->error('logic.PLEASE_INPUT_AMOUNT');
        }
        // 判断最低转出金额
        $minTransferOut = getConfig('yuebao_transfer_out_min', 0);
        if ($minTransferOut > 0 && $amount < $minTransferOut) {
            $this->error('logic.MIN_TRANSFER_OUT', 400, [
                'amount' => $minTransferOut
            ]);
        }
        // 判断最大转出金额
        $maxTransferOut = getConfig('yuebao_transfer_out_max', 0);
        if ($maxTransferOut > 0 && $amount > $maxTransferOut) {
            $this->error('logic.MAX_TRANSFER_OUT', 400, [
                'amount' => $maxTransferOut
            ]);
        }

        try {
            $this->container->get(YuebaoService::class)->transferOutToAccount($user_id, $amount);
        }
        catch (LogicException $e) {
            $this->error($e->getMessage(), 400, (array)Context::get('__replace', []));
        }

        $this->success();
    }

    /**
     * 资金日志
     *
     * @GetMapping(path="coin_log")
     */
    public function coinLog()
    {
        $user_id = JwtInstance::instance()->build()->getId();
        $params  = $this->request->all();

        $result = $this->container->get(YuebaoCoinLogDAO::class)->getCoinLog($user_id, $params);

        $this->success($result);
    }

    /**
     * 账户余额
     *
     * @GetMapping(path="account")
     */
    public function account()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $account = $this->container->get(YuebaoService::class)->getYuebaoAccount($user_id);
        
        $income = $this->container->get(MFIncomeDAO::class)->getTotalIncome($user_id);

        $this->success([
            'balance'          => $account->balance,
            'nextWithdrawTime' => $account->withdraw_time === 0 ? null : date('m-d H:i', $account->withdraw_time),
            'income'          => $income
        ]);
    }
}