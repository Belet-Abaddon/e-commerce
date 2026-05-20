<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-info table {
            width: 100%;
        }
        .order-info td {
            padding: 5px 0;
        }
        .order-info td:first-child {
            font-weight: bold;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-pending {
            background: #ffc107;
            color: #856404;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-couch" style="font-size: 40px; margin-bottom: 10px;"></i>
            <h1>Order Confirmation</h1>
            <p>Thank you for shopping with HomeNest!</p>
        </div>

        <div class="content">
            <h2>Hello {{ $order->user->name }},</h2>
            <p>Your order has been successfully placed! Here are your order details:</p>

            <div class="order-info">
                <table>
                    <tr>
                        <td>Order Number:</td>
                        <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Order Date:</td>
                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <td>Delivery Status:</td>
                        <td><span class="badge badge-pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>Delivery Type:</td>
                        <td>{{ ucfirst($order->delivery_type) }}</td>
                    </tr>
                    <tr>
                        <td>Delivery Address:</td>
                        <td>{{ $order->order_address }}</td>
                    </tr>
                </table>
            </div>

            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>
                            @if(isset($item['has_promotion']) && $item['has_promotion'])
                                <span style="text-decoration: line-through; color: #999;">${{ number_format($item['original_price'], 2) }}</span>
                                <strong style="color: #dc3545;">${{ number_format($item['promotion_price'], 2) }}</strong>
                            @else
                                ${{ number_format($item['price'], 2) }}
                            @endif
                        </td>
                        <td>
                            @php
                                $itemPrice = isset($item['final_price']) ? $item['final_price'] : (isset($item['promotion_price']) && $item['has_promotion'] ? $item['promotion_price'] : $item['price']);
                            @endphp
                            ${{ number_format($itemPrice * $item['qty'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                <strong>Total Amount: ${{ number_format($totalAmount, 2) }}</strong>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('user.orders.show', $order->id) }}" class="button">View Your Order</a>
            </div>

            <p style="margin-top: 30px;">We'll notify you once your order is shipped. If you have any questions, please contact our customer support.</p>
            <p>Thank you for choosing HomeNest!</p>
            <p>Best regards,<br><strong>HomeNest Furniture Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} HomeNest Furniture. All rights reserved.</p>
            <p>123 Furniture Street, Mandalay, Myanmar | +95 9 259 820 422 | info@homenest.com</p>
        </div>
    </div>
</body>
</html>