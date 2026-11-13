<?php

namespace App\Http\Controllers;

use App\Services\WarehouseApiService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct(protected WarehouseApiService $api) {}

    protected function activeWarehouseId(): ?int
    {
        $id = session('active_warehouse_id');

        return $id !== null ? (int) $id : null;
    }

    public function dashboard()
    {
        $warehouseId = $this->activeWarehouseId();
        $filter = array_filter(['warehouse_id' => $warehouseId]);

        $inboundPos = $this->api->getInboundPos($filter);
        $outboundOrders = $this->api->getOutboundOrders($filter);
        $transfers = $this->api->getInTransitTransfers($filter);
        $opnameHistory = $this->api->getOpnameHistory($filter);

        return view('warehouse.dashboard', compact(
            'warehouseId',
            'inboundPos',
            'outboundOrders',
            'transfers',
            'opnameHistory'
        ));
    }

    public function scanner(Request $request)
    {
        $warehouseId = $this->activeWarehouseId();
        $barcode = trim((string) $request->query('barcode', ''));
        $scanResult = null;
        $scanError = null;

        if ($barcode !== '') {
            $res = $this->api->scanBarcode($barcode, $warehouseId);
            if ($res['ok']) {
                $scanResult = $res['data'];
            } else {
                $scanError = $res['data']['message'] ?? "Barcode '{$barcode}' tidak ditemukan.";
            }

            if ($request->expectsJson()) {
                return response()->json($res['data'], $res['status']);
            }
        }

        return view('warehouse.scanner', compact('barcode', 'scanResult', 'scanError', 'warehouseId'));
    }

    public function opname(Request $request)
    {
        $warehouseId = $this->activeWarehouseId();
        $barcode = trim((string) $request->query('barcode', ''));
        $statusFilter = $request->query('status');

        $scannedProduct = null;
        $scanError = null;

        if ($barcode !== '') {
            $res = $this->api->scanBarcode($barcode, $warehouseId);
            if ($res['ok']) {
                $scannedProduct = $res['data'];
            } else {
                $scanError = $res['data']['message'] ?? "Barcode '{$barcode}' tidak ditemukan.";
            }
        }

        $history = $this->api->getOpnameHistory(array_filter([
            'warehouse_id' => $warehouseId,
            'status' => $statusFilter,
        ]));

        return view('warehouse.opname', compact(
            'warehouseId',
            'barcode',
            'scannedProduct',
            'scanError',
            'statusFilter',
            'history'
        ));
    }

    public function storeOpname(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => ['required', 'integer'],
            'product_id' => ['required', 'integer'],
            'unit_id' => ['nullable', 'integer'],
            'physical_qty' => ['required', 'numeric', 'min:0'],
            'batch_number' => ['nullable', 'string', 'max:100'],
            'expiration_date' => ['nullable', 'date'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $res = $this->api->submitOpnameCount($validated);

        if ($res['ok']) {
            return redirect()->route('opname')
                ->with('success', $res['data']['message'] ?? 'Hasil hitung fisik berhasil diajukan.')
                ->with('opname_result', $res['data']['data'] ?? null);
        }

        return back()
            ->withErrors(['opname' => $res['data']['message'] ?? 'Gagal menyimpan data stock opname.'])
            ->withInput();
    }

    public function inbound(Request $request, $id = null)
    {
        $warehouseId = $this->activeWarehouseId();
        $search = trim((string) $request->query('search', ''));

        $pos = $this->api->getInboundPos(array_filter([
            'warehouse_id' => $warehouseId,
            'search' => $search,
        ]));

        $poList = $pos['data'] ?? [];
        $selectedId = $id ?? ($poList[0]['id'] ?? null);
        $activePo = $selectedId ? $this->api->getInboundPoDetail($selectedId) : null;

        return view('warehouse.inbound', compact('warehouseId', 'search', 'poList', 'selectedId', 'activePo'));
    }

    public function receiveInbound(Request $request, $id)
    {
        $validated = $request->validate([
            'received_date' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.received_qty' => ['required', 'numeric', 'min:0'],
            'items.*.batch_number' => ['nullable', 'string', 'max:100'],
            'items.*.expiration_date' => ['nullable', 'date'],
        ]);

        $filteredItems = collect($validated['items'])
            ->filter(fn ($item) => (float) ($item['received_qty'] ?? 0) > 0)
            ->values()
            ->all();

        if (empty($filteredItems)) {
            return back()
                ->withErrors(['inbound' => 'Masukkan minimal 1 barang dengan jumlah terima lebih dari 0.'])
                ->withInput();
        }

        $res = $this->api->receiveInboundPo($id, [
            'received_date' => $validated['received_date'] ?? now()->toDateString(),
            'items' => $filteredItems,
        ]);

        if ($res['ok']) {
            return redirect()->route('inbound.show', $id)
                ->with('success', $res['data']['message'] ?? 'Penerimaan barang PO berhasil disimpan.')
                ->with('price_alerts', $res['data']['price_alerts'] ?? []);
        }

        return back()
            ->withErrors(['inbound' => $res['data']['message'] ?? 'Gagal memproses penerimaan PO.'])
            ->withInput();
    }

    public function picking(Request $request, $id = null)
    {
        $warehouseId = $this->activeWarehouseId();
        $search = trim((string) $request->query('search', ''));

        $orders = $this->api->getOutboundOrders(array_filter([
            'warehouse_id' => $warehouseId,
            'search' => $search,
        ]));

        $orderList = $orders['data'] ?? [];
        $selectedId = $id ?? ($orderList[0]['id'] ?? null);
        $activeOrder = $selectedId ? $this->api->getOutboundOrderDetail($selectedId) : null;

        $verifiedKey = $activeOrder ? 'verified_picking_items.' . ($activeOrder['id'] ?? $selectedId) : null;
        $verifiedItemIds = $verifiedKey ? session($verifiedKey, []) : [];

        return view('warehouse.picking', compact(
            'warehouseId',
            'search',
            'orderList',
            'selectedId',
            'activeOrder',
            'verifiedItemIds'
        ));
    }

    public function verifyPickingItem(Request $request, $id)
    {
        $validated = $request->validate([
            'barcode' => ['required', 'string'],
        ]);

        $res = $this->api->verifyOutboundItem($id, $validated['barcode']);

        if ($res['ok'] && ! empty($res['data']['matched'])) {
            $matchedItem = $res['data']['item'] ?? [];
            $itemId = $matchedItem['id'] ?? null;

            if ($itemId !== null) {
                $existing = session("verified_picking_items.{$id}", []);
                if (! in_array($itemId, $existing, true)) {
                    $existing[] = $itemId;
                    session(["verified_picking_items.{$id}" => $existing]);
                }
            }

            if ($request->expectsJson()) {
                return response()->json($res['data'], 200);
            }

            return redirect()->route('picking.show', $id)
                ->with('success', $res['data']['message'] ?? 'Barang cocok dengan daftar picking.')
                ->with('matched_item', $matchedItem);
        }

        if ($request->expectsJson()) {
            return response()->json($res['data'], $res['status']);
        }

        return redirect()->route('picking.show', $id)
            ->withErrors(['barcode' => $res['data']['message'] ?? 'Barcode tidak cocok dengan item pesanan ini.']);
    }

    public function completePicking(Request $request, $id)
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $res = $this->api->completeOutboundOrder($id, $validated['notes'] ?? null);

        if ($res['ok']) {
            session()->forget("verified_picking_items.{$id}");

            return redirect()->route('picking')
                ->with('success', $res['data']['message'] ?? 'Picking & packing pesanan selesai. Siap dimuat.');
        }

        return back()
            ->withErrors(['picking' => $res['data']['message'] ?? 'Gagal menyelesaikan picking pesanan.']);
    }

    public function transfers(Request $request, $id = null)
    {
        $warehouseId = $this->activeWarehouseId();

        $transfers = $this->api->getInTransitTransfers(array_filter([
            'warehouse_id' => $warehouseId,
        ]));

        $transferList = $transfers['data'] ?? [];
        $selectedId = $id ?? ($transferList[0]['id'] ?? null);
        $activeTransfer = $selectedId ? $this->api->getTransferDetail($selectedId) : null;

        return view('warehouse.transfer', compact('warehouseId', 'transferList', 'selectedId', 'activeTransfer'));
    }

    public function receiveTransfer(Request $request, $id)
    {
        $validated = $request->validate([
            'received' => ['required', 'array', 'min:1'],
            'received.*' => ['required', 'numeric', 'min:0'],
            'receive_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $res = $this->api->receiveTransfer($id, $validated['received'], $validated['receive_notes'] ?? null);

        if ($res['ok']) {
            return redirect()->route('transfers')
                ->with('success', $res['data']['message'] ?? 'Mutasi stok berhasil diterima.');
        }

        return back()
            ->withErrors(['transfer' => $res['data']['message'] ?? 'Gagal menerima mutasi stok.'])
            ->withInput();
    }
}
