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
use App\Service\DAO\MFIncomeDAO;
use App\Service\DAO\MFModeDAO;
use App\Service\DAO\MFOrderDAO;
use App\Service\MFService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Utils\Context;

/**
 * MFController
 *
 * @Controller(prefix="v1/mf")
 * @author  baidu.com
 * @package App\Controller
 */
class MFController extends AbstractController
{
    /**
     * 买入
     *
     * @PostMapping(path="buy")
     */
    public function buy()
    {
        $id     = (int)$this->request->input('id', 0);
        $amount = (int)$this->request->input('amount', 0);
        if ($id <= 0) {
            $this->error('logic.PLEASE_BUY_PRODUCT');
        }
        if ($amount <= 0) {
            $this->error('logic.PLEASE_INPUT_AMOUNT');
        }

        try {
            $this->container->get(MFService::class)->buy($id, $amount);
        }
        catch (LogicException $e) {
            $this->error($e->getMessage(), $e->getCode(), Context::get('_replace', []));
        }

        $this->success();
    }

    /**
     * 卖出
     *
     * @PostMapping(path="sell")
     */
    public function sell()
    {
        $order_no = $this->request->input('order_no', null);
        if ($order_no === null) {
            $this->error('logic.ORDER_NOT_FOUND');
        }

        try {
            $this->container->get(MFService::class)->sell((string)$order_no);
        }
        catch (LogicException $e) {
            $this->error($e->getMessage(), 400, Context::get('_replace'));
        }

        $this->success();
    }

    /**
     * 获取收益
     *
     * @GetMapping(path="income")
     */
    public function income()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $this->success([
            'totalIncome'     => $this->container->get(MFIncomeDAO::class)->getTotalIncome($user_id),
            'yesterdayIncome' => $this->container->get(MFIncomeDAO::class)->getYesterdayIncome($user_id),
            'balance'         => $this->container->get(MFOrderDAO::class)->getTotalAmount($user_id)
        ]);
    }

    /**
     * 获取收益
     *
     * @GetMapping(path="order")
     */
    public function order()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $result = $this->container->get(MFOrderDAO::class)->getOrderListByUserId($user_id);

        $this->success($result);
    }

    /**
     * 获取理财产品列表
     *
     * @GetMapping(path="product")
     */
    public function product()
    {
        $result = $this->container->get(MFModeDAO::class)->get();

        $this->success($result);
    }

    /**
     * 获取收益
     *
     * @GetMapping(path="order/{order_no}")
     * @param string $order_no
     */
    public function orderDetail(string $order_no)
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $result = $this->container->get(MFOrderDAO::class)->findByOrderNo($order_no);
        if (!$result || $result->user_id !== $user_id) {
            $this->error('logic.ORDER_NOT_FOUND');
        }

        $this->success($result);
    }
}