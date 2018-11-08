@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Checklists')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex class='text-xs-right'>
            <v-btn color="primary" @click="add()">Adicionar Lista de Tarefas</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel> 
                <v-expansion-panel-content v-for='l in clists'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6 class='font-weight-bold'>
                                @{{l.name}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs1 class='font-weight-bold'>
                                Tipo:
                            </v-flex>
                            <v-flex xs1 v-if="l.type==1">
                                Contratação
                            </v-flex>
                            <v-flex xs1 v-if="l.type==2">
                                Demissão
                            </v-flex>
                            <v-flex xs1 v-if="l.type==3">
                                Transferência
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs3 class='font-weight-bold' v-if="l.dependences.length>0">
                                Dependência:
                            </v-flex>
                            <v-flex xs3>
                                <template v-for="d in l.dependences">@{{d.name}},</template>
                            </v-flex>
                            <v-flex xs12 class='text-xs-right'>
                                <v-btn @click="tasks(l.id)" color="green" outline>
                                    <v-icon dark class='mr-2'>list</v-icon>Tarefas
                                </v-btn>
                                <v-btn @click="edit(l.id)" color="yellow darken-2" outline>
                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                </v-btn>
                                <v-btn @click="destroy(l.id)" color="red" outline>
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
        <v-flex s12>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-3'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" label="Nome" required :rules="rules.name" counter='25'></v-text-field>
                            <v-select v-model="form.type" :items="types" item-text="text" item-value="value" label="Tipo" required
                                :rules="rules.type" persistent-hint></v-select>
                            <v-select v-model="form.dependences" :items="dependencies" item-text="name" item-value="id" label="Dependencias"
                                persistent-hint multiple required></v-select>
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
                clists: [
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
                    type: [
                        v => !!v || 'Campo obrigtório'
                    ],
                },
                form: {
                    id: "",
                    name: '',
                    type: '',
                    dependences: ''
                },
                types: [{
                        text: "Contratação",
                        value: "1",
                    },
                    {
                        text: "Demissão",
                        value: "2",
                    },
                    {
                        text: "Transferência",
                        value: "3",
                    },
                ],
            }
        },
        methods: {
            add: function () {
                this.form_view = true;
                this.form_texts.title = "Criar lista de tarefas";
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
                        url: "{{route('checklist.store')}}",
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
                    url: "{{route('checklist.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.clists = response['clists'];
                    this.dependencies = response['profile'];
                });
            },
            edit: function (id) {
                $.ajax({
                    url: "{{route('checklist.edit')}}",
                    method: "GET",
                    dataType: "json",
                    data: {
                        id: id
                    },
                }).done(response => {
                    this.form_texts.title = "Editar Lista";
                    this.form_texts.button = "Salvar";
                    this.form = response;
                    this.form_view = true;
                });
            },
            destroy: function (id) {
                $.ajax({
                    url: "{{route('checklist.destroy')}}",
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
