@extends('layouts.vuetify-default')

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

            <v-list-tile v-for="item in menu" :key="item.title" @click="item.link">
                <v-list-tile-action>
                    <v-icon>@{{ item.icon }}</v-icon>
                </v-list-tile-action>

                <v-list-tile-content>
                    <v-list-tile-title>@{{ item.text }}</v-list-tile-title>
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
    <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down">

        <v-btn icon>
            <v-icon>search</v-icon>
        </v-btn>
        <v-btn icon>
            <v-badge right color='primary'>
                <span slot="badge" v-if='notifications>0'>@{{notifications}}</span>
                <v-icon>notifications</v-icon>
            </v-badge>
        </v-btn>

        <v-menu offset-y left>
            <v-btn icon slot="activator">
                <v-icon>more_vert</v-icon>
            </v-btn>
            <v-list>
                <v-list-tile @click="">
                    <v-list-tile-title>
                        <v-icon class="mr-2">delete</v-icon>Sair
                    </v-list-tile-title>

                </v-list-tile>
                <v-list-tile @click="">
                        <v-list-tile-title>
                            <v-icon class="mr-2">more</v-icon>sdgsd
                        </v-list-tile-title>

                    </v-list-tile>
            </v-list>
        </v-menu>
    </v-toolbar-items>
</v-toolbar>

<v-content>
    <page inline-template>
        <div>@yield('l-content')</div>
    </page>
</v-content>



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
                app_name: "Portal Checklist",
                notifications: "2",
                name: "Christiano",
                drawer: true,
                mini: true,
                data_ajax_get: "",
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
                        text: "Tarefas",
                        link: function () {
                            window.location = '/task/'
                        }
                    }
                ]
            }
        },
        computed: {
            letter: function () {
                return this.name[0];
            }
        },
    });
</script>
@endsection
