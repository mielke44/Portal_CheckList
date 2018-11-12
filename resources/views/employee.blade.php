@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Empregados')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
            <v-flex class='text-xs-right'>
                    <v-btn @click="add()" color="primary">Adicionar empregado</v-btn>
                </v-flex>
                <v-flex xs12>
                    <v-expansion-panel>
                        <v-expansion-panel-content v-for='em in employees'>
                            <div slot="header">
                                <v-layout row wrap fill-height align-center>
                                    <v-flex xs6>
                                        @{{em.name}}
                                    </v-flex>
                                    <v-flex xs3>
                                        @{{em.profile}}
                                    </v-flex>
                                    <v-flex xs3 class='text-xs-right'>
                                        <span class='mr-2'>@{{em.checks}}/@{{em.list}}</span>
                                        <v-progress-circular rotate="-90" :value="em.checks/em.list*100" color="primary" class='mr-2'
                                            width='7'></v-progress-circular>
                                    </v-flex>
                                </v-layout>
                            </div>
                            <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-flex xs3>CPF:</v-flex>
                                    <v-flex xs3 class='font-weight-bold'>@{{em.cpf}}</v-flex>
                                    <v-flex xs3>E-mail:</v-flex>
                                    <v-flex xs3 class='font-weight-bold'>@{{em.email}}</v-flex>
                                    <v-flex xs3>Telefone:</v-flex>
                                    <v-flex xs3 class='font-weight-bold'>@{{em.fone}}</v-flex>
                                    <v-flex xs3>Data admissão</v-flex>
                                    <v-flex xs3 class='font-weight-bold'>@{{em.created_at}}</v-flex>
                                    <v-flex xs3>Tipo:</v-flex>
                                    <v-flex xs3 class='font-weight-bold' v-if="em.type==1">Estagiário</v-flex>
                                    <v-flex xs3 class='font-weight-bold' v-if="em.type==2">Efetivado</v-flex>
                                    <v-flex xs12 class='text-xs-right'>
                                        <v-btn color="blue" outline>
                                            <v-icon dark class='mr-2'>check</v-icon> Checklist
                                        </v-btn>
                                        <v-btn @click="edit(em.id)" color="yellow darken-2" outline>
                                            <v-icon dark class='mr-2'>edit</v-icon> Editar
                                        </v-btn>
                                        <v-btn @click="destroy(em.id)" color="red" outline>
                                            <v-icon dark class='mr-2'>delete</v-icon> Remover
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-flex>
    </v-layout>

    <v-layout row wrap v-if="form_view">
        <v-flex xs12 sm6 offset-sm3>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-2'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" :rules="rules.name" label="Name" required></v-text-field>
                            <v-text-field v-model="form.email" :rules="rules.email" label="E-mail" required></v-text-field>
                            <v-text-field mask="###.###.###-##" return-masked-value="true" v-model="form.cpf" :rules="rules.cpf" label="CPF" required></v-text-field>
                            <v-text-field mask="+##(##)#####.####" return-masked-value="true" v-model="form.fone" :rules="rules.fone" label="Telefone" required></v-text-field>
                            <v-flex xs6>
                                <v-select v-model="form.type" :items="items" item-text="text" 
                                item-value="value" label="Tipo" :rules="rules.type" persistent-hint required></v-select>
                            </v-flex>
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
        data() {
            return {
                employees: [
                    
                ],
                dependencies:[
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
                    ],
                    email: [
                        v => !!v || 'E-mail é obrigatório!',
                        v => /.+@.+/.test(v) || 'E-mail deve ser válido!'
                    ],
                    fone:[
                        v => (v && v.length < 18) || 'Máximo 11 caracteres'
                    ],
                    cpf:[
                        v => !!v || 'CPF é obrigatório!',
                        v => (v && v.length < 15) || 'Máximo 11 caracteres'
                    ],
                    type: [
                        v => !!v || 'Campo obrigtório'
                    ],
                },
                form: {
                    id: "",
                    name: '',
                    dependences: '',
                    fone: '',
                    cpf: '',
                    email: '',
                    type: '',
                },
                items: [
                    { 
                        text: 'Efetivado',
                        value: "1",
                        },
                    { 
                        text: 'Estagiário',
                        value: "2",    
                    }
                ],
                }
        },
        methods: {
            add: function () {
                this.form_view = true;
                this.form_texts.title = "Criar Empregado";
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
                        url: "{{route('emp.store')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data: this.form,
                        success: (response) => {
                            this.list();
                            this.form_view = false;
                        }
                    });
                }
            },
            list: function () {
                $.ajax({
                    url: "{{route('emp.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.employees = response;
                    //this.dependencies = response['clist'];
                });
            },
            edit: function (id) {
                $.ajax({
                    url: "{{route('emp.edit')}}",
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
                $.ajax({
                    url: "{{route('emp.remove')}}",
                    method: "DELETE",
                    dataType: "json",
                    headers: app.headers,
                    data: {
                        id: id
                    },
                    success: (response) => {
                        this.list();
                    }
                });
            },
        },
        mounted() {
            this.list();
        }
    });
</script>
@endsection
