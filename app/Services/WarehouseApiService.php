<?php

namespace App\Services;

use App\Exceptions\ApiSessionExpired;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

/** HTTP client untuk berkomunikasi dengan backend Warehouse API ModernGrosir. */
class WarehouseApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.moderngrosir.url', 'http://127.0.0.1:8000/api'), '/');
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->timeout(15);
    }

    protected function authClient(): PendingRequest
    {
        return $this->client()->withToken((string) Session::get('api_token'));
    }

    protected function checkAuth(Response $response): void
    {
        if ($response->status() === 401) {
            Session::forget(['api_token', 'user', 'warehouses', 'active_warehouse_id']);
            throw new ApiSessionExpired();
        }
    }

    protected function getAuthenticatedJson(string $endpoint, array $query = [], array $fallback = []): array
    {
        try {
            $response = $this->authClient()->get($endpoint, $query);
            $this->checkAuth($response);

            return $response->successful() ? ($response->json() ?? $fallback) : $fallback;
        } catch (ApiSessionExpired $e) {
            throw $e;
        } catch (\Throwable) {
            return $fallback;
        }
    }

    protected function getAuthenticatedDetail(string $endpoint): ?array
    {
        $data = $this->getAuthenticatedJson($endpoint, [], []);

        return $data !== [] ? $data : null;
    }

    protected function postAuthenticatedJson(string $endpoint, array $payload = []): array
    {
        try {
            $response = $this->authClient()->post($endpoint, $payload);
            $this->checkAuth($response);

            return [
                'ok' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json() ?? [],
            ];
        } catch (ApiSessionExpired $e) {
            throw $e;
        } catch (\Throwable) {
            return [
                'ok' => false,
                'status' => 500,
                'data' => ['message' => 'Gagal menghubungi server API gudang.'],
            ];
        }
    }

    public function getStoreInfo(): array
    {
        return Cache::remember('warehouse_store_info_tenant', 300, function () {
            try {
                $response = $this->client()->timeout(5)->get('/store');
                if ($response->successful()) {
                    return $response->json('store') ?? [];
                }
            } catch (\Throwable) {
                // Abaikan bila backend offline agar layar login tetap memakai branding default.
            }

            return [
                'name' => 'Grosera',
                'clean_name' => 'Grosera',
                'monogram' => 'G',
                'primary_color' => '#FF783F',
                'secondary_color' => '#0F172A',
            ];
        });
    }

    public function login(string $email, string $password): array
    {
        try {
            $response = $this->client()->post('/warehouse/login', [
                'email' => $email,
                'password' => $password,
            ]);

            $data = $response->json() ?? [];

            if ($response->successful() && ! empty($data['token'])) {
                return [
                    'ok' => true,
                    'status' => $response->status(),
                    'token' => $data['token'],
                    'user' => $data['user'] ?? [],
                    'tenant' => $data['tenant'] ?? [],
                    'warehouses' => $data['warehouses'] ?? [],
                ];
            }

            return [
                'ok' => false,
                'status' => $response->status(),
                'message' => $data['message'] ?? 'Email atau kata sandi salah.',
            ];
        } catch (\Throwable) {
            return [
                'ok' => false,
                'status' => 500,
                'message' => 'Tidak dapat terhubung ke server gudang. Periksa koneksi API.',
            ];
        }
    }

    public function logout(): bool
    {
        try {
            if (Session::has('api_token')) {
                $this->authClient()->post('/warehouse/logout');
            }
        } catch (\Throwable) {
            // Sesi lokal tetap wajib dihapus meski server gagal dihubungi.
        } finally {
            Session::forget(['api_token', 'user', 'warehouses', 'active_warehouse_id']);
        }

        return true;
    }

    public function getProfile(): array
    {
        $data = $this->getAuthenticatedJson('/warehouse/profile', [], []);

        if (! empty($data['user'])) {
            Session::put('user', $data['user']);
        }

        if (isset($data['warehouses']) && is_array($data['warehouses'])) {
            Session::put('warehouses', $data['warehouses']);
        }

        return $data;
    }

    public function scanBarcode(string $barcode, ?int $warehouseId = null): array
    {
        try {
            $query = ['barcode' => $barcode];
            if ($warehouseId !== null) {
                $query['warehouse_id'] = $warehouseId;
            }

            $response = $this->authClient()->get('/warehouse/scan', $query);
            $this->checkAuth($response);

            return [
                'ok' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json() ?? [],
            ];
        } catch (ApiSessionExpired $e) {
            throw $e;
        } catch (\Throwable) {
            return [
                'ok' => false,
                'status' => 500,
                'data' => ['message' => 'Gagal memindai barcode ke server API.'],
            ];
        }
    }

    public function getOpnameHistory(array $filters = []): array
    {
        return $this->getAuthenticatedJson('/warehouse/opname/history', $filters, ['data' => []]);
    }

    public function submitOpnameCount(array $payload): array
    {
        return $this->postAuthenticatedJson('/warehouse/opname/count', $payload);
    }

    public function getInboundPos(array $filters = []): array
    {
        return $this->getAuthenticatedJson('/warehouse/inbound/po', $filters, ['data' => []]);
    }

    public function getInboundPoDetail(string|int $id): ?array
    {
        return $this->getAuthenticatedDetail("/warehouse/inbound/po/{$id}");
    }

    public function receiveInboundPo(string|int $id, array $payload): array
    {
        return $this->postAuthenticatedJson("/warehouse/inbound/po/{$id}/receive", $payload);
    }

    public function getOutboundOrders(array $filters = []): array
    {
        return $this->getAuthenticatedJson('/warehouse/outbound/orders', $filters, ['data' => []]);
    }

    public function getOutboundOrderDetail(string|int $id): ?array
    {
        return $this->getAuthenticatedDetail("/warehouse/outbound/orders/{$id}");
    }

    public function verifyOutboundItem(string|int $id, string $barcode): array
    {
        return $this->postAuthenticatedJson("/warehouse/outbound/orders/{$id}/verify-item", [
            'barcode' => $barcode,
        ]);
    }

    public function completeOutboundOrder(string|int $id, ?string $notes = null): array
    {
        $payload = [];
        if ($notes !== null && trim($notes) !== '') {
            $payload['notes'] = trim($notes);
        }

        return $this->postAuthenticatedJson("/warehouse/outbound/orders/{$id}/complete", $payload);
    }

    public function getInTransitTransfers(array $filters = []): array
    {
        return $this->getAuthenticatedJson('/warehouse/transfers/in-transit', $filters, ['data' => []]);
    }

    public function getTransferDetail(string|int $id): ?array
    {
        return $this->getAuthenticatedDetail("/warehouse/transfers/{$id}");
    }

    public function receiveTransfer(string|int $id, array $received, ?string $receiveNotes = null): array
    {
        return $this->postAuthenticatedJson("/warehouse/transfers/{$id}/receive", [
            'received' => $received,
            'receive_notes' => $receiveNotes,
        ]);
    }
}
