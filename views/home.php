<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.png">
    <title>Simple PHP MVC</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Product List</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>SKU</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody id="tableData">
            </tbody>
        </table>
        <button id="test">Show</button>
        <section>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </section>
    </div>
</body>
<script>
    $(document).ready(function() {

        refreshProductList();
        setInterval(refreshProductList, 500);


        function refreshProductList() {
            $.ajax({
                url: '/mvc%20architecture/test',
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    var data = "";
                    // console.log(response);
                    $.each(response, function(index, product) {
                        data += "<tr>";
                        data += "<td>" + product.id + "</td>";
                        data += "<td>" + product.title + "</td>";
                        data += "<td>" + product.description + "</td>";
                        data += "<td>" + parseFloat(product.price).toFixed(2) + "</td>";
                        data += "<td>" + product.sku + "</td>";
                        data += "<td>" + product.image + "</td>";
                        data += "</tr>";
                    });
                    $('#tableData').html(data);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        $("#test").click(function() {
            $.ajax({
                type: 'POST',
                url: '/mvc%20architecture/test',
                data: {
                    name: "Sandun"
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
</script>

</html>