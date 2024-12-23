### **Document: Implementing Asynchronous Features and Real-Time Functionality in Laravel**

---

### **Table of Contents**

1. [**Overview**](#1-overview)  
   - [Asynchronous Features](#asynchronous-features)  
   - [Real-Time Features with Laravel Broadcasting](#real-time-features-with-laravel-broadcasting)  

2. [**Asynchronous Features Implementation**](#2-asynchronous-features-implementation)  
   - [Dynamic Product Search](#dynamic-product-search)  
   - [Live Inventory Updates](#live-inventory-updates)  
   - [Quick Add to Cart](#quick-add-to-cart)  
   - [Pagination and Filtering](#pagination-and-filtering)  
   - [Dynamic Pricing and Discounts](#dynamic-pricing-and-discounts)  

3. [**Real-Time Features Implementation**](#3-real-time-features-implementation)  
   - [Real-Time Notifications for Order Status](#real-time-notifications-for-order-status)  
   - [Real-Time Stock Alerts](#real-time-stock-alerts)  
   - [Admin Notifications](#admin-notifications)  
   - [Chat Support](#chat-support)  

---

### **1. Overview**

#### **Asynchronous Features**
Asynchronous operations enable seamless interaction with the application without requiring a page reload. AJAX plays a crucial role in handling these tasks efficiently.

#### **Real-Time Features with Laravel Broadcasting**
Real-time updates keep users informed instantly. Laravel Broadcasting, powered by **Pusher** or **WebSocket**, is used to implement real-time functionality.

---

### **2. Asynchronous Features Implementation**

---

#### **Dynamic Product Search**

##### **Description**:
Allows users to search for products dynamically as they type in the search bar.

##### **Implementation**:

1. **Route**:
   ```php
   Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
   ```

2. **Controller**:
   ```php
   public function search(Request $request)
   {
       $query = $request->input('query');
       $products = Product::where('name', 'LIKE', "%{$query}%")->get();

       return response()->json($products);
   }
   ```

3. **Blade Template**:
   ```html
   <input type="text" id="search" placeholder="Search products...">
   <ul id="search-results"></ul>
   ```

4. **JavaScript (AJAX)**:
   ```javascript
   document.getElementById('search').addEventListener('input', function () {
       const query = this.value;
       fetch(`/products/search?query=${query}`)
           .then(response => response.json())
           .then(data => {
               const results = data.map(product => `<li>${product.name}</li>`).join('');
               document.getElementById('search-results').innerHTML = results;
           });
   });
   ```

---

#### **Live Inventory Updates**

##### **Description**:
Updates the inventory count in real-time as products are added or removed from the cart.

##### **Implementation**:

1. **Broadcast Event**:
   ```bash
   php artisan make:event InventoryUpdated
   ```

   **Event Class**:
   ```php
   public function broadcastOn()
   {
       return new Channel('inventory-updates');
   }
   ```

2. **Trigger Event**:
   In the `CartController`:
   ```php
   InventoryUpdated::dispatch($product);
   ```

3. **Frontend (JavaScript)**:
   ```javascript
   Echo.channel('inventory-updates').listen('InventoryUpdated', (event) => {
       document.getElementById(`inventory-${event.product.id}`).textContent = event.product.stock;
   });
   ```

---

#### **Quick Add to Cart**

##### **Description**:
Adds a product to the cart without a page reload.

##### **Implementation**:

1. **Route**:
   ```php
   Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
   ```

2. **Controller**:
   ```php
   public function addToCart(Request $request)
   {
       $cart = auth()->user()->cart ?? Cart::create(['user_id' => auth()->id()]);
       $cart->items()->create(['product_id' => $request->product_id, 'quantity' => 1]);

       return response()->json(['message' => 'Product added to cart']);
   }
   ```

3. **Blade Template**:
   ```html
   <button class="add-to-cart" data-id="{{ $product->id }}">Add to Cart</button>
   ```

4. **JavaScript (AJAX)**:
   ```javascript
   document.querySelectorAll('.add-to-cart').forEach(button => {
       button.addEventListener('click', function () {
           const productId = this.dataset.id;
           fetch('/cart/add', {
               method: 'POST',
               headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
               body: JSON.stringify({ product_id: productId }),
           }).then(response => response.json()).then(data => {
               alert(data.message);
           });
       });
   });
   ```

---

#### **Pagination and Filtering**

##### **Description**:
Loads paginated products and applies filters dynamically.

##### **Implementation**:

1. **Route**:
   ```php
   Route::get('/products', [ProductController::class, 'index'])->name('products.index');
   ```

2. **Controller**:
   ```php
   public function index(Request $request)
   {
       $products = Product::when($request->category, function ($query, $category) {
           return $query->where('category_id', $category);
       })->paginate(10);

       return response()->json($products);
   }
   ```

3. **JavaScript**:
   ```javascript
   function loadProducts(page = 1, category = null) {
       fetch(`/products?page=${page}&category=${category}`)
           .then(response => response.json())
           .then(data => {
               const products = data.data.map(product => `<div>${product.name}</div>`).join('');
               document.getElementById('product-list').innerHTML = products;
           });
   }

   document.getElementById('category-filter').addEventListener('change', function () {
       loadProducts(1, this.value);
   });
   ```

---

### **3. Real-Time Features Implementation**

#### **Real-Time Notifications for Order Status**

##### **Description**:
Notifies users in real-time when their order status is updated.

##### **Implementation**:

1. **Broadcast Event**:
   ```bash
   php artisan make:event OrderStatusUpdated
   ```

2. **Event Class**:
   ```php
   public function broadcastOn()
   {
       return new PrivateChannel('orders.' . $this->order->user_id);
   }
   ```

3. **Trigger Event**:
   ```php
   OrderStatusUpdated::dispatch($order);
   ```

4. **Frontend (JavaScript)**:
   ```javascript
   Echo.private(`orders.${userId}`).listen('OrderStatusUpdated', (event) => {
       alert(`Your order status is now: ${event.order.status}`);
   });
   ```

---

#### **Admin Notifications**

##### **Description**:
Alerts admin users in real-time when new orders are placed.

##### **Implementation**:

1. **Broadcast Event**:
   ```php
   public function broadcastOn()
   {
       return new Channel('admin-notifications');
   }
   ```

2. **Frontend (JavaScript)**:
   ```javascript
   Echo.channel('admin-notifications').listen('NewOrderPlaced', (event) => {
       alert(`New order placed by user: ${event.order.user.name}`);
   });
   ```

---
