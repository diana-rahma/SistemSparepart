<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Parts;
use App\Models\Sparepart;
use App\Models\SparepartPart;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserOrderStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_deducts_ordered_quantity_from_part_stock(): void
    {
        [$user, $part, $sparepart] = $this->createCartWithStock(10, 3);

        $this->actingAs($user)
            ->postJson(route('orders.store'))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('stocks', [
            'part_id' => $part->id,
            'jumlah' => 7,
        ]);
        $this->assertDatabaseHas('order_items', [
            'kode_part' => 'PART-001',
            'jumlah' => 3,
        ]);
        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
        ]);
    }

    public function test_checkout_with_insufficient_stock_does_not_create_order_or_change_stock(): void
    {
        [$user, $part] = $this->createCartWithStock(2, 3);

        $this->actingAs($user)
            ->postJson(route('orders.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('stok');

        $this->assertDatabaseHas('stocks', [
            'part_id' => $part->id,
            'jumlah' => 2,
        ]);
        $this->assertSame(0, Order::count());
        $this->assertSame(1, Cart::where('user_id', $user->id)->count());
    }

    public function test_buyer_cannot_update_order_status(): void
    {
        $user = User::factory()->create(['role' => 'pembeli']);
        $order = Order::create([
            'order_code' => 'ORD-STATUS-001',
            'user_id' => User::factory()->create()->id,
            'status' => 'Menunggu Konfirmasi',
        ]);

        $this->actingAs($user)
            ->patchJson(route('orders.update_status', $order->id), ['status' => 'Selesai'])
            ->assertForbidden();

        $this->assertSame('Menunggu Konfirmasi', $order->fresh()->status);
    }

    public function test_admin_can_update_order_status_through_status_endpoint(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::create([
            'order_code' => 'ORD-STATUS-002',
            'status' => 'Menunggu Konfirmasi',
        ]);

        $this->actingAs($admin)
            ->patchJson(route('orders.update_status', $order->id), ['status' => 'Diproses'])
            ->assertRedirect(route('admin.orders.index'));

        $this->assertSame('Diproses', $order->fresh()->status);
    }

    public function test_checkout_uses_database_price_instead_of_cart_price(): void
    {
        [$user, , $sparepart] = $this->createCartWithStock(10, 3);
        Cart::where('user_id', $user->id)->update(['harga_satuan' => 1]);

        $this->actingAs($user)
            ->postJson(route('orders.store'))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total_price' => 45000]);
        $this->assertDatabaseHas('order_items', [
            'sparepart_id' => $sparepart->id,
            'harga_satuan' => 15000,
            'subtotal' => 45000,
        ]);
    }

    public function test_add_to_cart_uses_database_price_instead_of_submitted_price(): void
    {
        [$user, , $sparepart] = $this->createCartWithStock(10, 3);
        Cart::where('user_id', $user->id)->delete();

        $this->actingAs($user)
            ->postJson(route('cart.add'), [
                'sparepart_id' => $sparepart->id,
                'items' => [[
                    'ref' => 'A1',
                    'kode' => 'PART-001',
                    'nama' => 'Part Test',
                    'jml' => 1,
                    'harga' => 1,
                    'stok' => 0,
                ]],
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'harga_satuan' => 15000,
            'stok_tersedia' => 10,
        ]);
    }

    private function createCartWithStock(int $stock, int $quantity): array
    {
        $user = User::factory()->create();
        $sparepart = Sparepart::create([
            'nama_sparepart' => 'Sparepart Test',
            'gambar' => 'sparepart-test.png',
        ]);
        $part = Parts::create([
            'nama_parts' => 'Part Test',
            'kode_parts' => 'PART-001',
        ]);

        SparepartPart::create([
            'sparepart_id' => $sparepart->id,
            'part_id' => $part->id,
            'nomor_referensi' => 'A1',
            'jumlah' => 1,
        ]);
        Stok::create([
            'part_id' => $part->id,
            'jumlah' => $stock,
            'harga_satuan' => 15000,
        ]);
        Cart::create([
            'user_id' => $user->id,
            'key' => $sparepart->id.'_A1_PART-001',
            'sparepart_id' => $sparepart->id,
            'sparepart_nama' => $sparepart->nama_sparepart,
            'ref' => 'A1',
            'kode_part' => 'PART-001',
            'nama_part' => 'Part Test',
            'jumlah' => $quantity,
            'harga_satuan' => 15000,
            'stok_tersedia' => $stock,
        ]);

        return [$user, $part, $sparepart];
    }
}
