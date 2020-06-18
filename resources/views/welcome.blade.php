<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CreditCard</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
        <div class="top-right links">
            @auth
            <a href="{{ url('/home') }}">Home</a>
            @else
            <a href="{{ route('login') }}">Login</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}">Register</a>
            @endif
            @endauth
        </div>
        @endif

        <div class="content">
            <div class="links">
                <form class="form-horizontal" id="credit-card-form">
                    <fieldset>
                        <!-- Form Name -->
                        <legend>CreditCard</legend>
                        @csrf
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="card_holder">Nome portador</label>
                            <div class="col-md-8">
                                <input id="card_holder" name="card_holder" type="text" placeholder="Nome portador"
                                    class="form-control input-md" required="">

                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="card_number">Número cartão</label>
                            <div class="col-md-8">
                                <input id="card_number" name="card_number" type="text" placeholder="Número cartão"
                                    class="form-control input-md">

                            </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="mes">Mês vencimento</label>
                            <div class="col-md-8">
                                <select id="mes" name="mes" class="form-control">
                                    <option value="1">Janeiro</option>
                                    <option value="2">Fevereiro</option>
                                    <option value="3">Março</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Maio</option>
                                    <option value="6">Junho</option>
                                    <option value="7">Julho</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="ano">Ano vencimento</label>
                            <div class="col-md-8">
                                <input id="ano" name="ano" type="number" placeholder="Ano vencimento"
                                    min="{{ date('Y') + 1 }}" value="{{ date('Y') + 1 }}" class="form-control input-md">

                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="cvv">CVV</label>
                            <div class="col-md-8">
                                <input id="cvv" name="cvv" type="text" placeholder="CVV" class="form-control input-md">
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="execJs">Exec JS</label>
                            <div class="col-md-8">
                                <input id="execJs" name="execJs" type="checkbox" class="form-control checkbox">
                            </div>
                        </div>
                        <input type="hidden" id="public-token" name="public-token" value="4985DCEFF0AFDFAD959D16EEF0D6C7512769A5CDB662CD2F28D12B67D23934B2" />
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="testar">Testar</button>
                            </div>
                        </div>

                    </fieldset>
                </form>
                <div class="row">
                    <label class="col-md-4 control-label">JS</label>
                    <div class="col-md-8" id="producao-js"></div>
                </div>
                <div class="row">
                    <label class="col-md-4 control-label">PHP</label>
                    <div class="col-md-8" id="producao-php"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/producao.min.js') }}"></script>
    {{-- <script src="{{ asset('js/sandbox.min.js') }}"></script> --}}
    <script>
        $(function() {
            $("#testar").click(function() {

                if ($("#execJs").is(":checked")) {
                    var checkout = new DirectCheckout($("#public-token").val(), false);
                    /* Em sandbox utilizar o construtor new DirectCheckout('PUBLIC_TOKEN', false); */
                    var cardData = {
                        cardNumber: $("#card_number").val(),
                        holderName: $("#card_holder").val(),
                        securityCode: $("#cvv").val(),
                        expirationMonth: $("#mes").val(),
                        expirationYear: $("#ano").val()
                    };
                    checkout.getCardHash(cardData, function(cardHash) {
                        $("#producao-js").html(cardHash).css("color", "black");
                    }, function(error) {
                        console.error(error);
                        $("#producao-js").html(error).css("color", "red");
                    });
                }
                fetch("{{ route('get.token') }}", {
                    method: 'POST',
                    body: serializeForm('credit-card-form')
                }).then(function(response) {
                    console.log(response);
                }).catch(function(error) {
                    console.error(error);
                });
            });
        });

        function serializeForm(id) {
            try {
                var form = document.getElementById(id);
                return new FormData(form);
            } catch(error) {
                console.error(error);
            }
        }
    </script>
</body>

</html>
