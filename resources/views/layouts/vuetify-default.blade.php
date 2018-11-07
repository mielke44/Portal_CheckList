<!DOCTYPE html>
<html>

<head>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="{{asset('vuetify/vuetify.min.css')}}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield("css")
    <title>@yield('title')</title>
</head>

<body>
    <div id="app">
        <v-app>
            <template>
                @yield("vuetify-app-content")
            </template>
        </v-app>
    </div>
    @yield("content")
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('vuetify/vue.min.js')}}"></script>
    <script src="{{asset('vuetify/vuetify.min.js')}}"></script>
    @yield("js")
</body>

</html>
