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
                                        <v-text-field v-model='form.email' label="Email" prepend-icon="email" color='white' :rules='rules.email'></v-text-field>
                                        <v-text-field v-model='form.password' label="Senha" prepend-icon="lock" color='white' :rules='rules.password'
                                            :append-icon="show1 ? 'visibility_off' : 'visibility'" :type="show1 ? 'text' : 'password'"
                                            @click:append="show1 = !show1"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 class="text-xs-right">
                                        <v-btn color="white" class="black--text mt-3" block style='border-radius:20px'
                                            @click='login'>Entrar</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-form>
                        </v-container>

                    </v-card>
                </v-flex>
            </v-layout>
        </div>
    </div>

    <v-snackbar v-model="snackbar_notify.model" multi-line timeout="3000" bottom right :color='snackbar_notify.color'>
            @{{snackbar_notify.text}}
            <v-btn flat  @click.native="value = false"><v-icon>clear</v-icon></v-btn>
        </v-snackbar>

</v-content>



@endsection


@section('js')
<script src='{{asset("vuetify/theme.js")}}'></script>
<script>
    app = new Vue({
        el: '#app',

        created() {
            this.$vuetify.theme = $THEME_VUETIFY;
        },
        data() {
            return {
                show1: false,
                form:{
                    email:'',
                    password:''
                },
                rules: {
                    email: [
                        v => !!v || 'Campo obrigtório',
                    ],
                    password: [
                        v => !!v || 'Campo obrigtório',
                    ],
                },
                snackbar_notify:{
                    text:"",
                    model: false,
                    color: "",
                }

            }
        },
        computed: {},
        methods: {
            login: function () {
                if (this.$refs.form.validate()) {
                    $.ajax({
                        url: '{{route("login")}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: this.form,
                        success: response=>{
                            window.location = '{{route("dashboard")}}';
                        },
                        error: response=>{
                            this.notify("Credenciais inválidas","error");
                            this.form.password = '';
                        }
                    })
                }

            },
            notify: function(text,color){
                this.snackbar_notify.text = text;
                this.snackbar_notify.model = true;
                if(this.snackbar_notify.color==null)this.snackbar_notify.color = "black";
                this.snackbar_notify.color = color;
            }

        },
        mounted(){
            $(document).keydown((event) => {
                if (event.which == 13) {
                    this.login();
                }
            });
        }
    });
</script>
@endsection
