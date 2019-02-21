@extends('layouts.vuetify-default')

@section('css')
@yield("l-css")
<link href="{{asset('css/extras.css')}}" rel="stylesheet">
@endsection

@section('vuetify-app-content')



<!--<v-navigation-drawer :mini-variant.sync="mini" fixend app v-model="drawer" hide-overlay stateless class="red accent-3" dark>-->
<v-navigation-drawer fixend app v-model="drawer" hide-overlay stateless class="red accent-3" dark>
    <!--<div @mouseover="mini=false" @mouseout="mini=true" style='height:100%'>-->
    <div style='height:100%'>
        <v-toolbar flat class="transparent">
            <v-list class="pa-0">
                <v-list-tile avatar>
                    <v-list-tile-avatar>
                        <v-avatar color="grey darken-4" size='40'>
                            <span class="white--text headline">@{{letter}}</span>
                        </v-avatar>
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>@{{user.name.split(" ")[0]}}</v-list-tile-title>
                    </v-list-tile-content>


                </v-list-tile>
            </v-list>

        </v-toolbar>

        <v-list class="pt-0" dense>
            <v-divider></v-divider>

            <v-list-tile v-if="user.is_admin>0 || item.visible_external" v-for="(item,i) in menu" :key="item.title"
                @click="item.link">
                <v-list-tile-action>
                    <v-icon :color="(i==screen) ? 'black': 'white'">@{{ item.icon }}</v-icon>
                </v-list-tile-action>

                <v-list-tile-content>
                    <v-list-tile-title :class="(i==screen) ? 'black--text': 'white--text'">@{{ item.text }}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>
        <v-footer height="auto" absolute color='primary'>
            <v-layout row wrap align-center>
                <!--<v-flex xs12 v-if='mini'>
                    <v-img src="{{asset('images/t-logo3.png')}}" max-width='50' style='display: block;margin: 0 auto;'></v-img>
                </v-flex>-->
                <!--<v-flex xs12 class='text-xs-center font-weight-bold caption' v-if='!mini'>T-Systems Mobile Apps
                </v-flex>-->
                <v-flex xs12 class='text-xs-center font-weight-bold caption'>T-Systems Mobile Apps</v-flex>
            </v-layout>
        </v-footer>
    </div>
</v-navigation-drawer>



<v-toolbar color="white" fixed app>
    <v-toolbar-side-icon @click.stop="drawer=!drawer;mini=false"></v-toolbar-side-icon>
    <v-toolbar-title>
        @yield('l-title')
    </v-toolbar-title>
    <template v-if="search.model">
        <v-text-field ref='search_input' v-model='search.value' append-icon="search" label="Procurar" full-width solo
            slot='extension' @click:append='searching' @input='searching'></v-text-field>
    </template>
    <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down">

        <v-btn icon @click="search.model=!search.model;">
            <v-icon>search</v-icon>
        </v-btn>

        <v-menu :close-on-content-click="false" offset-y left transition="slide-y-transition">
            <v-btn icon slot='activator'>
                <v-badge right color='primary'>
                    <span slot="badge" v-if='notifications.length>0'>@{{notifications.length}}</span>
                    <v-icon>notifications</v-icon>
                </v-badge>
            </v-btn>
            <v-list class="ma-0 pa-0">
                <template v-if="notifications.length>0" v-for='n in notifyLimit'>
                    <v-list-tile @click="get_not_source(n.id)">
                        <v-list-tile-avatar>
                            <v-icon color="red" v-if='n.type==-1' class="twotone">warning</v-icon>
                            <v-icon color="primary" v-if='n.type==0'>check_box</v-icon>
                            <v-icon color="primary" v-if='n.type==1'>add_comment</v-icon>
                            <v-icon color="primary" v-if='n.type==2'>assignment_ind</v-icon>
                            <v-icon color="primary" v-if='n.type==3'>playlist_add</v-icon>
                            <v-icon color="primary" v-if='n.type==4'>playlist_add_check</v-icon>
                            <v-icon color="yellow darken-2" v-if='n.type==5'>warning</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content class='body-1'>
                            <v-list-tile-title>@{{n.name}}</v-list-tile-title>
                            <v-list-tile-sub-title xs3>@{{n.text}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-list-tile-action-text>@{{n.data[0]}}</v-list-tile-action-text>
                            <v-list-tile-action-text>@{{n.data[1]}}</v-list-tile-action-text>
                        </v-list-tile-action>
                    </v-list-tile>
                    <v-divider></v-divider>
                </template>
                <template v-if="notifications.length==0">
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>nenhuma notificação</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
                <v-list-tile class="pa-0">
                    <v-flex xs6>
                        <v-btn class="pa-0 ma-0" color="white" v-if="tam==3" depressed block @click='tam=notifications.length'><v-icon>expand_more</v-icon></v-btn>
                        <v-btn class="pa-0 ma-0" color="white" v-if="tam!=3" depressed block @click='tam=3'><v-icon>expand_less</v-icon></v-btn>
                    </v-flex>
                    <v-flex xs6>
                        <v-btn class="pa-0 ma-0" color="white" depressed block @click="clearnot"><v-icon>delete</v-icon></v-btn>
                    </v-flex>
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
    <page inline-template ref='page'>
        <div style="height:100%">@yield('l-content')</div>
    </page>
</v-content>

<!-- COPONENTES -->
<v-snackbar v-model="snackbar_notify.model" multi-line timeout="3000" bottom right :color='snackbar_notify.color'>
    @{{snackbar_notify.text}}
    <v-btn flat @click.native="snackbar_notify.model = false">
        <v-icon>clear</v-icon>
    </v-btn>
</v-snackbar>

<v-dialog v-model="dialog_confirm.model" persistent max-width="500px" transition="dialog-transition">
    <v-card :color='dialog_confirm.color' dark>
        <v-card-text class='text-xs-center display-1'>
            @{{dialog_confirm.title}}
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text class='text-xs-center'>
            @{{dialog_confirm.text}}
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text class='text-xs-center'>
            <v-btn color="white" class='red--text' @click='dialog_confirm.model=false' fab small>
                <v-icon large>close</v-icon>
            </v-btn>
            <v-btn color="white" class='green--text' @click='dialog_confirm.action();dialog_confirm.model=false' fab
                small>
                <v-icon large>check</v-icon>
            </v-btn>
            </v-card-tex>
    </v-card>
</v-dialog>

@endsection

@section('js')
@yield("l-js")
<script src='{{asset("vuetify/theme.js")}}'></script>
<script>
    app = new Vue({
        el: '#app',
        created() {
            this.$vuetify.theme = $THEME_VUETIFY;
        },
        data() {
            return {
                user: {
                    is_admin: 0,
                    name: ' '
                },
                tam: 3,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                screen: "0",
                app_name: "Portal Checklist",
                notifications: [],
                drawer: true,
                mini: false,
                search: {
                    value: "",
                    model: false
                },
                menu: [
                        {
                            id: 'dash',
                            icon: "dashboard",
                            text: "Dashboard",
                            visible_external: false,
                            link: function () {
                                window.location = '{{route("dashboard")}}'
                            }
                        },
                        {
                            id: 'employee',
                            icon: "face",
                            text: "Empregados",
                            visible_external: false,
                            link: function () {
                                window.location = '{{route("employee")}}'
                            }
                        },
                        {
                            id: 'yourchecklist',
                            icon: "event_note",
                            text: "Suas tarefas",
                            visible_external: true,
                            link: function () {
                                window.location = '{{route("emp.yourchecklist.view")}}'
                            }
                        },
                        {
                            id: 'profile',
                            icon: "portrait",
                            text: "Perfis",
                            visible_external: false,
                            link: function () {
                                window.location = '{{route("profile")}}'
                            }
                        },
                        {
                            id: 'checklist',
                            icon: "list_alt",
                            text: "Lista de tarefas",
                            visible_external: false,
                            link: function () {
                                window.location = '{{route("checklist")}}'
                            }
                        },
                        {
                            id: 'task',
                            icon: "list",
                            text: "Tarefas",
                            visible_external: false,
                            link: function () {
                                window.location = '{{route("task")}}'
                            }
                        },
                        {
                        id: 'admin',
                        icon: "supervisor_account",
                        text: "Gestores e Responsáveis",
                        visible_external: false,
                        link: function () {
                            window.location = '{{route("admin")}}'
                        }
                    }
                ],
                more: [{
                        icon: "account_box",
                        text: "Seu Perfil",
                        link: function () {}
                    },
                    {
                        icon: "exit_to_app",
                        text: "Sair",
                        link: function () {
                            location.href = '{{route("logout")}}'
                        }
                    },
                ],
                snackbar_notify: {
                    text: "",
                    model: false,
                    color: "",
                },
                dialog_confirm: {
                    model: false,
                    color: 'white',
                    title: '',
                    text: '',
                    action: () => {},
                }
            }
        },
        computed: {
            letter: function () {
                return this.user.name[0];
            },
            notifyLimit: function () {
                var array = []
                for (i = 0; i < this.tam; i++) {
                    if (this.notifications.length >= i + 1)
                        array.push(this.notifications[i])
                    else break;
                }
                return array;
            }
        },
        methods: {
            notify: function (text, color) {
                this.snackbar_notify.text = text;
                this.snackbar_notify.model = true;
                if (this.snackbar_notify.color == null) this.snackbar_notify.color = "black";
                this.snackbar_notify.color = color;
            },
            clearnot: function(){
                $.ajax({
                    url: "{{route('clrnot')}}",
                    method: 'POST',
                    datatype: 'json',
                    headers : app.headers,
                }).done(response => {
                    if(response['error'])app.notify('Ocorreu um erro! Tente Novamente!','error');
                    app.notify('notificações excluidas!','success');
                    this.list_notifications();
                })
            },
            confirm: function (title, text, color, action) {
                this.dialog_confirm.model = true;
                this.dialog_confirm.title = title;
                this.dialog_confirm.text = text;
                this.dialog_confirm.color = color;
                this.dialog_confirm.action = action;
            },
            getUser: function (callback) {
                $.ajax({
                    url: "{{route('getuser')}}",
                    method: 'GET',
                }).done(response => {
                    this.user = response;
                    callback();
                });
            },
            searching: function () {
                this.$refs.page.searching(this.search.value);
            },
            list_notifications: function () {
                $.ajax({
                    url: "{{route('getnoti')}}",
                    method: 'GET',
                    dataType: "json",
                }).done(response => {
                    this.notifications = response;
                });
            },
            get_not_source: function (id) {
                $.ajax({
                    url: "{{route('updnot')}}",
                    method: 'POST',
                    dataType: "json",
                    headers: app.headers,
                    data: {
                        id: id
                    },
                }).done(response => {
                    this.list_notifications();
                    window.location = '{{route("emp.yourchecklist.view")}}';
                });
            },
            update: function () {
                $.ajax({
                    url: "{{route('getflagnoti')}}",
                    method: 'GET',
                    dataType: "json",
                }).done(response => {
                    if (JSON.stringify(response) == "true") {
                        this.list_notifications()
                    };
                });
            },
            setMenu: function (id) {
                for (i = 0; i < this.menu.length; i++) {
                    if (this.menu[i].id == id) {
                        this.screen = i;
                    }
                }
            },
            search_text: function (search, text) {
                if (text.toLowerCase().indexOf(search.toLowerCase()) > -1 || search == '') {
                    return true;
                } else return false;
            }
        },
        mounted() {
            this.list_notifications();
            this.more[0].link = () => {
                location.href = "{{route('admin.profile')}}";
            };
            this.getUser(() => {
                setTimeout(() => {
                    if (this.$refs.page.hasOwnProperty("mounted")) this.$refs.page.mounted()
                }, 50);
            });
            setInterval(() => this.update(), 5000);

        }
    });
</script>
@endsection
