<?php

namespace App\Services;

class AgoraService
{
    private string $appId;
    private string $appCertificate;

    public function __construct()
    {
        $this->appId = config('services.agora.app_id');
        $this->appCertificate = config('services.agora.app_certificate');
    }

    public function generateToken(string $channelName, int $uid, int $expireTime = 3600): string
    {
        // If no certificate, return app ID based token for testing
        if (empty($this->appCertificate)) {
            return $this->appId;
        }

        $currentTimestamp = now()->timestamp;
        $privilegeExpiredTs = $currentTimestamp + $expireTime;

        return $this->buildToken($channelName, $uid, $privilegeExpiredTs);
    }

    private function buildToken(string $channelName, int $uid, int $expireTimestamp): string
    {
        $message = $this->packMessage($channelName, $uid, $expireTimestamp);
        $signature = hash_hmac('sha256', $message, $this->appCertificate, true);

        $content = $this->appId . $message . bin2hex($signature);
        return '006' . base64_encode($content);
    }

    private function packMessage(string $channelName, int $uid, int $expireTimestamp): string
    {
        return pack('NNN', 1, $expireTimestamp, crc32($channelName . $uid));
    }

    public function generateChannelName(int $callerId, int $receiverId): string
    {
        return 'call_' . min($callerId, $receiverId) . '_' . max($callerId, $receiverId) . '_' . time();
    }
}
