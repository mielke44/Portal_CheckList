@extends('layouts.vuetify-default')

@section('css')
@yield("l-css")
<link href="{{asset('css/extras.css')}}" rel="stylesheet">
@endsection

@section('vuetify-app-content')



<v-navigation-drawer :mini-variant.sync="mini" fixend app v-model="drawer" hide-overlay stateless class="red accent-3"
    dark>
    <div @mouseover="mini=false" @mouseout="mini=true" style='height:100%'>
        <v-toolbar flat class="transparent">
            <v-list class="pa-0">
                <v-list-tile avatar>
                    <v-list-tile-avatar>
                        <v-avatar color="grey darken-4" size='40'>
                            <span class="white--text headline">@{{letter}}</span>
                        </v-avatar>
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>@{{name}}</v-list-tile-title>
                    </v-list-tile-content>


                </v-list-tile>
            </v-list>
        </v-toolbar>

        <v-list class="pt-0" dense>
            <v-divider></v-divider>

            <v-list-tile v-for="(item,i) in menu" :key="item.title" @click="item.link">
                <v-list-tile-action>
                    <v-icon :color="(i==screen) ? 'black': 'white'">@{{ item.icon }}</v-icon>
                </v-list-tile-action>

                <v-list-tile-content>
                    <v-list-tile-title :class="(i==screen) ? 'black--text': 'white--text'">@{{ item.text }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>
    </div>
</v-navigation-drawer>



<v-toolbar color="white" fixed app>
    <v-toolbar-side-icon @click.stop="drawer=!drawer;mini=false"></v-toolbar-side-icon>

    <v-toolbar-title>
        @yield('l-title')
    </v-toolbar-title>
    <template v-if="search.model">
        <v-text-field v-model='search.value' append-icon="search" label="Procurar" full-width solo slot='extension' @click:append='searching'></v-text-field>
    </template>
    <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down">

        <v-btn icon @click="search.model=!search.model">
            <v-icon>search</v-icon>
        </v-btn>

        <v-menu offset-y left>
            <v-btn icon slot='activator'>
                <v-badge right color='primary'>
                    <span slot="badge" v-if='notifications.length>0'>@{{notifications.length}}</span>
                    <v-icon>notifications</v-icon>
                </v-badge>
            </v-btn>
            <v-list>
                <v-list-tile @click="" v-for='n in notifications'>
                    <v-list-tile-avatar>
                        <v-icon>@{{n.icon}}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content class='body-1'>
                        <v-list-tile-title>@{{n.title}}</v-list-tile-title>
                        <v-list-tile-sub-title>@{{n.text}}</v-list-tile-sub-title>

                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-menu>

        <v-menu offset-y left>
            <v-btn icon slot="activator">
                <v-icon>more_vert</v-icon>
            </v-btn>
            <v-list>
                <v-list-tile @click="m.link" v-for='m in more'>
                    <v-list-tile-avatar>
                        <v-icon>@{{m.icon}}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content class='body-1'>
                        @{{m.text}}
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-menu>
    </v-toolbar-items>
</v-toolbar>

<v-content>
    <page inline-template>
        <div style="height:100%">@yield('l-content')</div>
    <page inline-template screen="screen">
        <div>@yield('l-content')</div>
    </page>
</v-content>

<!-- COPONENTES -->
<v-snackbar v-model="snackbar_notify.model" multi-line timeout="3000" bottom right :color='snackbar_notify.color'>
    @{{snackbar_notify.text}}
    <v-btn flat @click.native="value = false">
        <v-icon>clear</v-icon>
    </v-btn>
</v-snackbar>

@endsection

@section('js')
@yield("l-js")
<script src='/vuetify/theme.js'></script>
<script>
    app = new Vue({
        el: '#app',

        created() {
            this.$vuetify.theme = $THEME_VUETIFY;
        },
        data() {
            return {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                screen: "0",
                app_name: "Portal Checklist",
                notifications: [{
                    icon: "list_alt",
                    title: "Christiano Oishi",
                    text: "Atualizou a tarefa"
                }, {
                    icon: "list_alt",
                    title: "Christiano Oishi",
                    text: "Removeu a tarefa"
                }, ],
                name: " ",
                drawer: true,
                mini: true,
                search:{
                    value: "",
                    model:false
                },
                menu: [{
                        icon: "dashboard",
                        text: "Dashboard",
                        link: function () {
                            window.location = '/'
                        }
                    },
                    {
                        icon: "face",
                        text: "Empregados",
                        link: function () {
                            window.location = '/employee/'
                        }
                    },
                    {
                        icon: "portrait",
                        text: "Perfis",
                        link: function () {
                            window.location = '/profile/'
                        }
                    },
                    {
                        icon: "list_alt",
                        text: "Lista de tarefas",
                        link: function () {
                            window.location = '/checklist/'
                        }
                    },
                    {
                        icon: "list",
                        text: "Tarefas",
                        link: function () {
                            window.location = '/task/'
                        }
                    },
                    {
                        icon: "supervisor_account",
                        text: "Gestores",
                        link: function () {
                            window.location = '/Admin'
                        }
                    }
                ],
                more: [
                    {
                        icon: "account_box",
                        text: "Seu Perfil",
                        link: function(){
                            
                        }
                    },
                    {
                        icon: "settings",
                        text: "Configurações",
                        link: function () {}
                    },
                    {
                        icon: "exit_to_app",
                        text: "Sair",
                        link: function () {
                            location.href = '/logout'
                        }
                    },
                ],
                snackbar_notify: {
                    text: "",
                    model: false,
                    color: "",
                }
            }
        },
        computed: {
            letter: function () {
                return this.name[0];
            },
        },
        methods: {
            notify: function (text, color) {
                this.snackbar_notify.text = text;
                this.snackbar_notify.model = true;
                if (this.snackbar_notify.color == null) this.snackbar_notify.color = "black";
                this.snackbar_notify.color = color;
            },
            getName: function () {
                $.ajax({
                    url: "{{route('getname')}}",
                    method: 'GET',
                }).done(response => {
                    this.name = response;
                });
            },
            searching: function(){
                alert(this.search.value);
            }
        },
        mounted() {
            this.more[0].link = ()=>{
                location.href="{{route('admin.profile')}}";
            };
            this.getName();
        }
    });
</script>
@endsection
