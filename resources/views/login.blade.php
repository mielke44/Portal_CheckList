@extends('layouts.vuetify-default')

@section('title')
    Login - Portal Checklist
@endsection

@section('css')
<link href="{{asset('css/extras.css')}}" rel="stylesheet">
@endsection

@section('vuetify-app-content')
<v-content>
    <div style="width:100%;height:100%;background-image:url('/images/background.jpg');" class='extra-background-cover'>
        <div style="width:100%;height:100%;position:absolute" class='extra-background-darkness'></div>
        <div style="width:100%;height:100%;position:absolute">
            <v-layout row wrap fill-height align-center>
                <v-flex offset-xs4 xs4 class='text-xs-center'>
                    <v-card color="red accent-3" dark style='border-radius:10px'>
                        <v-container grid-list-xs>
                            <v-form ref='form' class='extra-error'>
                                <v-layout row wrap fill-height align-center>
                                    <v-flex xs12 class="text-xs-center">
                                        <div class='mb-4 display-2 text-weight-bold'>Login</div>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-text-field label="Email" prepend-icon="email" color='white' :rules='rules.email'></v-text-field>
                                        <v-text-field label="Senha" prepend-icon="lock" color='white' :rules='rules.password'
                                            :append-icon="show1 ? 'visibility_off' : 'visibility'" :type="show1 ? 'text' : 'password'" @click:append="show1 = !show1"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 class="text-xs-right">
                                        <v-btn color="white" class="black--text mt-3" block style='border-radius:20px' @click='login'>Entrar</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-form>
                        </v-container>

                    </v-card>
                </v-flex>
            </v-layout>
        </div>
    </div>



</v-content>



@endsection


@section('js')
<script src='/vuetify/theme.js'></script>
<script>
    app = new Vue({
        el: '#app',

        created() {
            this.$vuetify.theme = $THEME_VUETIFY;
        },
        data() {
            return {
                show1:false,
                rules: {
                    email: [
                        v => !!v || 'Campo obrigtório',
                    ],
                    password: [
                        v => !!v || 'Campo obrigtório',
                    ],
                },

            }
        },
        computed: {},
        methods: {
            login: function(){
                if(this.$refs.form.validate()){

                }
            }
        }
    });
</script>
@endsection
