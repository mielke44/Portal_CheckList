@extends('layouts.vuetify-default')

@section('vuetify-app-content')

<v-navigation-drawer v-model="drawer" fixed app temporary>
        <v-list>
                <v-subheader>@{{app_name}}</v-subheader>
                <v-divider></v-divider>
                <template v-for='item in menu'>
                    <v-list-tile @click='item.link'>
                        <v-list-tile-action>
                            <v-icon>@{{item.icon}}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>@{{item.text}}</v-list-tile-content>
                    </v-list-tile>
                </template>

            </v-list>

</v-navigation-drawer>



<v-toolbar color="primary" dark fixed app>
    <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
    <v-toolbar-title>@{{title}}</v-toolbar-title>
    <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down">

        <v-btn flat>

            <v-badge right color='black'>
                <span slot="badge" v-if='notifications>0'>@{{notifications}}</span>
                <v-icon>notifications</v-icon>
            </v-badge>
        </v-btn>

        <v-btn flat>

            <v-avatar color="grey darken-4" size='40'>
                <span class="white--text headline">@{{letter}}</span>
            </v-avatar>
            <span class='ml-2'>@{{name}}</span>

        </v-btn>

    </v-toolbar-items>
</v-toolbar>

<v-content>
        <page inline-template><div>@yield('l-content')</div></page>
</v-content>

<v-footer color="primary">
    <v-card class="flex" flat tile>

        <v-card-actions class="secondary justify-center">
            <div><span class="white--text">&copy;2018 — <strong>T-Systems Brasil</strong></span></div>

        </v-card-actions>
    </v-card>
</v-footer>


@endsection

@section('js')
@yield("l-js")
<script src='vuetify/theme.js'></script>
<script>
    app = new Vue({
        el: '#app',

        created() {
            this.$vuetify.theme = $THEME_VUETIFY;
        },
        data() {
            return {
                app_name: "Portal Checklist",
                title: "Dashboard",
                notifications: "2",
                name: "Christiano",
                drawer: null,
                data_ajax_get: "",
                menu: [{
                        icon: "dashboard",
                        text: "Dashboard",
                        link: function () {
                            window.location = '##'
                        }
                    },
                    {
                        icon: "face",
                        text: "Usuários",
                        link: function () {
                            window.location = '##'
                        }
                    },
                    {
                        icon: "portrait",
                        text: "Perfis",
                        link: function () {
                            window.location = '##'
                        }
                    },
                    {
                        icon: "list_alt",
                        text: "Tarefas",
                        link: function () {
                            window.location = '##'
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
