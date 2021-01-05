<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Crontab\MFIncomeCrontab;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * TestController
 *
 * @Controller()
 * @author  baidu.com
 * @package App\Controller\v1
 */
class TestController extends AbstractController
{
    /**
     * @GetMapping(path="/t")
     */
    public function index()
    {
        $this->container->get(MFIncomeCrontab::class)->execute();
    }
}