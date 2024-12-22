### **Document: Comprehensive Implementation of eCommerce Functionality and Payment Gateway Integration in Laravel**

---

### **Table of Contents**

1. [**Overview**](#1-overview)  
   - [eCommerce Functionality](#ecommerce-functionality)  
   - [Payment Gateway Integration](#payment-gateway-integration)  

2. [**eCommerce Functionality Implementation**](#2-ecommerce-functionality-implementation)  
   - [Models and Relationships](#models-and-relationships)  
   - [Controllers for Cart Management](#controllers-for-cart-management)  
   - [Checkout Process with Blade Templates](#checkout-process-with-blade-templates)  

3. [**Payment Gateway Integration Implementation**](#3-payment-gateway-integration-implementation)  
   - [Installing and Configuring Stripe](#installing-and-configuring-stripe)  
   - [Collecting Payment Details Securely with Stripe Elements](#collecting-payment-details-securely-with-stripe-elements)  
   - [Server-Side Payment Processing](#server-side-payment-processing)  
   - [Post-Payment Actions](#post-payment-actions)  

---

### **1. Overview**

#### **eCommerce Functionality**
This involves:
1. Managing shopping carts.
2. Handling user orders.
3. Facilitating a seamless checkout process.

#### **Payment Gateway Integration**
A payment gateway processes payments securely. Here, we’ll use **Stripe** for its simplicity and reliability.

---

### **2. eCommerce Functionality Implementation**

#### **Models and Relationships**

##### **1. Product Model**
The `Product` model stores product details like name, description, price, and stock quantity.
```bash
php artisan make:model Product -m
```
**Migration**:
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 8, 2);
    $table->integer('stock');
    $table->timestamps();
});
```

##### **2. Cart and CartItem Models**
The `Cart` model links to a user and holds `CartItem` records, which represent individual items in the cart.

**Cart Migration**:
```php
Schema::create('carts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

**CartItem Migration**:
```php
Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cart_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->integer('quantity');
    $table->timestamps();
});
```

##### **3. Order Model**
The `Order` model stores finalized orders.
**Order Migration**:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->decimal('total', 10, 2);
    $table->string('status')->default('pending');
    $table->timestamps();
});
```

##### **4. Relationships**
**User**:
```php
public function cart()
{
    return $this->hasOne(Cart::class);
}

public function orders()
{
    return $this->hasMany(Order::class);
}
```

**Cart**:
```php
public function items()
{
    return $this->hasMany(CartItem::class);
}
```

**CartItem**:
```php
public function product()
{
    return $this->belongsTo(Product::class);
}
```

**Order**:
```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

---

#### **Controllers for Cart Management**

##### **1. CartController**

**Add Item to Cart**:
```php
public function addToCart(Request $request)
{
    $cart = auth()->user()->cart ?? Cart::create(['user_id' => auth()->id()]);
    $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

    if ($cartItem) {
        $cartItem->increment('quantity', $request->quantity);
    } else {
        $cart->items()->create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
    }

    return response()->json(['message' => 'Item added to cart']);
}
```

**Remove Item from Cart**:
```php
public function removeFromCart($itemId)
{
    $cartItem = CartItem::findOrFail($itemId);
    $cartItem->delete();

    return response()->json(['message' => 'Item removed from cart']);
}
```

**View Cart**:
```php
public function viewCart()
{
    $cart = auth()->user()->cart;
    $items = $cart->items->load('product');
    $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

    return response()->json([
        'items' => $items,
        'total' => $total,
    ]);
}
```

---

#### **Checkout Process with Blade Templates**

##### **Checkout Page (Blade Template)**
```html
<h1>Checkout</h1>
<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cart->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ $item->product->price }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<h3>Total: ${{ $total }}</h3>
<form action="{{ route('order.place') }}" method="POST">
    @csrf
    <button type="submit">Place Order</button>
</form>
```

##### **OrderController**
```php
public function placeOrder()
{
    $cart = auth()->user()->cart;
    $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);

    $order = Order::create([
        'user_id' => auth()->id(),
        'total' => $total,
        'status' => 'pending',
    ]);

    foreach ($cart->items as $item) {
        $order->items()->create([
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
        ]);
        $item->product->decrement('stock', $item->quantity);
    }

    $cart->items()->delete();

    return redirect()->route('order.confirmation', $order);
}
```

---

### **3. Payment Gateway Integration Implementation**

#### **Installing and Configuring Stripe**

1. Install Stripe SDK:
   ```bash
   composer require stripe/stripe-php
   ```

2. Add Credentials to `.env`:
   ```env
   STRIPE_KEY=your_stripe_key
   STRIPE_SECRET=your_stripe_secret
   ```

---

#### **Collecting Payment Details Securely with Stripe Elements**

##### **Blade Template (Checkout Page)**
```html
<script src="https://js.stripe.com/v3/"></script>
<form id="payment-form">
    <div id="card-element"></div>
    <button id="submit-button">Pay</button>
</form>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    document.getElementById('payment-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const { paymentMethod } = await stripe.createPaymentMethod({
            type: 'card',
            card: card,
        });

        fetch('/process-payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ payment_method: paymentMethod.id }),
        });
    });
</script>
```

---

#### **Server-Side Payment Processing**

##### **PaymentController**
```php
public function processPayment(Request $request)
{
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    $charge = $stripe->charges->create([
        'amount' => 5000, // Amount in cents
        'currency' => 'usd',
        'source' => $request->payment_method,
    ]);

    return response()->json(['message' => 'Payment successful', 'charge' => $charge]);
}
```

---
