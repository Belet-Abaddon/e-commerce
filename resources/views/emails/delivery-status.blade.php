<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery Status Update</title>
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
        .status-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-pending {
            background: #ffc107;
            color: #856404;
        }
        .status-in_progress {
            background: #0d6efd;
            color: white;
        }
        .status-delivered {
            background: #198754;
            color: white;
        }
        .order-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .order-info table {
            width: 100%;
        }
        .order-info td {
            padding: 8px 0;
        }
        .order-info td:first-child {
            font-weight: bold;
            width: 120px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .progress-bar-container {
            background: #e9ecef;
            border-radius: 10px;
            height: 10px;
            margin: 20px 0;
            overflow: hidden;
        }
        .progress-bar {
            background: #0d6efd;
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        .steps {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .step {
            text-align: center;
            flex: 1;
            font-size: 12px;
        }
        .step.active {
            color: #0d6efd;
            font-weight: bold;
        }
        .step.completed {
            color: #198754;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-truck" style="font-size: 40px; margin-bottom: 10px;"></i>
            <h1>Delivery Status Update</h1>
            <p>Your order #{{ str_pad($delivery->order_id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="content">
            <h2>Hello {{ $delivery->order->user->name }},</h2>
            
            @if($newStatus == 'in_progress')
                <p>Great news! Your order is now <strong>In Progress</strong> and is being prepared for delivery.</p>
                
                <div class="status-card">
                    <span class="status-badge status-in_progress">
                        <i class="fas fa-spinner fa-pulse"></i> IN PROGRESS
                    </span>
                    <p style="margin-top: 15px;">Your order has been confirmed and is now being processed by our team.</p>
                </div>

                <!-- Progress Steps -->
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: 50%;"></div>
                </div>
                <div class="steps">
                    <div class="step completed">✓ Order Placed</div>
                    <div class="step active">🔄 In Progress</div>
                    <div class="step">✅ Delivered</div>
                </div>

                <p>What happens next?</p>
                <ul>
                    <li>Our team is preparing your items for shipment</li>
                    <li>You'll receive another notification when your order is shipped</li>
                    <li>Track your order anytime from your dashboard</li>
                </ul>

            @elseif($newStatus == 'delivered')
                <p>Your order has been <strong>Delivered</strong>! We hope you love your new furniture.</p>
                
                <div class="status-card">
                    <span class="status-badge status-delivered">
                        <i class="fas fa-check-circle"></i> DELIVERED
                    </span>
                    <p style="margin-top: 15px;">Your order has been successfully delivered to your address.</p>
                </div>

                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: 100%; background: #198754;"></div>
                </div>
                <div class="steps">
                    <div class="step completed">✓ Order Placed</div>
                    <div class="step completed">✓ In Progress</div>
                    <div class="step completed">✓ Shipped</div>
                    <div class="step completed">✓ Delivered</div>
                </div>

                <p>We'd love to hear your feedback!</p>
                <ul>
                    <li>Share your experience by leaving a review</li>
                    <li>Upload photos of your new furniture</li>
                    <li>Rate your delivery experience</li>
                </ul>
            @endif

            <div class="order-info">
                <table>
                    <tr>
                        <td>Order Number:</td>
                        <td><strong>#{{ str_pad($delivery->order_id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Order Date:</td>
                        <td>{{ \Carbon\Carbon::parse($delivery->order->order_date)->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td>Delivery Status:</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $newStatus)) }}</td>
                    </tr>
                    <tr>
                        <td>Delivery Address:</td>
                        <td>{{ $delivery->order->order_address }}</td>
                    </tr>
                </table>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('user.orders.show', $delivery->order_id) }}" class="button">Track Your Order</a>
            </div>

            <p style="margin-top: 30px;">If you have any questions about your delivery, please contact our customer support.</p>
            <p>Thank you for shopping with HomeNest!</p>
            <p>Best regards,<br><strong>HomeNest Furniture Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} HomeNest Furniture. All rights reserved.</p>
            <p>123 Furniture Street, Mandalay, Myanmar | +95 9 259 820 422 | info@homenest.com</p>
        </div>
    </div>
</body>
</html>