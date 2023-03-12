<html>
    <script src="https://dev.shift4.com/checkout.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript">
    $(function () {
        Shift4Checkout.key = 'pk_test_BS2JbJxjzLpsVWZe1SKfyqKz';
        Shift4Checkout.success = function (result) {
        console.log("Success"); // This will print "Success" to the console if the transaction is successful
        console.log(result); // This will print the charge/subscription object to the console if the transaction is successful
        };
        Shift4Checkout.error = function (errorMessage) {
        console.log("An error occurred"); // This will print "An error occurred" to the console if the transaction resulted in a failure
        console.log(errorMessage); // This will print the error message to the console if the transaction results in an error
        };

        var signedRequestCharge = <?php
            require_once('vendor/autoload.php');
            use Shift4\Shift4Gateway;
            use Shift4\Request\CheckoutRequestCharge;
            use Shift4\Request\CheckoutRequest;
            use Shift4\Request\CheckoutRequestSubscription;
            use Shift4\Request\CheckoutRequestCustomCharge;

            $shift4 = new Shift4Gateway('sk_test_BS2Jb07UJk1FkdKq0mbIGvkz'); // Change to your own secret key

            $checkoutCharge = new CheckoutRequestCharge();
            $checkoutCharge->amount(1599)->currency('USD'); // Adjust this to alter the amount and currency
            $checkoutRequest = new CheckoutRequest();
            $checkoutRequest->charge($checkoutCharge);
            
            $signedCheckoutRequest = $shift4->signCheckoutRequest($checkoutRequest);
            echo json_encode($signedCheckoutRequest, JSON_HEX_TAG);

        ?>;

        var signedRequestSubscription = <?php

            $checkoutSubscription = new CheckoutRequestSubscription();
            $checkoutSubscription->planId('plan_kE2RIqZGTOMoKgcSPp2Ul9iJ'); // Change to your own plan ID
            $checkoutRequest = new CheckoutRequest();
            $checkoutRequest->subscription($checkoutSubscription);

            $signedCheckoutRequest = $shift4->signCheckoutRequest($checkoutRequest);
            echo json_encode($signedCheckoutRequest, JSON_HEX_TAG);

        ?>;

        var signedRequestCustomCharge = <?php

            $amtOpts = array(1000, 20000, 30000, 50000, 100000); // Change the amounts here to alter the amount options displayed
            $cstAmt = (object) [
                'min' => 10000,
                'max' => 500000,
            ]; // Change the amounts here to alter the minimum and maximum amount allowed

            $checkoutCustomCharge = new CheckoutRequestCustomCharge();
            //$checkoutCustomCharge->customAmount($cstAmt)->currency('USD'); // Uncomment this line and comment the line below to use custom amount instead of amout options
            $checkoutCustomCharge->amountOptions($amtOpts)->currency('EUR');
            $checkoutRequest = new CheckoutRequest();
            $checkoutRequest->customCharge($checkoutCustomCharge);

            $signedCheckoutRequest = $shift4->signCheckoutRequest($checkoutRequest);
            echo json_encode($signedCheckoutRequest, JSON_HEX_TAG);

        ?>;
    
        $('#payment-button1').click(function () {
            Shift4Checkout.open({
                checkoutRequest: signedRequestCharge, //'MTAwYmMwYTgxYzk0MWRjODcyMjgwM2M0MTBkMzkwOWU0NzE2ZDRmNjdiMTZhMDMxNmIwMzk5MDVjZDgyMjEyY3x7ImNoYXJnZSI6eyJhbW91bnQiOjQ5OSwiY3VycmVuY3kiOiJFVVIifX0=',
                name: 'Justin\'s Test app',
                description: 'Test charge'
            });
        });

        $('#payment-button2').click(function () {
            Shift4Checkout.open({
                checkoutRequest:signedRequestSubscription,
                name: 'Justin\'s Test app',
                description: 'Test subscription'
            });
        });

        $('#payment-button3').click(function () {
            Shift4Checkout.open({
                checkoutRequest:signedRequestCustomCharge,
                name: 'Justin\'s Test app',
                description: 'Test custom charge'
            });
        });

    });
    </script>

    <button id="payment-button1">Charge</button>
    <button id="payment-button2">Subscription</button>
    <button id="payment-button3">Custom Charge</button>
</html>