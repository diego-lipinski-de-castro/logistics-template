<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cadastro loja | {{ config('app.name', 'SpeedApp') }}</title>

    <link rel="icon" href="{{ url('oldfavicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
</head>

<body class="font-sans antialiased overflow-x-hidden bg-gray-50">

    <div id="app" class="min-h-full flex flex-col justify-center py-6 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ route('welcome') }}">
                <img class="mx-auto h-28 w-auto" src="{{ asset('images/logo.svg') }}" alt="SpeedApp"/>
            </a>
            
            <h2 class="text-center text-xl font-medium text-gray-800">Cadastre seu estabelecimento</h2>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-md sm:px-10">
                
                <form class="space-y-6" action="{{ route('userFormSubmit') }}" method="POST">
                    @csrf

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">Estado</label>
                        <div class="mt-1">
                            <select v-model="state" id="state" name="state_id" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm">
                                <option :value="null">Selecione</option>
                                <option v-for="(state, index) in states" :key="index"  :value="state.id">@{{ state.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Cidade</label>
                        <div class="mt-1">
                            <select v-model="city" :disabled="state == null" id="city" name="city_id" required class="disabled:opacity-50 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm">
                                <option :value="null">Selecione</option>
                                <option v-for="(city, index) in cities" :key="index"  :value="city.id">@{{ city.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" autocomplete="name" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                        <div class="mt-1">
                            <input id="phone" name="phone" type="text" autocomplete="phone"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500">Enviar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script defer>
        new Vue({
            el: '#app',
            data() {
                return {
                    states: @json($states),
                    state: null,
                    city: null,
                }
            },
            computed: {
                cities() {
                    return this.state ? this.states.find(s => s.id == this.state).cities : [];
                },
            },
            watch: {
                state() {
                    this.city = null;
                }
            }
        });
    </script>
</body>

</html>
