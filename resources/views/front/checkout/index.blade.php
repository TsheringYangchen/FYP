@extends('front.layouts.master')

@section('style')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')

        <h2 class="mt-5"><i class="fa  fa-credit-card-alt"></i> Checkout</h2>
        <hr>

        <div class="row">

            <div class="col-md-7">

                @if (session()->has('msg'))
                    <div class="alert alert-success">
                        {{ session()->get('msg') }}
                    </div>
                @endif

                <h4>Confirm Order</h4>

                <form method="post" id="payment-form" action="{{ route('checkout') }}">

                    @csrf

             <button type="submit" class="btn btn-outline-info col-md-12">Complete Order</button>
                </form>

            </div>

            <div class="col-md-5">

                <h4>Your Order</h4>

                <table class="table your-order-table">
                    <tr>
                        <th>Image</th>
                        <th>Details</th>
                        <th>Qty</th>
                    </tr>

                    @foreach (Cart::instance('default')->content() as $item )
                    <tr>
                        <td><img src="{{ url('/uploads') . '/'. $item->model->image }}" alt="" style="width: 4em"></td>
                        <td>
                            <strong>{{ $item->model->name }}</strong><br>
                            <span class="text-dark">Nu.{{ Cart::subtotal() }}</span>
                        </td>
                        <td>
                            <span class="badge badge-light">{{ $item->qty }}</span>
                        </td>
                    </tr>
                    @endforeach
                </table>

                <hr>
                <table class="table your-order-table table-bordered">
                    <tr>
                        <th colspan="2">Price Details</th>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <th>Nu.{{ Cart::subtotal()}}</th>
                    </tr>

                </table>

            </div>
        </div>

    <div class="mt-5"><hr></div>
 
@endsection

@section('script')
    <script>
        // Create a Stripe client.
        var stripe = Stripe('pk_test_4paokl8kcBC4qZqwl6yYFty3');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#3325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {
            style: style,
            hidePostalCode: true
        });

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            var options = {
                name: document.getElementById("name_on_card").value,
                address_line_1: document.getElementById("address").value,
                address_city: document.getElementById("city").value,
                address_state: document.getElementById("province").value,
                address_zip: document.getElementById("postal").value
            };

            stripe.createToken(card, options).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>
@endsection