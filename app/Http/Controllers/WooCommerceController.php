<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Automattic\WooCommerce\Client;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{
    // Méthode pour afficher les détails d'un site WooCommerce
    public function show($id)
    {
        // Récupérer les informations du site depuis la base de données
        $site = Site::findOrFail($id);

        // Initialiser le client WooCommerce
        $woocommerce = new Client(
            $site->url . '/wp-json/wc/v3/', // URL de l'API WooCommerce
            $site->consumer_key,            // Consumer Key
            $site->consumer_secret,         // Consumer Secret
            [
                'version' => 'wc/v3', // Version de l'API
            ]
        );

        // Récupérer les produits ou d'autres données de WooCommerce
        try {
            $products = $woocommerce->get('products', ['per_page' => 10]);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la récupération des produits.');
        }

        // Retourner la vue avec les données récupérées
        return view('sites.orders', compact('site', 'products'));
    }
}
