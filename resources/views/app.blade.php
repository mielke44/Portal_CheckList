@extends('layouts.default.index')

@section('title','My App Vuetify')

@section('l-topbar-title',"Integration Vue.js and Vuetify - Laravel Project Example")

@section('l-content')

<!--
###########
Frameworks
###########
-->


<v-container>
    <section>
        <h1 class="display-1 primary--text">
            <div>
                <div>
                    <p>Frameworks</p>
                </div>
            </div>
        </h1>
        <v-card>
            <v-layout row wrap>
                <template v-for='frame in frameworks'>
                    <v-flex xs12 sm6 md4>
                        <v-container fill-height>
                            <v-card :color="frame.color" :class="frame.colortext" height="100%">
                                <v-layout justify-space-between column fill-height>
                                    <v-flex>
                                        <v-img :src="frame.img" aspect-ratio="2.75"></v-img>
                                        <v-card-title primary-title>
                                            <div>
                                                <h3 class="headline mb-0" v-html='frame.title'></h3>
                                                <div v-html='frame.desc'></div>
                                            </div>
                                        </v-card-title>
                                    </v-flex>
                                    <v-flex style='height:100px'>
                                        <v-layout align-end row fill-height>
                                            <v-flex>
                                                <v-divider></v-divider>
                                                <v-card-actions>
                                                    <v-btn color="error" @click='linkopen(frame.doc)'>Documentation</v-btn>
                                                </v-card-actions>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-card>
                        </v-container>
                    </v-flex>
                </template>
            </v-layout>
        </v-card>

    </section>

</v-container>

<!--
###########
Tips
###########
-->
<v-divider></v-divider>
<v-container>

    <section>
        <h1 class="display-1 primary--text">
            <div>
                <div>
                    <p>Tips</p>
                </div>
            </div>
        </h1>

        <v-card>
            <v-container>
                <template v-for='tip in tips'>
                    <div class='mt-1'>
                        <v-card color='primary' class='white--text'>
                            <v-container>
                                <v-layout row>
                                    <v-flex xs6 v-html='tip.desc'></v-flex>
                                    <v-flex xs6 v-html='tip.using'></v-flex>
                                </v-layout>
                            </v-container>
                        </v-card>
                    </div>
                </template>

            </v-container>
        </v-card>
    </section>



</v-container>


<!--
###########
Example
###########
-->
<v-divider></v-divider>
<v-container>

    <div><span class='display-2 grey--text  text--darken-3'>Examples - Using Vuetify Components</span></div>
</v-container>


@endsection

<!--
###########
Menu
###########
-->


@section('l-menu')
<v-list>
    <v-subheader>Menu Example</v-subheader>
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
@endsection


<!--
###########
Footer
###########
-->

@section('l-footer')
<v-card class="flex" flat tile>

    <v-card-actions class="grey darken-3 justify-center">
        <div><span class="white--text">&copy;2018 — <strong>Example project by Christiano Oishi de Carvalho</strong></span></div>

    </v-card-actions>
</v-card>
@endsection

<!--
###########
Vue
###########
-->

@section('js')
<script>
    new Vue({
        el: '#app',

        created() {
            this.$vuetify.theme = {
                primary: '#424242',
                secondary: '#424242',
                accent: '#82B1FF',
                error: '#ff4444',
                info: '#33b5e5',
                success: '#00C851',
                warning: '#ffbb33'
            };
        },
        data() {
            return {
                drawer: null,
                data_ajax_get: "",
                tips: [{
                        desc: "Vue variables on Blade template",
                        using: "Use @ before &#123;&#123; &#125;&#125;"
                    },
                    {
                        desc: "Vue variables data getting by get request",
                        using: "oi"
                    }
                ],
                frameworks: [{
                        color: 'white',
                        colortext: 'black--text',
                        img: "https://cdn.freebiesupply.com/logos/large/2x/laravel-1-logo-png-transparent.png",
                        title: 'Laravel',
                        desc: "Laravel is free, open-source and one of the more popular PHP web framework based on the model–view–controller (MVC) architectural pattern. It is created by Taylor Otwell, intended to reduce the cost of initial development and improve quality of your code by defining industry standard design practices. Using Laravel, you can save hours of development time and reduce thousands of lines of code compared raw PHP.",
                        doc: "https://laravel.com/docs/5.7"
                    },
                    {
                        color: 'teal accent-4',
                        colortext: 'white--text',
                        img: "https://dwglogo.com/wp-content/uploads/2017/09/Vue-logo-001.svg",
                        title: 'Vue.js',
                        desc: "Vue (pronounced /vjuː/, like view) is a progressive framework for building user interfaces. Unlike other monolithic frameworks, Vue is designed from the ground up to be incrementally adoptable. The core library is focused on the view layer only, and is easy to pick up and integrate with other libraries or existing projects. On the other hand, Vue is also perfectly capable of powering sophisticated Single-Page Applications when used in combination with modern tooling and supporting libraries.",
                        doc: "https://vuejs.org/v2/guide/"
                    },
                    {
                        color: 'blue',
                        colortext: 'white--text',
                        img: "https://cdn.vuetifyjs.com/images/logos/vuetify-logo-300.png",
                        title: 'Vuetify',
                        desc: "Tidelift gives software development teams a single source for purchasing and maintaining their software, with professional-grade assurances from the experts who know it best, while seamlessly integrating with existing tools.",
                        doc: "https://vuetifyjs.com/pt-BR/getting-started/quick-start"
                    },
                ],
                menu: [{
                        icon: "home",
                        text: "Home",
                        link: function () {
                            window.location = '##'
                        }
                    },
                    {
                        icon: "face",
                        text: "User",
                        link: function () {
                            window.location = '##'
                        }
                    }
                ]
            }
        },
        methods: {
            linkopen: function (link) {
                window.open(link)
            },
        },
        mounted() {
            var self = this;
            $.ajax({
                url: "/message1",
                dataType: "json"
            }).done(function (response) {
                self.tips[1].using = response.text;
            });
        }
    });
</script>
@endsection
