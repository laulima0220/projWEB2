<?php

namespace Api\Http;

use stdClass;
use DomainException;
use Exception;
use InvalidArgumentException;
use UnexpectedValueException;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

class MeuTokenJWT
{
    private const KEY = 'x9S4q0v+V0IjvHkG20uAxaHx1ijj+q1HWjHKv+ohxp/oK+77qyXkVj/l4QYHHTF3';
    private const ALGORITHM = 'HS256';
    private const TYPE = 'JWT';

    private ?stdClass $payload;
    private string $iss;
    private string $aud;
    private string $sub;
    private int $duration;

    public function __construct()
    {
        $this->payload = null;

        $this->iss = 'http://localhost';

        $this->aud = 'http://localhost';

        $this->sub = 'acesso_sistema';

        // 30 dias
        $this->duration = 3600 * 24 * 30;
    }

    public function gerarToken(stdClass $claims): string
    {
        $headers = [
            'alg' => self::ALGORITHM,
            'typ' => self::TYPE
        ];

        $payload = [
            'iss' => $this->iss,

            'aud' => $this->aud,

            'sub' => $this->sub,

            'iat' => time(),

            'nbf' => time(),

            'exp' => time() + $this->duration,

            'jti' => bin2hex(random_bytes(16)),

            'usuario' => [
                'name' => $claims->name ?? null,
                'email' => $claims->email ?? null,
                'role' => $claims->role ?? null,
                'idUsuario' => $claims->idUsuario ?? null
            ],
        ];

        return JWT::encode($payload, self::KEY, self::ALGORITHM, null, $headers);
    }

    public function validateToken(string $stringToken): bool
    {
        if (empty($stringToken)) {
            return false;
        }
        $token = trim($stringToken);

        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        $padrao =
            '/^[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+$/';

        if (preg_match($padrao, $token) !== 1) {
            return false;
        }

        try {
             $payloadValido = JWT::decode($token, new Key(self::KEY, self::ALGORITHM));

            if (!isset($payloadValido->iss) || $payloadValido->iss !== $this->iss) {
                return false;
            }

            if (!isset($payloadValido->aud) || $payloadValido->aud !== $this->aud) {
                return false;
            }

            if (!isset($payloadValido->sub) || $payloadValido->sub !== $this->sub) {
                return false;
            }

            $this->payload = $payloadValido;

            return true;
        } catch (
            SignatureInvalidException |
            BeforeValidException |
            ExpiredException |
            InvalidArgumentException |
            DomainException |
            UnexpectedValueException |
            Exception $e
        ) {
            return false;
        }
    }

    public function getPayload(): ?stdClass
    {
        return $this->payload;
    }
    public function setPayload(?stdClass $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function limparPayload(): self
    {
        $this->payload = null;

        return $this;
    }

    public function getIss(): string
    {
        return $this->iss;
    }
    public function setIss(string $iss): self
    {
        $this->iss = $iss;

        return $this;
    }

        public function getAud(): string
    {
        return $this->aud;
    }
    public function setAud(string $aud): self
    {
        $this->aud = $aud;

        return $this;
    }

        public function getSub(): string
    {
        return $this->sub;
    }
    public function setSub(string $sub): self
    {
        $this->sub = $sub;

        return $this;
    }

        public function getDuration(): int
    {
        return $this->duration;
    }
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
