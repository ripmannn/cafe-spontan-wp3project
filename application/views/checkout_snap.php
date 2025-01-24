<html>
<title>Checkout</title>
<head>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-ZNNndb8J6_Q4Ql4S"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
<body>
    <form id="payment-form" method="post" action="<?=site_url()?>snap/finish">
        <input type="hidden" name="result_type" id="result-type" value="">
        <input type="hidden" name="result_data" id="result-data" value="">
    </form>
    
    <button id="pay-button">Pay!</button>
    <script type="text/javascript">
        $('#pay-button').click(function (event) {
         
        
            $.ajax({
                url: '<?=site_url()?>/snap/token',
                cache: false,
                success: function(data) {
                    console.log('token = ' + data);
                    var resultType = document.getElementById('result-type');
                    var resultData = document.getElementById('result-data');
        
                    function changeResult(type, data) {
                        $("#result-type").val(type);
                        $("#result-data").val(JSON.stringify(data));
                    }
        
                    snap.pay(data, {
                        onSuccess: function(result) {
                            changeResult('success', result);
                            console.log(result.status_message);
                            $("#payment-form").submit();
                        },
                        onPending: function(result) {
                            changeResult('pending', result);
                            console.log(result.status_message);
                            $("#payment-form").submit();
                        },
                        onError: function(result) {
                            changeResult('error', result);
                            console.log(result.status_message);
                            $("#payment-form").submit();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
