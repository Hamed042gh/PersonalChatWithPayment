<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="bg-white shadow-md rounded-lg p-6 max-w-lg mx-auto mt-10 mb-10">
    <div class="text-center mb-4">
        <span class="text-gray-500 text-sm" id="orderno">Order #{{ $orderNumber }}</span>
    </div>
    <div class="text-center text-2xl font-semibold mb-6 text-gray-800">Thank you for your order, {{ $user->name }}!</div>
    
    <div class="mb-6">
        <p class="text-lg font-bold text-gray-700 text-center">Payment Summary</p>
        <p class="text-gray-700 mb-6">With an annual payment of $2, you can enjoy unlimited chatting services.</p>
    </div>

    <div class="space-y-4">
        <div class="flex justify-between text-gray-700">
            <span><b>Unlimited chat for one year</b></span>
        </div>
    </div>

    <hr class="my-4 border-gray-300">

    <div class="total mb-6">
        <div class="flex justify-between text-lg font-semibold text-gray-800">
            <span>Total:</span>
            <span>$2</span>
        </div>
    </div>

    <div class="text-center">
        <a href="" class="bg-blue-500 text-white font-semibold py-3 px-6 rounded-full shadow-md hover:bg-blue-600 transition duration-300">
            Purchase
        </a>
    </div>
</div>
