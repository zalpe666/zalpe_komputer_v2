<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.clientKey') }}"></script>
</head>
<body>

<h3>Loading Payment...</h3>

<script>
window.onload = function () {
    snap.pay('{{ $transaction->snap_token }}', {
        onSuccess: function(result){
            alert("Pembayaran sukses!");
            window.location.href = "/home/transaction";
        },
        onPending: function(result){
            alert("Menunggu pembayaran");
        },
        onError: function(result){
            alert("Pembayaran gagal");
        }
    });
};
</script>

</body>
</html>