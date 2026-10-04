<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class CheckoutController extends Controller
{
    public function create(Listing $listing)
    {
        // Only buyers can purchase
        if (auth()->user()->hasRole('seller') && !auth()->user()->hasRole('buyer')) {
            return back()->with('error', 'Sellers cannot purchase listings.');
        }

        // Cannot buy your own listing
        if ($listing->user_id === auth()->id()) {
            return back()->with('error', 'You cannot buy your own listing.');
        }

        // Check availability
        if ($listing->quantity <= 0 || $listing->status !== 'active') {
            return back()->with('error', 'This listing is no longer available.');
        }

        // Create pending order first
        $order = Order::create([
            'buyer_id'         => auth()->id(),
            'listing_id'       => $listing->id,
            'unit_price'       => $listing->price,
            'quantity'         => 1,
            'total'            => $listing->price,
            'status'           => 'pending',
            'shipping_address' => auth()->user()->name . ', Pakistan',
        ]);

        // Create Stripe Checkout session
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'usd',
                    'unit_amount'  => (int) ($listing->price * 100), // cents
                    'product_data' => [
                        'name'        => $listing->title,
                        'description' => substr($listing->description, 0, 255),
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('checkout.cancel') . '?order_id=' . $order->id,
            'metadata'    => [
                'order_id' => $order->id,
            ],
        ]);

        // Save stripe session id on order
        $order->update(['stripe_session_id' => $session->id]);

        // Redirect to Stripe hosted page
        return redirect($session->url);
    }

    public function success(Request $request)
{
    $order = null;

    if ($request->filled('session_id')) {
        $order = Order::where('stripe_session_id', $request->session_id)
            ->where('buyer_id', auth()->id())
            ->with(['listing'])
            ->first();

        // Fallback: if webhook hasn't fired yet, mark as paid here
        if ($order && $order->status === 'pending') {
            $order->update(['status' => 'paid']);

            // Decrease quantity
            $listing = $order->listing;
            if ($listing && $listing->quantity > 0) {
                $listing->decrement('quantity');

                if ($listing->fresh()->quantity <= 0) {
                    $listing->update(['status' => 'sold']);
                }
            }
        }
    }

    return view('checkout.success', compact('order'));
}

    public function cancel(Request $request)
    {
        if ($request->filled('order_id')) {
            $order = Order::where('id', $request->order_id)
                ->where('buyer_id', auth()->id())
                ->first();

            if ($order && $order->status === 'pending') {
                $order->update(['status' => 'cancelled']);
            }

            $listing = $order->listing ?? null;
            return view('checkout.cancel', compact('listing'));
        }

        return view('checkout.cancel', ['listing' => null]);
    }

    // Stripe webhook (FR-PAY-02)
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.secret_webhook');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        // Handle checkout completed
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $order = Order::where('stripe_session_id', $session->id)
                ->with('listing')
                ->first();

            if ($order && $order->status === 'pending') {
                // Update order status to paid
                $order->update(['status' => 'paid']);

                // Decrease listing quantity
                $listing = $order->listing;
                if ($listing && $listing->quantity > 0) {
                    $listing->decrement('quantity');

                    // If quantity reaches 0 mark as sold
                    if ($listing->fresh()->quantity <= 0) {
                        $listing->update(['status' => 'sold']);
                    }
                }
            }
        }

        return response('OK', 200);
    }
}
