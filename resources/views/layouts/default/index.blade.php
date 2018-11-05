@extends('layouts.vuetify-default')

@section('vuetify-app-content')

<v-navigation-drawer v-model="drawer" fixed app temporary>
    @yield('l-menu')
</v-navigation-drawer>



<v-toolbar color="primary" dark fixed app>
    <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
    <v-toolbar-title>@yield('l-topbar-title')</v-toolbar-title>
</v-toolbar>

<v-content>
    @yield('l-content')
</v-content>


<v-footer color="primary">
    @yield('l-footer')
</v-footer>
@endsection
