<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DashboardSteamImportController extends Controller
{
    public function index()
    {
        return view('admin.product.steam-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'app_id' => ['required', 'integer', 'min:1'],
        ]);

        $appId = $request->app_id;

        try {
            // Request Steam Store API
            $response = Http::timeout(20)
                ->get('https://store.steampowered.com/api/appdetails', [
                    'appids' => $appId,
                    'cc' => 'id',
                    'l' => 'english',
                ]);

            if (!$response->successful()) {
                return back()->withErrors([
                    'app_id' => 'Gagal menghubungi Steam API.'
                ])->withInput();
            }

            $result = $response->json();

            // Validasi response Steam
            if (
                !isset($result[$appId]) ||
                !$result[$appId]['success'] ||
                empty($result[$appId]['data'])
            ) {
                return back()->withErrors([
                    'app_id' => 'Game tidak ditemukan di Steam.'
                ])->withInput();
            }

            $game = $result[$appId]['data'];

            // Ambil informasi harga
            $priceOverview = $game['price_overview'] ?? null;

            $defaultPrice = $priceOverview
                ? $priceOverview['initial'] / 100
                : 0;

            $price = $priceOverview
                ? $priceOverview['final'] / 100
                : 0;

            $discount = $priceOverview
                ? $priceOverview['discount_percent']
                : 0;
            $screenshots = $game['screenshots'] ?? [];

            // Mengambil seluruh trailer/video dari Steam
            $movies = $game['movies'] ?? [];

            // Mengambil background halaman Steam
            $imagesBg = $game['background_raw'] ?? null;
            $description = '
                <div class="steam-description">

                    <h4>Short Description</h4>
                    <p>' . ($game['short_description'] ?? '') . '</p>

                    <h4>About The Game</h4>
                    <div>' . ($game['about_the_game'] ?? '') . '</div>

                    <h4 class="my-2">Supported Languages</h4>
                    <div>' . ($game['supported_languages'] ?? 'Not available') . '</div>

                    <h4 class="my-2">System Requirements</h4>

                    <div>' . ($game['pc_requirements']['minimum'] ?? 'Not available') . '</div>

                    <div>' . ($game['pc_requirements']['recommended'] ?? 'Not available') . '</div>

                    <h4 class="my-2">Developer</h4>
                    <p>' . implode(', ', $game['developers'] ?? []) . '</p>

                    <h4 class="my-2">Publisher</h4>
                    <p>' . implode(', ', $game['publishers'] ?? []) . '</p>

                    <h4 class="my-2">Platform</h4>
                    <ul>
                        <li>Windows: ' . (!empty($game['platforms']['windows']) ? 'Yes' : 'No') . '</li>
                        <li>Mac: ' . (!empty($game['platforms']['mac']) ? 'Yes' : 'No') . '</li>
                        <li>Linux: ' . (!empty($game['platforms']['linux']) ? 'Yes' : 'No') . '</li>
                    </ul>

                    <p>
                        <a href="' . e($game['website'] ?? '#') . '" target="_blank">
                            Official Website
                        </a>
                    </p>

                </div>
            ';
            // Simpan ke database
            $product = DB::transaction(function () use (
                $appId,
                $game,
                $defaultPrice,
                $price,
                $discount,
                $description,
                $screenshots,
                $movies,
                $imagesBg
            ) {
                return Product::updateOrCreate(
                    [
                        'steam_app_id' => (string) $appId,
                    ],
                    [
                        'name' => $game['name'],
                        'type' => 'Games',
                        'category_id' => 18,
                        'default_price' => $defaultPrice,
                        'price' => $price,
                        'discount' => $discount,
                        'image' => $game['header_image'] ?? null,

                        'images' => $screenshots,
                        'movies' => $movies,
                        'images_bg' => $imagesBg,

                        'description' => $description,
                        'developers' => implode(', ', $game['developers'] ?? []),
                        'publishers' => implode(', ', $game['publishers'] ?? []),
                        'stock' => 9999,
                        'is_active' => true,
                        'is_games' => true,
                        'is_digital' => true,
                    ]
                );
            });

            return redirect()
                ->route('admin.product.index')
                ->with(
                    'success',
                    'Game ' . $product->name . ' berhasil diimport dari Steam!'
                );
        } catch (\Throwable $e) {

            report($e);

            return back()->withErrors([
                'app_id' => config('app.debug')
                    ? 'ERROR: ' . $e->getMessage()
                    : 'Terjadi kesalahan saat mengimport game.'
            ])->withInput();
        }
    }
}
