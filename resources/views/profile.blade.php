@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Perfis')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex class='text-xs-right'>
            <v-btn color="primary" @click="add()">Adicionar Perfil</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel>
                <v-expansion-panel-content v-for='p in profile'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6 class='font-weight-bold'>
                                @{{p.name}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs3 class='font-weight-bold' v-if="p.clist.length>0">
                                Lista de tarefas relacionadas:
                            </v-flex>
                            <v-flex xs9>
                                <v-layout row wrap>
                                    <v-flex xs12 v-for="d in p.clist">
                                            @{{d.name}}
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12 class='text-xs-right'>
                                <v-btn @click="edit(p.id)" color="yellow darken-2" outline>
                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                </v-btn>
                                <v-btn @click="destroy(p.id)" color="red" outline>
                                    <v-icon dark class='mr-2'>delete</v-icon> Remover
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
                <v-expansion-panel-content v-if='profile.length==0'><div slot="header">Nenhum perfil foi criado</div></v-expansion-panel-content>
            </v-expansion-panel>
        </v-flex>
    </v-layout>

    <v-layout row wrap v-if="form_view">
        <v-flex s12>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-3'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" label="Nome" required :rules="rules.name" counter='25'></v-text-field>
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
                profile: [
                ],
                form_view: false,
                form_texts: {
                    title: "",
                    button: ""
                },
                rules: {
                    name: [
                        v => !!v || 'Campo obrigtório',
                        v => (v && v.length <= 25) || 'Máximo 25 caracteres'
                    ]
                },
                form: {
                    id: "",
                    name: '',
                    checklists: ''
                }
            }
        },
        methods: {
            add: function () {

                this.form_view = true;
                this.form_texts.title = "Criar perfil";
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
                    app.confirm("Criando/alterando Registro!", "Confirmar ação deste Registro?", "green", () => {
                    $.ajax({
                        url: "{{route('profile.store')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data: this.form,
                        success: (response) => {
                            this.list();
                            this.form_view = false;
                            if(this.form.id=="")app.notify("Perfil criado com sucesso!","success");
                            else app.notify("Edição salva","success");
                        }
                    });
                })
                }
            },
            list: function () {
                $.ajax({
                    url: "{{route('profile.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.profile = response;

                });
            },
            edit: function (id) {
                $.ajax({
                    url: "{{route('profile.edit')}}",
                    method: "GET",
                    dataType: "json",
                    data: {
                        id: id
                    },
                }).done(response => {
                    this.form_texts.title = "Editar Perfil";
                    this.form_texts.button = "Salvar";
                    this.form = response;
                    this.form_view = true;
                });
            },
            destroy: function (id) {
                app.confirm("Deletar Registro!", "Deseja deletar este Registro?", "red", () => {
                $.ajax({
                    url: "{{route('profile.destroy')}}",
                    method: "DELETE",
                    dataType: "json",
                    headers: app.headers,
                    data: {
                        id: id
                    },
                    success: (response) => {
                        this.list();
                        app.notify("Perfil removido","error");
                    }
                });
            })
            },
            mounted: function(){
                app.setMenu('profile');
            }
        },
        mounted() {
            this.list();

        }
    });
</script>
@endsection
