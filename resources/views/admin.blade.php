@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Admins')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view && !prof_view">
        <v-flex class='text-xs-right'>
            <v-btn @click="add()" color="primary">Adicionar admin</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel>
                <v-expansion-panel-content v-for='adm in admin'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6>
                                @{{adm.name}}
                            </v-flex>
                            <!--<v-flex xs3 class='text-xs-right'>
                                <span class='mr-2'>@{{adm.checks}}/@{{adm.list}}</span>
                                <v-progress-circular rotate="-90" :value="adm.checks/adm.list*100" color="primary" class='mr-2'
                                    width='7'></v-progress-circular>
                            </v-flex>-->
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs3>E-mail:</v-flex>
                            <v-flex xs3 class='font-weight-bold'>@{{adm.email}}</v-flex>
                            <v-flex xs3>Data admissão</v-flex>
                            <v-flex xs3 class='font-weight-bold'>@{{adm.created_at}}</v-flex>
                            <v-flex xs12 class='text-xs-right'>
                                <v-btn color="blue" outline>
                                    <v-icon dark class='mr-2'>check</v-icon> Empregados
                                </v-btn>
                                <v-btn @click="edit(adm.id)" color="yellow darken-2" outline>
                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                </v-btn>
                                <v-btn @click="destroy(adm.id)" color="red" outline>
                                    <v-icon dark class='mr-2'>delete</v-icon> Remover
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-flex>
    </v-layout>



    <!-- PERFIl -->
    <v-layout row wrap v-if="prof_view2">
            <v-flex xs12 sm6 offset-sm3>
                <v-card>
                    <v-container grid-list-xs>
                        <div class='display-2'>Perfil</div>
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs3>Nome:</v-flex>
                                <v-flex xs9 class='font-weight-bold'>@{{user.name}}</v-flex>
                                <v-flex xs3>E-mail:</v-flex>
                                <v-flex xs9 class='font-weight-bold'>@{{user.email}}</v-flex>
                                <v-flex xs3>Data admissão:</v-flex>
                                <v-flex xs9 class='font-weight-bold'>@{{user.created_at}}</v-flex>
                                <v-flex class='text-xs-center'>
                                    <v-btn @click="edit(user.id)" color="yellow darken-2" outline>
                                        <v-icon dark class='mr-2'>edit</v-icon> Editar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-container>    
                </v-card>        
            </v-flex>
        </v-layout>



    <!-- EDIT-->
    <v-layout row wrap v-if="form_view && !prof_view2">
        <v-flex xs12 sm6 offset-sm3>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-2'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" :rules="rules.name" label="Nome" required></v-text-field>
                            <v-text-field v-model="form.email" :rules="rules.email" label="E-mail" required></v-text-field>
                            <v-text-field v-model="form.password" :append-icon="show1 ? 'visibility_off' : 'visibility'"
                                :rules="rules.password" :type="show1 ? 'text' : 'password'" name="input-10-1"
                                label="Senha" hint="At least 6 characters" counter @click:append="show1 = !show1"></v-text-field>
                            <v-text-field v-model="form.passwordc" :rules="rules.passwordc" :type="show1 ? 'text' : 'password'" name="input-10-1"
                                label="Confirmar Senha"></v-text-field>
                            <v-btn @click="store" color="primary">@{{form_texts.button}}</v-btn>
                        </v-card-text>
                    </v-form>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>



@endsection

@section('l-js')
<script>
    Vue.component("page", {
        props: {
            screen: String
        },
        data() {
            return {
                name: '',
                show1: false,
                admin: [
                ],
                user: [
                ],
                form_view: false,
                form_texts: {
                    title: "",
                    button: ""
                },
                rules: {
                    name: [
                        v => !!v || 'Campo obrigatório',
                        v => (v && v.length <= 25) || 'Máximo 25 caracteres'
                    ],
                    email: [
                        v => !!v || 'E-mail é obrigatório!',
                        v => /.+@.+/.test(v) || 'E-mail deve ser válido!'
                    ],
                    password: [
                        v => !!v || 'Campo obrigatório!',
                        v => (v && v.length >= 6) || 'Mínimo 6 caracteres'
                    ],
                    passwordc: [
                        v => !!v || 'Campo obrigatório!',
                        v => v == this.form.password || 'Senhas não estão iguais!'
                    ],
                },
                form: {
                    id: "",
                    name: '',
                    email: '',
                    password: '',
                    passwordc: '',
                },
                items: [
                ],
                prof_view2:[],
            }
        },
        computed:{
            prof_view: function(){
                if("true"=="{{$prof_view}}")return true;
                return false;
            }
        },
        methods: {
            add: function () {
                this.form_view = true;
                this.form_texts.title = "Criar Admin";
                this.form_texts.button = "Criar";
                this.form = {
                    id: "",
                    name: '',
                    type: '',
                    dependences: ''
                }
            },
            store: function () {
                if (this.$refs.form.validate()) {
                    $.ajax({
                        url: "{{route('admin.store')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data: this.form,
                        success: (response) => {
                            this.list();
                            this.form_view = false;
                            if(this.form.id=="")app.notify("Admin adicionado","success");
                            else app.notify("Edição salva","success");
                        }
                    });
                }
            },
            list: function () {
                $.ajax({
                    url: "{{route('admin.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.admin = response['list'];
                    this.user = response['user'];
                });
            },
            edit: function (id) {
                $.ajax({
                    url: "{{route('admin.edit')}}",
                    method: "GET",
                    dataType: "json",
                    data: {
                        id: id
                    },
                }).done(response => {
                    this.form_texts.title = "Editar Admin";
                    this.form_texts.button = "Salvar";
                    this.form = response;
                    this.form_view = true;
                    this.prof_view2 = false;
                });
            },
            destroy: function (id) {
                $.ajax({
                    url: "{{route('admin.remove')}}",
                    method: "DELETE",
                    dataType: "json",
                    headers: app.headers,
                    data: {
                        id: id
                    },
                    success: (response) => {
                        this.list();
                        app.notify("Admin removido","error");
                    }
                });
            },
        },
        mounted() {
            this.list();
            setTimeout(()=>{app.screen = 5},1);
            this.prof_view2 = this.prof_view;
        }
    });
</script>
@endsection
