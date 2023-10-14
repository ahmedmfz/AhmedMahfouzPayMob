<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title> PayMob View Testing </title>
</head>

<body>

    <div class="row">
        <div class="col-12 text-center">
            @if(session()->has('error'))
                <div class="alert alert-danger" role="alert">
                    {{  session('error') }} 
                </div>
            @endif
        </div>
    </div>

    <div class="container mt-5">
        <form action="{{ route('pay') }}" method="POST">
            @csrf

            <div class="row mt-5 justify-content-center">

                <div class="card" style="width: 18rem;text-align: center;margin: 5px;">
                    <div class="card-body">
                        <a href="#">
                            <h5 class="card-title">Paymob Card</h5>
                            <input type="radio" name="service" value="CARD">
                        </a>
                    </div>
                </div>

                {{-- <div class="card" style="width: 18rem;text-align: center;margin: 5px;">
                    <div class="card-body">
                        <a href="">
                            <h5 class="card-title">Paymob Aman</h5>
                            <input type="radio" name="service" value="AMAN">
                        </a>
                    </div>
                </div> --}}

                <div class="card" style="width: 18rem;text-align: center;margin: 5px;">
                    <div class="card-body">
                        <a href="">
                            <h5 class="card-title">Paymob Wallet</h5>
                            <input type="radio" name="service" value="MOBILE_WALLET">
                        </a>
                    </div>
                </div>

                <div class="text-center">
                    <button class="btn btn-warning"> Pay </button>
                </div>

            </div>

        </form>
    </div>

</body>
</html>
