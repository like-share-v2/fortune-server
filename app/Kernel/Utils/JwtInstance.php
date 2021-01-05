<?php

declare(strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version   1.0.0
 * @link      https://github.com/Zunea/hyperf-admin
 */

namespace App\Kernel\Utils;

use App\Exception\ResponseException;
use App\Model\User;
use App\Service\DAO\UserDAO;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Traits\StaticInstance;

/**
 * JWT服务
 *
 * @property int  $uid
 * @property User $user
 * @package App\Kernel\Utils
 */
class JwtInstance
{
    use StaticInstance;

    /**
     * @string
     */
    CONST KEY = 'mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=';

    /**
     * @var int
     */
    public $uid;

    /**
     * @var User
     */
    public $user;

    /**
     * 解析token
     *
     * @param string $token
     *
     * @return $this
     */
    public function decode(string $token): self
    {
        try {
            $decode = (array)JWT::decode($token, self::KEY, ['HS256']);
        }
        catch (ExpiredException $e) {
            throw new ResponseException('logic.LOGIN_EXPIRED', 401);
        }
        catch (\Throwable $e) {
            throw new ResponseException('logic.SERVER_ERROR', 500);
        }

        if (($id = $decode['id'] ?? null) !== null) {
            $this->uid  = $id;
            $this->user = ApplicationContext::getContainer()->get(UserDAO::class)->find((int)$id);
        }

        return $this;
    }

    public function build(): self
    {
        if (empty($this->uid)) {
            throw new ResponseException('logic.NEED_LOGIN', 401);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->uid;
    }

    public function getUser(): ?User
    {
        if ($this->user === null && $this->uid) {
            $this->user = ApplicationContext::getContainer()->get(UserDAO::class)->find($this->uid);
        }

        return $this->user;
    }
}