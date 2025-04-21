<?php

namespace App\Services;

use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestAuthorizationService
{

    public function isValidTestUser($testId, $userIp, $allowedIps)
    {

        //dd($testId, $userIp, $allowedIps);

        if (!$this->isValidIp($userIp, $allowedIps)) {
            return false;
        }

        // Check SSL certificate
        if (!$this->isValidCertificate($testId)) {
            return false;
        }
        // Check group membership
        // dd($this->isInTestGroup($testId, Auth::id()));

        return $this->isInTestGroup($testId, Auth::id()); //I will remove the ! later
    }
    //SSL Certificate
    public function isValidCertificate($testId)
    {
        // Check if test requires certificates
        $requiresCert = DB::table('test_sslcert')
            ->where('test_id', $testId)
            ->exists();
        //test_sslcert ssl_cert
        if (!$requiresCert) {
            return true;
        }
        $clientHash = $this->getClientSslHash();
        return DB::table('test_sslcert')
            ->join('ssl_cert', 'test_sslcert.ssl_id', '=', 'ssl_cert.id')
            ->where('test_id', $testId)
            ->where('hash', $clientHash)
            ->exists();
    }
    public function getClientSslHash()
    {
        // Implement SSL client hash retrieval
        // This depends on your server configuration
        return $_SERVER['SSL_CLIENT_MD5'] ?? '';
    }
    //This will check if the user is in a group allowed for the take test
    public function isInTestGroup($testId, $userId)
    {
        return DB::table('user_groups')
            ->join('test_groups', 'user_groups.group_id', '=', 'test_groups.group_id')
            ->where('test_id', $testId)
            ->where('user_id', $userId)
            ->exists();
    }



    //IP Validation
    public function isValidIp($userIp, $allowedIps)
    {

        if (empty($userIp) || empty($allowedIps)) {
            return false;
        }

        $userIpInt = $this->ipToInt($userIp);
        $ipMasks = explode(',', $allowedIps);

        foreach ($ipMasks as $ipMask) {
            $ipMask = trim($ipMask);

            if (str_contains($ipMask, '*')) {
                // Handle wildcard notation

                $range = $this->wildcardToRange($ipMask);

                if ($this->isInRange($userIpInt, $range)) {

                    return true;
                }
            } elseif (str_contains($ipMask, '-')) {
                // Handle IP range
                $range = explode('-', $ipMask, 2);
                if (count($range) === 2 && $this->isInRange($userIpInt, $range)) {
                    return true;
                }
            } elseif ($userIpInt === $this->ipToInt($ipMask)) {
                // Exact match
                return true;
            }
        }

        return false;
    }

    public function ipToInt($ip)
    {
        return ip2long($ip) ?: 0;
    }

    public function wildcardToRange($wildcardIp)
    {
        $octets = explode('.', $wildcardIp);
        $start = [];
        $end = [];

        foreach ($octets as $octet) {
            if ($octet === '*') {
                $start[] = 0;
                $end[] = 255;
            } else {
                $num = (int) $octet;
                $start[] = $num;
                $end[] = $num;
            }
        }

        return [
            implode('.', $start),
            implode('.', $end)
        ];
    }

    public function isInRange($ipInt, $range)
    {
        $start = $this->ipToInt($range[0]);
        $end = $this->ipToInt($range[1]);
        return $ipInt >= $start && $ipInt <= $end;
    }
}
