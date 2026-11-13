<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WarehouseAppTest extends TestCase
{
    private function warehouseSession(array $overrides = []): array
    {
        return array_merge([
            'api_token' => 'test-warehouse-token',
            'user' => [
                'id' => 10,
                'name' => 'Budi Gudang',
                'email' => 'staf.gudang@grosera.id',
                'roles' => ['warehouse'],
            ],
            'tenant' => [
                'name' => 'Grosera Utama',
                'clean_name' => 'Grosera Utama',
            ],
            'warehouses' => [
                ['id' => 1, 'name' => 'Gudang Pusat', 'type' => 'gudang', 'location' => 'Surabaya'],
                ['id' => 2, 'name' => 'Gudang Cabang', 'type' => 'gudang', 'location' => 'Sidoarjo'],
            ],
            'active_warehouse_id' => 1,
        ], $overrides);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/scanner')->assertRedirect('/login');
        $this->get('/opname')->assertRedirect('/login');
        $this->get('/inbound')->assertRedirect('/login');
        $this->get('/picking')->assertRedirect('/login');
        $this->get('/transfers')->assertRedirect('/login');
    }

    public function test_login_page_renders(): void
    {
        Http::fake([
            '*/store' => Http::response([
                'store' => [
                    'name' => 'Grosera WMS',
                    'clean_name' => 'Grosera WMS',
                    'monogram' => 'G',
                ],
            ], 200),
        ]);

        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Grosera WMS')
            ->assertSee('Masuk ke Akun Gudang');
    }

    public function test_staff_can_login_and_switch_active_warehouse(): void
    {
        Http::fake([
            '*/warehouse/login' => Http::response([
                'token' => 'wms-token-abc',
                'user' => [
                    'id' => 10,
                    'name' => 'Budi Gudang',
                    'email' => 'staf.gudang@grosera.id',
                    'roles' => ['warehouse'],
                ],
                'tenant' => [
                    'name' => 'Grosera',
                    'clean_name' => 'Grosera',
                ],
                'warehouses' => [
                    ['id' => 1, 'name' => 'Gudang Pusat', 'type' => 'gudang', 'location' => 'Surabaya'],
                    ['id' => 2, 'name' => 'Gudang Cabang', 'type' => 'gudang', 'location' => 'Sidoarjo'],
                ],
            ], 200),
        ]);

        $response = $this->post('/login', [
            'email' => 'staf.gudang@grosera.id',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertEquals('wms-token-abc', session('api_token'));
        $this->assertEquals(1, session('active_warehouse_id'));

        $this->post('/warehouse/select', ['warehouse_id' => 2])
            ->assertRedirect();

        $this->assertEquals(2, session('active_warehouse_id'));
    }

    public function test_login_displays_error_on_rejected_credentials(): void
    {
        Http::fake([
            '*/warehouse/login' => Http::response([
                'message' => 'Akses ditolak. Akun ini tidak memiliki hak akses gudang.',
            ], 403),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'reseller@toko.id',
            'password' => 'wrong',
        ]);

        $response->assertRedirect('/login')
            ->assertSessionHasErrors('email');
        $this->assertFalse(session()->has('api_token'));
    }

    public function test_dashboard_renders_operational_queues(): void
    {
        Http::fake([
            '*/warehouse/inbound/po*' => Http::response([
                'data' => [
                    ['id' => 5, 'po_number' => 'PO-2026-005', 'supplier_name' => 'PT Indofood', 'status' => 'ordered', 'total_items' => 3],
                ],
            ], 200),
            '*/warehouse/outbound/orders*' => Http::response([
                'data' => [
                    ['id' => 8, 'order_number' => 'ORD-00008', 'customer_name' => 'Toko Berkah', 'status' => 'processing', 'total_items' => 4],
                ],
            ], 200),
            '*/warehouse/transfers/in-transit*' => Http::response([
                'data' => [
                    ['id' => 3, 'transfer_number' => 'TRF-003', 'from_warehouse' => ['name' => 'Pusat'], 'to_warehouse' => ['name' => 'Cabang'], 'total_items' => 2],
                ],
            ], 200),
            '*/warehouse/opname/history*' => Http::response([
                'data' => [
                    ['id' => 11, 'product_name' => 'Gula Pasir 1kg', 'status' => 'pending'],
                ],
            ], 200),
        ]);

        $this->withSession($this->warehouseSession())
            ->get('/')
            ->assertStatus(200)
            ->assertSee('PO-2026-005')
            ->assertSee('ORD-00008')
            ->assertSee('TRF-003');
    }

    public function test_scanner_displays_product_and_fefo_batches(): void
    {
        Http::fake([
            '*/warehouse/scan*' => Http::response([
                'product' => [
                    'id' => 101,
                    'name' => 'Minyak Goreng Bimoli 2L',
                    'sku' => 'SKU-MNY-2L',
                    'category' => 'Sembako',
                ],
                'base_unit' => ['id' => 1, 'name' => 'Pouch'],
                'scanned_unit' => ['id' => 2, 'name' => 'Dus', 'conversion_ratio' => 6, 'barcode' => '899123456', 'is_base' => false],
                'units' => [
                    ['id' => 1, 'unit_id' => 1, 'name' => 'Pouch', 'conversion_ratio' => 1, 'barcode' => '899123450'],
                    ['id' => 2, 'unit_id' => 2, 'name' => 'Dus', 'conversion_ratio' => 6, 'barcode' => '899123456'],
                ],
                'total_stock' => 120,
                'selected_warehouse_stock' => 84,
                'stock_by_warehouse' => [
                    ['warehouse_id' => 1, 'warehouse_name' => 'Gudang Pusat', 'type' => 'gudang', 'quantity' => 84],
                ],
                'batches' => [
                    [
                        'id' => 77,
                        'batch_number' => 'BCH-NOV-01',
                        'warehouse_id' => 1,
                        'warehouse_name' => 'Gudang Pusat',
                        'quantity' => 84,
                        'expiration_date' => '2027-01-15',
                        'is_expired' => false,
                        'days_until_expiration' => 63,
                    ],
                ],
            ], 200),
        ]);

        $this->withSession($this->warehouseSession())
            ->get('/scanner?barcode=899123456')
            ->assertStatus(200)
            ->assertSee('Minyak Goreng Bimoli 2L')
            ->assertSee('SKU-MNY-2L')
            ->assertSee('BCH-NOV-01')
            ->assertSee('84');
    }

    public function test_staff_can_submit_stock_opname_count(): void
    {
        Http::fake([
            '*/warehouse/opname/count' => Http::response([
                'success' => true,
                'message' => 'Data Stock Opname berhasil diajukan untuk persetujuan Admin.',
                'data' => [
                    'adjustment_id' => 99,
                    'product_id' => 101,
                    'product_name' => 'Minyak Goreng Bimoli 2L',
                    'warehouse_id' => 1,
                    'warehouse_name' => 'Gudang Pusat',
                    'system_stock' => 84,
                    'physical_stock' => 90,
                    'difference' => 6,
                    'status' => 'pending',
                ],
            ], 201),
        ]);

        $response = $this->withSession($this->warehouseSession())
            ->post('/opname', [
                'warehouse_id' => 1,
                'product_id' => 101,
                'unit_id' => 2,
                'physical_qty' => 15,
                'batch_number' => 'BCH-NOV-01',
                'reason' => 'Opname rak minyak',
            ]);

        $response->assertRedirect('/opname')
            ->assertSessionHas('success');

        Http::assertSent(fn ($request) => str_contains($request->url(), '/warehouse/opname/count')
            && $request['product_id'] == 101
            && $request['physical_qty'] == 15);
    }

    public function test_staff_can_view_and_receive_inbound_po(): void
    {
        Http::fake([
            '*/warehouse/inbound/po/5' => Http::response([
                'id' => 5,
                'po_number' => 'PO-2026-005',
                'status' => 'ordered',
                'supplier' => ['id' => 1, 'name' => 'PT Indofood'],
                'warehouse' => ['id' => 1, 'name' => 'Gudang Pusat'],
                'items' => [
                    [
                        'id' => 50,
                        'product_id' => 101,
                        'product_name' => 'Indomie Goreng Dus',
                        'sku' => 'IND-GRG',
                        'unit_id' => 2,
                        'unit_name' => 'Dus',
                        'ordered_qty' => 20,
                        'received_qty' => 0,
                        'remaining_qty' => 20,
                        'has_expiration' => true,
                        'default_expiration_date' => '2027-06-01',
                    ],
                ],
            ], 200),
            '*/warehouse/inbound/po/5/receive' => Http::response([
                'success' => true,
                'message' => 'Penerimaan PO berhasil diproses.',
                'price_alerts' => [],
            ], 200),
            '*/warehouse/inbound/po*' => Http::response([
                'data' => [
                    ['id' => 5, 'po_number' => 'PO-2026-005', 'supplier_name' => 'PT Indofood', 'warehouse_name' => 'Gudang Pusat', 'status' => 'ordered', 'total_items' => 1],
                ],
            ], 200),
        ]);

        $this->withSession($this->warehouseSession())
            ->get('/inbound/5')
            ->assertStatus(200)
            ->assertSee('PO-2026-005')
            ->assertSee('Indomie Goreng Dus');

        $receiveResponse = $this->withSession($this->warehouseSession())
            ->post('/inbound/5/receive', [
                'received_date' => '2026-11-13',
                'items' => [
                    [
                        'item_id' => 50,
                        'product_id' => 101,
                        'received_qty' => 20,
                        'batch_number' => 'BCH-IND-20',
                        'expiration_date' => '2027-06-01',
                    ],
                ],
            ]);

        $receiveResponse->assertRedirect('/inbound/5')
            ->assertSessionHas('success');

        Http::assertSent(fn ($request) => str_contains($request->url(), '/warehouse/inbound/po/5/receive')
            && $request['items'][0]['received_qty'] == 20);
    }

    public function test_staff_can_verify_picking_barcode_and_complete_outbound_order(): void
    {
        Http::fake([
            '*/warehouse/outbound/orders/8/verify-item' => Http::sequence()
                ->push([
                    'matched' => true,
                    'message' => 'Barang cocok: Teh Botol Sosro 250ml',
                    'item' => [
                        'id' => 81,
                        'product_id' => 201,
                        'product_name' => 'Teh Botol Sosro 250ml',
                        'sku' => 'SKU-TEH-BOTOL',
                        'ordered_quantity' => 5,
                    ],
                ], 200)
                ->push([
                    'matched' => false,
                    'message' => "Barang dengan barcode 'SALAH' BUKAN bagian dari pesanan ini.",
                ], 404),
            '*/warehouse/outbound/orders/8/complete' => Http::response([
                'success' => true,
                'message' => 'Picking & packing pesanan ORD-00008 selesai. Siap dimuat / dibuat surat jalan.',
                'data' => [
                    'id' => 8,
                    'order_number' => 'ORD-00008',
                    'status' => 'packing',
                ],
            ], 200),
            '*/warehouse/outbound/orders/8' => Http::response([
                'id' => 8,
                'order_number' => 'ORD-00008',
                'customer_name' => 'Toko Berkah',
                'warehouse' => ['id' => 1, 'name' => 'Gudang Pusat'],
                'status' => 'processing',
                'items' => [
                    [
                        'id' => 81,
                        'product_id' => 201,
                        'product_name' => 'Teh Botol Sosro 250ml',
                        'sku' => 'SKU-TEH-BOTOL',
                        'unit_name' => 'Dus',
                        'quantity' => 5,
                        'qty_base' => 5,
                        'valid_barcodes' => ['899222333444'],
                    ],
                ],
            ], 200),
            '*/warehouse/outbound/orders*' => Http::response([
                'data' => [
                    ['id' => 8, 'order_number' => 'ORD-00008', 'customer_name' => 'Toko Berkah', 'warehouse_name' => 'Gudang Pusat', 'status' => 'processing', 'total_items' => 1],
                ],
            ], 200),
        ]);

        $this->withSession($this->warehouseSession())
            ->get('/picking/8')
            ->assertStatus(200)
            ->assertSee('ORD-00008')
            ->assertSee('Teh Botol Sosro 250ml');

        $verifyOk = $this->withSession($this->warehouseSession())
            ->post('/picking/8/verify', ['barcode' => '899222333444']);
        $verifyOk->assertRedirect('/picking/8')
            ->assertSessionHas('success');
        $this->assertContains(81, session('verified_picking_items.8', []));

        $verifyFail = $this->withSession($this->warehouseSession())
            ->post('/picking/8/verify', ['barcode' => 'SALAH']);
        $verifyFail->assertRedirect('/picking/8')
            ->assertSessionHasErrors('barcode');

        $completeResponse = $this->withSession($this->warehouseSession(['verified_picking_items' => [8 => [81]]]))
            ->post('/picking/8/complete', ['notes' => '2 Koli lakban oranye']);

        $completeResponse->assertRedirect('/picking')
            ->assertSessionHas('success');
        $this->assertFalse(session()->has('verified_picking_items.8'));

        Http::assertSent(fn ($request) => str_contains($request->url(), '/warehouse/outbound/orders/8/complete')
            && $request['notes'] === '2 Koli lakban oranye');
    }

    public function test_staff_can_receive_in_transit_stock_transfer(): void
    {
        Http::fake([
            '*/warehouse/transfers/3' => Http::response([
                'id' => 3,
                'transfer_number' => 'TRF-003',
                'from_warehouse' => ['id' => 1, 'name' => 'Gudang Pusat'],
                'to_warehouse' => ['id' => 2, 'name' => 'Gudang Cabang'],
                'status' => 'in_transit',
                'items' => [
                    [
                        'id' => 301,
                        'product_id' => 101,
                        'product_name' => 'Kopi Kapal Api Renteng',
                        'sku' => 'SKU-KOPI',
                        'unit_name' => 'Dus',
                        'quantity' => 10,
                        'qty_base' => 10,
                    ],
                ],
            ], 200),
            '*/warehouse/transfers/3/receive' => Http::response([
                'success' => true,
                'message' => 'Mutasi stok TRF-003 berhasil diterima di Gudang Cabang.',
            ], 200),
            '*/warehouse/transfers/in-transit*' => Http::response([
                'data' => [
                    ['id' => 3, 'transfer_number' => 'TRF-003', 'from_warehouse' => ['name' => 'Gudang Pusat'], 'to_warehouse' => ['name' => 'Gudang Cabang'], 'total_items' => 1],
                ],
            ], 200),
        ]);

        $this->withSession($this->warehouseSession())
            ->get('/transfers/3')
            ->assertStatus(200)
            ->assertSee('TRF-003')
            ->assertSee('Kopi Kapal Api Renteng');

        $receiveResponse = $this->withSession($this->warehouseSession())
            ->post('/transfers/3/receive', [
                'received' => [301 => 10],
                'receive_notes' => 'Diterima lengkap tanpa cacat',
            ]);

        $receiveResponse->assertRedirect('/transfers')
            ->assertSessionHas('success');

        Http::assertSent(fn ($request) => str_contains($request->url(), '/warehouse/transfers/3/receive')
            && $request['received'][301] == 10);
    }

    public function test_expired_api_token_clears_session_and_redirects_to_login(): void
    {
        Http::fake([
            '*/warehouse/scan*' => Http::response(['message' => 'Unauthenticated.'], 401),
        ]);

        $response = $this->withSession($this->warehouseSession())
            ->get('/scanner?barcode=SKU-X');

        $response->assertRedirect('/login')
            ->assertSessionHasErrors('email');
        $this->assertFalse(session()->has('api_token'));
    }
}
