@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title',$emp->name)


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->

    <v-layout row wrap v-if="!form_view">
        <v-flex xs12 class='text-xs-right'>
            <v-btn color="primary" @click="add()">Adicionar Lista de Tarefas</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-card height='100%'>
                <v-tabs v-model="clists.model" color="primary" dark slider-color="primary">
                    <v-tab v-for='c in clists.data'>
                        @{{c.name}}
                    </v-tab>
                    <v-tabs-items>
                        <v-tab-item v-for='c in clists.data'>

                        </v-tab-item>
                    </v-tabs-items>
                </v-tabs>
            </v-card>
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
                            <v-select v-model="form.profile_id" :items="profile" item-text="name" item-value="id" label="Perfil"
                                :rules="rules.prof_id" persistent-hint required></v-select>
                            <v-autocomplete v-model="form.dependences" :items="task" label="Tarefas" item-text="name"
                                item-value="id" multiple>
                                <template slot="selection" slot-scope="data">
                                    <v-chip :selected="data.selected" close class="chip--select-multi" @input="remove(data.item)">@{{data.item.name}}</v-chip>
                                </template>
                                <template slot="item" slot-scope="data">
                                    <template>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                                            <v-list-tile-sub-title v-html="data.item.description"></v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </template>
                                </template>
                            </v-autocomplete>
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
                clists: {
                    model:0,
                    data:[
                        {
                            name:"Estagiário",
                            tasks:[{
                                name:"Fazer crachá"
                            }]
                        },
                        {
                            name:"Estagiário nex",
                            tasks:[{
                                name:"Fazer crachá nex"
                            }]
                        }
                    ]
                },
                profile: [],
                task: [],
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
                    dep: [
                        v => !!v || 'Campo obrigtório'
                    ],
                    prof_id: [
                        v => !!v || 'Campo obrigtório'
                    ],
                },
                form: {
                    id: "",
                    name: '',
                    profile_id: '',
                    dependences: ''
                },
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
                    profile_id: '',
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
                            if (this.form.id == "") app.notify("Lista de tarefa criada",
                                "success");
                            else app.notify("Edição salva", "success");
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
                    this.task = response['task'];
                    this.profile = response['profiles'];
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
                        app.notify("Lista de tarefa removida", "error");
                    }
                });
            },
            remove(item) {
                const index = this.friends.indexOf(item.name)
                if (index >= 0) this.friends.splice(index, 1)
            }
        },
        watch: {
            isUpdating(val) {
                if (val) {
                    setTimeout(() => (this.isUpdating = false), 3000)
                }
            }
        },
        mounted() {}
    });
</script>
@endsection
